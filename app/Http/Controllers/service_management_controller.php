<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Http\Controllers\Controller;

class service_management_controller extends Controller
{
    // 🔹 Reusable Supabase API request
    private function supabaseRequest($endpoint, $params = [])
    {
        $response = Http::withHeaders([
            'apikey' => env('SUPABASE_SERVICE_KEY'),
            'Authorization' => 'Bearer ' . env('SUPABASE_SERVICE_KEY'),
        ])->get(env('SUPABASE_URL') . '/rest/v1/' . $endpoint, $params);

        return $response->json();
    }

public function volunteers(Request $request)
{
    // 1. Get active volunteer applications
    $data = $this->supabaseRequest('volunteer_applications', [
        'select' => 'id,volunteer_event_id,account_id,application_date,skills,remarks,status,accounts!inner(first_name,last_name,email,roles,contact_number,address,birth_date,status)',
        'status' => 'eq.1',
        'accounts.status' => 'eq.1'
    ]);

    // Make sure the response is an array/collection
    $volunteers = collect($data);

    // 2. Get UNIQUE SKILLS from the same data
    $skills = $volunteers
        ->pluck('skills')
        ->filter()
        ->flatMap(function ($item) {
            return explode(',', $item);
        })
        ->map(function ($skill) {
            return trim($skill);
        })
        ->filter()
        ->unique()
        ->values();

    // 3. Search by volunteer name/email
    if ($request->filled('search')) {

        $search = strtolower(trim($request->search));

        $volunteers = $volunteers->filter(function ($item) use ($search) {

            // Make sure item is an array
            if (!is_array($item)) {
                return false;
            }

            // Get account information
            $account = $item['accounts'] ?? [];

            // Make sure accounts is an array
            if (!is_array($account)) {
                return false;
            }

            $firstName = strtolower($account['first_name'] ?? '');
            $lastName = strtolower($account['last_name'] ?? '');
            $email = strtolower($account['email'] ?? '');

            return str_contains($firstName, $search)
                || str_contains($lastName, $search)
                || str_contains($email, $search);
        });
    }

    // 4. Skill filter
    $skillFilter = strtolower(trim($request->search_skill ?? ''));

    if ($skillFilter) {

        $volunteers = $volunteers->filter(function ($item) use ($skillFilter) {

            if (!is_array($item)) {
                return false;
            }

            $skillsString = strtolower($item['skills'] ?? '');

            $skillArray = array_map(
                'trim',
                explode(',', $skillsString)
            );

            return in_array($skillFilter, $skillArray);
        });
    }

    // 5. Convert the data into an easier format for the Blade view
    $volunteers = $volunteers->map(function ($item) {

        $account = $item['accounts'] ?? [];

        if (!is_array($account)) {
            $account = [];
        }

        return (object) [

            // Application information
            'application_id' => $item['id'] ?? null,
            'volunteer_event_id' => $item['volunteer_event_id'] ?? null,
            'account_id' => $item['account_id'] ?? null,
            'application_date' => $item['application_date'] ?? '',
            'skills' => $item['skills'] ?? '',
            'remarks' => $item['remarks'] ?? '',

            // Account information
            'first_name' => $account['first_name'] ?? '',
            'last_name' => $account['last_name'] ?? '',
            'email' => $account['email'] ?? '',
            'roles' => $account['roles'] ?? '',
            'contact_number' => $account['contact_number'] ?? 'N/A',
            'address' => $account['address'] ?? '',
            'birth_date' => $account['birth_date'] ?? '',
        ];
    })
    ->values();

    // 6. Return the view
    return view(
        'service_management',
        compact(
            'volunteers',
            'skills'
        )
    );
}




public function deactivate($id)
{
    $response = Http::withHeaders([
        'apikey' => env('SUPABASE_SERVICE_KEY'),
        'Authorization' => 'Bearer ' . env('SUPABASE_SERVICE_KEY'),
        'Content-Type' => 'application/json',
        'Prefer' => 'return=representation'
    ])->patch(
        env('SUPABASE_URL') . "/rest/v1/accounts?id=eq.$id",
        [
            'status' => 0
        ]
    );

    return response()->json([
        'success' => true,
        'data' => $response->json()
    ]);
}


