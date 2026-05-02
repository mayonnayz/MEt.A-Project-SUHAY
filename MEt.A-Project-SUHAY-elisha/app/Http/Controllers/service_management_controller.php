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
    $data = $this->supabaseRequest('volunteer_applications', [
        'select' => 'id,account_id,availability,skills,interests,has_experience,experience_details,status,accounts!inner(first_name,last_name,email,roles,contact_number,address,birth_date,status)',
        'status' => 'eq.1',
        'accounts.status' => 'eq.1'
    ]);

    $volunteers = collect($data);

    // ✅ UNIQUE SKILLS + AVAILABILITY (FROM SAME DATA)
    $skills = $volunteers
        ->pluck('skills')
        ->filter()
        ->flatMap(fn($item) => explode(',', $item))
        ->map(fn($s) => trim($s))
        ->filter()
        ->unique()
        ->values();

    $availability = $volunteers
        ->pluck('availability')
        ->filter()
        ->map(fn($a) => trim($a))
        ->unique()
        ->values();

    // optional search filter
    if ($request->filled('search')) {
        $search = strtolower($request->search);

        $volunteers = $volunteers->filter(function ($item) use ($search) {
            return str_contains(strtolower($item['accounts']['first_name'] ?? ''), $search)
                || str_contains(strtolower($item['accounts']['last_name'] ?? ''), $search)
                || str_contains(strtolower($item['accounts']['email'] ?? ''), $search);
        });
    }
    $skillFilter = strtolower($request->search_skill ?? '');
    $availabilityFilter = strtolower($request->search_availability ?? '');

    if ($skillFilter || $availabilityFilter) {

        $volunteers = $volunteers->filter(function ($item) use ($skillFilter, $availabilityFilter) {

            $skills = strtolower($item['skills'] ?? '');
            $availability = strtolower($item['availability'] ?? '');

            // split skills into array for better matching
            $skillArray = array_map('trim', explode(',', $skills));

            $skillMatch = !$skillFilter || in_array($skillFilter, $skillArray);
            $availabilityMatch = !$availabilityFilter || str_contains($availability, $availabilityFilter);

            return $skillMatch && $availabilityMatch;
        });
    }
    // map output
    $volunteers = $volunteers->map(function ($item) {
        return (object) [
            'application_id' => $item['id'],
            'account_id' => $item['account_id'] ?? null,
            'first_name' => $item['accounts']['first_name'] ?? '',
            'last_name' => $item['accounts']['last_name'] ?? '',
            'email' => $item['accounts']['email'] ?? '',
            'contact_number' => $item['accounts']['contact_number'] ?? 'N/A',
            'address' => $item['accounts']['address'] ?? '',
            'birth_date' => $item['accounts']['birth_date'] ?? '',
            'availability' => $item['availability'] ?? '',
            'has_experience' => $item['has_experience'] ?? 0,
            'experience_details' => $item['experience_details'] ?? '',
            'skills' => $item['skills'] ?? '',
            'interests' => $item['interests'] ?? '',
        ];
    });

    return view('service_management', compact('volunteers', 'skills', 'availability'));
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