<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class volunteer_controller extends Controller
{
    public function dashboard()
    {
        $userId = session('user_id');

    $userResponse = Http::withHeaders([
        'apikey' => env('SUPABASE_SERVICE_KEY'),
        'Authorization' => 'Bearer ' . env('SUPABASE_SERVICE_KEY'),
    ])->get(env('SUPABASE_URL') . "/rest/v1/accounts?id=eq.$userId&select=*");

    $user = $userResponse->json()[0] ?? null;

        $base = env('SUPABASE_URL');

        $url = $base . "/rest/v1/volunteer_applications?select=*&account_id=eq.$userId";

        $response = Http::withHeaders([
            'apikey' => env('SUPABASE_SERVICE_KEY'),
            'Authorization' => 'Bearer ' . env('SUPABASE_SERVICE_KEY'),
        ])->get($url);

        $data = collect($response->json());

        $totalApplications = $data->count();
        $approved = $data->where('status', 1)->count();
        $pending = $data->where('status', 3)->count();

        return view('Volunteers.dashboard', compact(
            'totalApplications',
            'approved',
            'pending',
            'user'
        ));
    }

    public function updateAccount(Request $request)
    {
        $userId = session('user_id');

        $validator = Validator::make($request->all(), [
        'email' => 'required|email',
        'contact_number' => ['required','regex:/^[0-9]{11}$/'],
        'address' => 'required',
    ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // 2. CHECK EMAIL UNIQUENESS
        $existingEmail = Http::withHeaders([
            'apikey' => env('SUPABASE_SERVICE_KEY'),
            'Authorization' => 'Bearer ' . env('SUPABASE_SERVICE_KEY'),
        ])->get(env('SUPABASE_URL') . "/rest/v1/accounts?email=eq." . $request->email)->json();

        if (!empty($existingEmail) && $existingEmail[0]['id'] != $userId) {
            return back()->with('error', 'Email already exists.');
        }

        $data = [
        'email' => $request->email,
        'address' => $request->address,
        'contact_number' => $request->contact_number,
    ];

    // only update password if user typed something
    if (!empty($request->password)) {
        $data['password'] = $request->password;
    }

    Http::withHeaders([
        'apikey' => env('SUPABASE_SERVICE_KEY'),
        'Authorization' => 'Bearer ' . env('SUPABASE_SERVICE_KEY'),
        'Content-Type' => 'application/json',
    ])->patch(env('SUPABASE_URL') . "/rest/v1/accounts?id=eq.$userId", $data);


        return back()->with('success', 'Account updated successfully!');
    }

    public function activeEvents(Request $request)
{
    $search = $request->search;
    $accountId = session('user_id');
    $applications = [];

    if ($accountId) {
        $appResponse = Http::withHeaders([
            'apikey' => env('SUPABASE_SERVICE_KEY'),
            'Authorization' => 'Bearer ' . env('SUPABASE_SERVICE_KEY'),
        ])->get(env('SUPABASE_URL') . '/rest/v1/volunteer_applications?select=volunteer_event_id&account_id=eq.' . $accountId);

        if ($appResponse->successful()) {
            $applications = collect($appResponse->json())
                ->pluck('volunteer_event_id')
                ->map(fn($id) => (string) $id)
                ->toArray();
        }
    }

    $response = Http::withHeaders([
        'apikey' => env('SUPABASE_SERVICE_KEY'),
        'Authorization' => 'Bearer ' . env('SUPABASE_SERVICE_KEY'),
    ])->get(env('SUPABASE_URL') . "/rest/v1/volunteer_events?status=eq.1&order=date.desc");

    $events = collect($response->json());

    if ($events->isEmpty()) {
        return view('Volunteers.events', ['events' => collect(), 'applications' => $applications]);
    }

    // NGO FETCH
    $ngoIds = $events->pluck('ngo_id')->filter()->unique()->implode(',');

    $ngoResponse = Http::withHeaders([
        'apikey' => env('SUPABASE_SERVICE_KEY'),
        'Authorization' => 'Bearer ' . env('SUPABASE_SERVICE_KEY'),
    ])->get(env('SUPABASE_URL') . "/rest/v1/ngo_profile?id=in.($ngoIds)&select=id,name");

    $ngos = collect($ngoResponse->json());

    $activitiesResponse = Http::withHeaders([
        'apikey' => env('SUPABASE_SERVICE_KEY'),
        'Authorization' => 'Bearer ' . env('SUPABASE_SERVICE_KEY'),
    ])->get(env('SUPABASE_URL') . "/rest/v1/volunteer_activities?select=*");

    $activities = collect($activitiesResponse->json());

    if (!empty($search)) {
        $search = strtolower($search);
        $events = $events->filter(function ($event) use ($search, $ngos) {
            $ngo = $ngos->firstWhere('id', $event['ngo_id'] ?? null);
            $ngoName = strtolower($ngo['name'] ?? '');
            return str_contains(strtolower($event['name'] ?? ''), $search)
                || str_contains($ngoName, $search);
        })->values();
    }

    $events = $events->map(function ($event) use ($ngos, $activities) {
        $ngo = $ngos->firstWhere('id', $event['ngo_id'] ?? null);
        $event['ngo_name'] = $ngo['name'] ?? 'Unknown NGO';

        $eventId = (string) $event['id'];
        $event['activities'] = $activities
            ->filter(fn($a) => (string)($a['volunteer_event_id'] ?? '') === $eventId)
            ->values();

        return $event;
    });

    return view('Volunteers.events', compact('events', 'applications'));
}

    public function applications(Request $request)
{
    $userId = session('user_id');

$userResponse = Http::withHeaders([
    'apikey' => env('SUPABASE_SERVICE_KEY'),
    'Authorization' => 'Bearer ' . env('SUPABASE_SERVICE_KEY'),
])->get(env('SUPABASE_URL') . "/rest/v1/accounts?id=eq.$userId&select=*");

$user = collect($userResponse->json())->first();

    // 1. Applications for user
    $response = Http::withHeaders([
        'apikey' => env('SUPABASE_SERVICE_KEY'),
        'Authorization' => 'Bearer ' . env('SUPABASE_SERVICE_KEY'),
    ])->get(env('SUPABASE_URL') . "/rest/v1/volunteer_applications?account_id=eq.$userId");

    $applications = collect($response->json());

    // 2. Get event IDs
    $eventIds = $applications->pluck('volunteer_event_id')->filter()->unique()->implode(',');

    $eventsResponse = Http::withHeaders([
        'apikey' => env('SUPABASE_SERVICE_KEY'),
        'Authorization' => 'Bearer ' . env('SUPABASE_SERVICE_KEY'),
    ])->get(env('SUPABASE_URL') . "/rest/v1/volunteer_events?id=in.($eventIds)");

    $events = collect($eventsResponse->json());

    // 3. GET NGO IDs FROM EVENTS (IMPORTANT FIX)
    $ngoIds = $events->pluck('ngo_id')->filter()->unique()->implode(',');

    $ngoResponse = Http::withHeaders([
        'apikey' => env('SUPABASE_SERVICE_KEY'),
        'Authorization' => 'Bearer ' . env('SUPABASE_SERVICE_KEY'),
    ])->get(env('SUPABASE_URL') . "/rest/v1/ngo_profile?id=in.($ngoIds)&select=id,name");

    $ngos = collect($ngoResponse->json());

    // 4. Attach everything properly
    $applications = $applications->map(function ($app) use ($events, $ngos, $user) {

        $event = $events->firstWhere('id', $app['volunteer_event_id']);
        $app['event_status'] = $event['status'] ?? null;

        $app['event_name'] = $event['name'] ?? 'Unknown Event';
        $app['date'] = $event['date'] ?? null;

        // FIXED NGO RESOLUTION
        $ngo = $ngos->firstWhere('id', $event['ngo_id'] ?? null);
        $app['ngo_name'] = $ngo['name'] ?? 'Unknown NGO';

        $app['first_name'] = $user['first_name'] ?? null;
        $app['last_name'] = $user['last_name'] ?? null;
        $app['address'] = $user['address'] ?? null;
        $app['contact'] = $user['contact_number'] ?? null;
        $app['email'] = $user['email'] ?? null;
        $app['dob'] = $user['birth_date'] ?? null;

        return $app;
    });

    // SEARCH
if ($request->search) {
    $search = strtolower($request->search);

    $applications = $applications->filter(function ($a) use ($search) {
        return str_contains(strtolower($a['event_name'] ?? ''), $search)
            || str_contains(strtolower($a['ngo_name'] ?? ''), $search);
    });
}

// FILTER (COMBO BOX)
$filter = $request->filter;

if ($filter === 'current') {
    $applications = $applications->filter(function ($a) {
        return ($a['event_status'] ?? null) == 1; // ACTIVE events
    });
}

if ($filter === 'past') {
    $applications = $applications->filter(function ($a) {
        return ($a['event_status'] ?? null) != 1; // NOT active = past
    });
}

// reset indexing
$applications = $applications->values();

    return view('Volunteers.applications', [
        'applications' => $applications->values()
    ]);
}
}