  public function store(Request $request)
{
    try {
        $activityId = $request->activity_id;
        $accountId = $request->account_id;

        // ✅ 1. Check in Supabase if already exists
        $existing = Http::withHeaders([
            'apikey' => env('SUPABASE_SERVICE_KEY'),
            'Authorization' => 'Bearer ' . env('SUPABASE_SERVICE_KEY'),
        ])->get(env('SUPABASE_URL') . '/rest/v1/volunteer_assignments', [
            'select' => 'id',
            'activity_id' => 'eq.' . $activityId,
            'account_id' => 'eq.' . $accountId
        ])->json();

        if (!empty($existing)) {
            return response()->json([
                'message' => 'Already assigned'
            ], 409); // ✅ use 409 conflict
        }

        // ✅ 2. Insert into Supabase
        $insert = Http::withHeaders([
            'apikey' => env('SUPABASE_SERVICE_KEY'),
            'Authorization' => 'Bearer ' . env('SUPABASE_SERVICE_KEY'),
            'Content-Type' => 'application/json',
            'Prefer' => 'return=representation'
        ])->post(
            env('SUPABASE_URL') . '/rest/v1/volunteer_assignments',
            [
                'account_id' => $accountId,
                'activity_id' => $activityId,
                'date' => $request->date,
                'status' => $request->status,
            ]
        );

        return response()->json([
            'success' => true,
            'data' => $insert->json()
        ]);

    } catch (\Exception $e) {
        return response()->json([
            'error' => $e->getMessage()
        ], 500);
    }
}

public function getVolunteers()
{
    $data = $this->supabaseRequest('volunteer_applications', [
        'select' => 'id,account_id,accounts!inner(first_name,last_name,email)',
        'status' => 'eq.1',
        'accounts.status' => 'eq.1'
    ]);

    $volunteers = collect($data)->map(function ($item) {
        return [
            'id' => $item['account_id'],
            'name' => ($item['accounts']['first_name'] ?? '') . ' ' . ($item['accounts']['last_name'] ?? ''),
            'email' => $item['accounts']['email'] ?? '',
        ];
    });

    return response()->json($volunteers);
}

 public function trackActivity()
    {
        $response = Http::withHeaders([
            'apikey' => env('SUPABASE_SERVICE_KEY'),
            'Authorization' => 'Bearer ' . env('SUPABASE_SERVICE_KEY'),
        ])->get(env('SUPABASE_URL') . '/rest/v1/volunteer_assignments', [
            'select' => '*,accounts(first_name,last_name),activities(name)',
        ]);

        $assignments = $response->json();

        return view('track-activity', compact('assignments'));
    }


    public function index(Request $request)
    {
        $query = Volunteer::query();

        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                ->orWhere('last_name', 'like', "%{$search}%")
                ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $volunteers = $query->get();

        return view('service-management', compact('volunteers'));
    }


   public function destroy($id)
{
    $url = env('SUPABASE_URL') . "/rest/v1/volunteer_assignments?id=eq.$id";

    $response = Http::withHeaders([
        'apikey' => env('SUPABASE_SERVICE_KEY'),
        'Authorization' => 'Bearer ' . env('SUPABASE_SERVICE_KEY'),
        'Content-Type' => 'application/json',
        'Prefer' => 'return=representation'
    ])->delete($url);

    // 🔥 DEBUG (IMPORTANT)
    if ($response->failed()) {
        return response()->json([
            'message' => 'Supabase delete failed',
            'status' => $response->status(),
            'error' => $response->json(),
            'url_used' => $url
        ], 500);
    }

    return response()->json([
        'success' => true,
        'message' => 'Deleted successfully'
    ]);
}
}