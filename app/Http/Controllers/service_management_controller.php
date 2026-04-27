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



public function applications()
{
    $data = $this->supabaseRequest('volunteer_applications', [
        // use !inner so accounts is ALWAYS a single object
        'select' => 'id,status,availability,skills,interests,has_experience,experience_details,accounts!inner(first_name,last_name,email,address,contact_number,birth_date)'
    ]);

    $applications = collect($data)->map(function ($item) {

        $account = is_array($item['accounts']) && isset($item['accounts'][0])
            ? $item['accounts'][0]
            : ($item['accounts'] ?? []);

        return [
            'id' => $item['id'],
            'status' => $item['status'],

            'first_name' => $account['first_name'] ?? '',
            'last_name' => $account['last_name'] ?? '',
            'email' => $account['email'] ?? '',
            'address' => $account['address'] ?? '',
            'contact_number' => $account['contact_number'] ?? '',
            'birth_date' => $account['birth_date'] ?? '',

            'availability' => $item['availability'] ?? '',
            'skills' => $item['skills'] ?? '',
            'interests' => $item['interests'] ?? '',
            'has_experience' => $item['has_experience'] ?? 0,
            'experience_details' => $item['experience_details'] ?? '',
        ];
    });

    $skills = $applications
        ->pluck('skills')
        ->filter()
        ->flatMap(fn($item) => explode(',', $item))
        ->map(fn($s) => trim($s))
        ->filter()
        ->unique()
        ->values();

    $availability = $applications
        ->pluck('availability')
        ->filter()
        ->map(fn($a) => trim($a))
        ->unique()
        ->values();

    $applications = $applications->sortBy(function ($item) {
        return strtolower(($item['first_name'] ?? '') . ' ' . ($item['last_name'] ?? ''));
    })->values();

    return view('applications', compact('applications', 'skills', 'availability'));
}


    // 🔹 Dashboard stats
   public function dashboard()
    {
        $approved = $this->supabaseRequest('volunteer_applications', [
            'select' => 'id',
            'status' => 'eq.1'
        ]);

        $pending = $this->supabaseRequest('volunteer_applications', [
            'select' => 'id',
            'status' => 'eq.0'
        ]);

        return view('service-management.dashboard', [
            'totalVolunteers' => count($approved),
            'pendingApplications' => count($pending),
            'activeVolunteers' => count($approved)
        ]);
    }

    public function approveApplication($id)
    {
        return $this->updateStatus($id, 1);
    }

    public function rejectApplication($id)
    {
        return $this->updateStatus($id, 0);
    }

    public function restoreApplication($id)
    {
        return $this->updateStatus($id, 2);
    }
    public function archiveApplication($id)
    {
        return $this->updateStatus($id, 3);
    }


    private function updateStatus($id, $status)
    {
        $response = Http::withHeaders([
            'apikey' => env('SUPABASE_SERVICE_KEY'),
            'Authorization' => 'Bearer ' . env('SUPABASE_SERVICE_KEY'),
            'Content-Type' => 'application/json',
        ])->patch(env('SUPABASE_URL') . "/rest/v1/volunteer_applications?id=eq.$id", [
            'status' => $status
        ]);

        return response()->json([
            'success' => $response->successful(),
            'data' => $response->json()
        ]);
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
}