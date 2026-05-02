<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class volunteer_controller extends Controller
{
    /* =========================================================
        DASHBOARD
    ========================================================= */
    public function dashboard()
    {
        $userId = session('user_id');

        // USER
        $userResponse = Http::withHeaders([
            'apikey' => env('SUPABASE_SERVICE_KEY'),
            'Authorization' => 'Bearer ' . env('SUPABASE_SERVICE_KEY'),
        ])->get(env('SUPABASE_URL') . "/rest/v1/accounts?id=eq.$userId&select=*");

        $user = $userResponse->json()[0] ?? null;

        // APPLICATIONS
        $response = Http::withHeaders([
            'apikey' => env('SUPABASE_SERVICE_KEY'),
            'Authorization' => 'Bearer ' . env('SUPABASE_SERVICE_KEY'),
        ])->get(env('SUPABASE_URL') . "/rest/v1/volunteer_applications?account_id=eq.$userId");

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

    /* =========================================================
        UPDATE ACCOUNT
    ========================================================= */
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

        // CHECK EMAIL
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

    public function ngos()
    {
        $response = Http::withHeaders([
            'apikey' => env('SUPABASE_SERVICE_KEY'),
            'Authorization' => 'Bearer ' . env('SUPABASE_SERVICE_KEY'),
        ])->get(env('SUPABASE_URL') . '/rest/v1/ngo_profile?select=*');

        if (!$response->successful()) {
            dd($response->body());
        }

        $ngos = $response->json();

        return view('Volunteers.ngos', compact('ngos'));
    }

    public function updateProfilePicture(Request $request)
    {
        $request->validate([
            'profile_picture' => 'required|image|max:2048',
        ]);

        $userId = session('user_id');

        if (!$userId) {
            return back()->with('error', 'User not logged in.');
        }

        $file = $request->file('profile_picture');

$fileName = uniqid() . '.' . $file->getClientOriginalExtension();

$response = Http::withHeaders([
    'Authorization' => 'Bearer ' . env('SUPABASE_SERVICE_KEY'),
    'Content-Type' => $file->getMimeType(),
])->withBody(
    file_get_contents($file),
    $file->getMimeType()
)->post(
    env('SUPABASE_URL') . "/storage/v1/object/profile-pictures/" . $fileName
);
if (!$response->successful()) {
    dd($response->status(), $response->body());
}

        // if (!$response->successful()) {
        //     return back()->with('error', 'Upload failed.');
        // }

        // Public URL
        $publicUrl = env('SUPABASE_URL')
            . "/storage/v1/object/public/profile-pictures/"
            . $fileName;

        // Save to DB
        Http::withHeaders([
            'apikey' => env('SUPABASE_SERVICE_KEY'),
            'Authorization' => 'Bearer ' . env('SUPABASE_SERVICE_KEY'),
            'Content-Type' => 'application/json',
        ])->patch(
            env('SUPABASE_URL') . "/rest/v1/accounts?id=eq.$userId",
            [
                'profile_picture' => $publicUrl
            ]
        );

        return back()->with('success', 'Profile picture updated!');
    }

    /* =========================================================
        EVENTS
    ========================================================= */
    public function activeEvents(Request $request)
    {
        $search = $request->search;
        $accountId = session('user_id');
        $applications = [];

        // GET USER APPLICATIONS
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

        // GET EVENTS
        $response = Http::withHeaders([
            'apikey' => env('SUPABASE_SERVICE_KEY'),
            'Authorization' => 'Bearer ' . env('SUPABASE_SERVICE_KEY'),
        ])->get(env('SUPABASE_URL') . "/rest/v1/volunteer_events?status=eq.1&order=date.desc");

        $events = collect($response->json());

        if ($events->isEmpty()) {
            return view('Volunteers.events', ['events' => collect(), 'applications' => $applications]);
        }

        // NGO
        $ngoIds = $events->pluck('ngo_id')->filter()->unique()->implode(',');

        $ngos = collect(Http::withHeaders([
            'apikey' => env('SUPABASE_SERVICE_KEY'),
            'Authorization' => 'Bearer ' . env('SUPABASE_SERVICE_KEY'),
        ])->get(env('SUPABASE_URL') . "/rest/v1/ngo_profile?id=in.($ngoIds)&select=id,name")->json());

        // ACTIVITIES
        $activities = collect(Http::withHeaders([
            'apikey' => env('SUPABASE_SERVICE_KEY'),
            'Authorization' => 'Bearer ' . env('SUPABASE_SERVICE_KEY'),
        ])->get(env('SUPABASE_URL') . "/rest/v1/volunteer_activities")->json());

        // SEARCH
        if (!empty($search)) {
            $search = strtolower($search);
            $events = $events->filter(function ($event) use ($search, $ngos) {
                $ngo = $ngos->firstWhere('id', $event['ngo_id']);
                return str_contains(strtolower($event['name']), $search)
                    || str_contains(strtolower($ngo['name'] ?? ''), $search);
            })->values();
        }

        // ATTACH DATA
        $events = $events->map(function ($event) use ($ngos, $activities) {
            $ngo = $ngos->firstWhere('id', $event['ngo_id']);
            $event['ngo_name'] = $ngo['name'] ?? 'Unknown NGO';

            $event['activities'] = $activities
                ->where('volunteer_event_id', $event['id'])
                ->values();

            return $event;
        });

        return view('Volunteers.events', compact('events', 'applications'));
    }

    /* =========================================================
        APPLICATIONS (FIXED 🔥)
    ========================================================= */
    public function applications(Request $request)
    {
        $userId = session('user_id');

        $response = Http::withHeaders([
            'apikey' => env('SUPABASE_SERVICE_KEY'),
            'Authorization' => 'Bearer ' . env('SUPABASE_SERVICE_KEY'),
        ])->get(env('SUPABASE_URL') . "/rest/v1/volunteer_applications?account_id=eq.$userId");

        $applications = collect($response->json());

        if ($applications->isEmpty()) {
            return view('Volunteers.applications', ['applications' => collect()]);
        }

        $eventIds = $applications->pluck('volunteer_event_id')->implode(',');

        $events = collect(Http::withHeaders([
            'apikey' => env('SUPABASE_SERVICE_KEY'),
            'Authorization' => 'Bearer ' . env('SUPABASE_SERVICE_KEY'),
        ])->get(env('SUPABASE_URL') . "/rest/v1/volunteer_events?id=in.($eventIds)")->json());

        $ngoIds = $events->pluck('ngo_id')->implode(',');

        $ngos = collect(Http::withHeaders([
            'apikey' => env('SUPABASE_SERVICE_KEY'),
            'Authorization' => 'Bearer ' . env('SUPABASE_SERVICE_KEY'),
        ])->get(env('SUPABASE_URL') . "/rest/v1/ngo_profile?id=in.($ngoIds)&select=id,name")->json());

        $applications = $applications->map(function ($app) use ($events, $ngos) {
            $event = $events->firstWhere('id', $app['volunteer_event_id']);
            $ngo = $ngos->firstWhere('id', $event['ngo_id'] ?? null);

            $app['event_name'] = $event['name'] ?? 'Unknown Event';
            $app['ngo_name'] = $ngo['name'] ?? 'Unknown NGO';
            $app['date'] = $event['date'] ?? null;

            return $app;
        });

        return view('Volunteers.applications', compact('applications'));
    }

    /* =========================================================
        ASSIGNMENTS (APPROVED ONLY 🔥)
    ========================================================= */
    public function assignments(Request $request)
    {
        $userId = session('user_id');

        $applications = collect(Http::withHeaders([
            'apikey' => env('SUPABASE_SERVICE_KEY'),
            'Authorization' => 'Bearer ' . env('SUPABASE_SERVICE_KEY'),
        ])->get(env('SUPABASE_URL') . "/rest/v1/volunteer_applications?account_id=eq.$userId&status=eq.1")->json());

        if ($applications->isEmpty()) {
            return view('Volunteers.assignments', ['assignments' => collect()]);
        }

        $eventIds = $applications->pluck('volunteer_event_id')->implode(',');

        $events = collect(Http::withHeaders([
            'apikey' => env('SUPABASE_SERVICE_KEY'),
            'Authorization' => 'Bearer ' . env('SUPABASE_SERVICE_KEY'),
        ])->get(env('SUPABASE_URL') . "/rest/v1/volunteer_events?id=in.($eventIds)")->json());

        $ngoIds = $events->pluck('ngo_id')->implode(',');

        $ngos = collect(Http::withHeaders([
            'apikey' => env('SUPABASE_SERVICE_KEY'),
            'Authorization' => 'Bearer ' . env('SUPABASE_SERVICE_KEY'),
        ])->get(env('SUPABASE_URL') . "/rest/v1/ngo_profile?id=in.($ngoIds)&select=id,name")->json());

        $activities = collect(Http::withHeaders([
            'apikey' => env('SUPABASE_SERVICE_KEY'),
            'Authorization' => 'Bearer ' . env('SUPABASE_SERVICE_KEY'),
        ])->get(env('SUPABASE_URL') . "/rest/v1/volunteer_activities")->json());

        $assignments = $applications->map(function ($app) use ($events, $ngos, $activities) {

            $event = $events->firstWhere('id', $app['volunteer_event_id']);
            $ngo = $ngos->firstWhere('id', $event['ngo_id'] ?? null);

            $activity = $activities->firstWhere('volunteer_event_id', $event['id'] ?? null);

            $status = 'On Going';
            if ($event['date'] && strtotime($event['date']) < time()) {
                $status = 'Completed';
            }

            return [
                'event_id' => $event['id'],
                'ngo_name' => $ngo['name'] ?? 'Unknown NGO',
                'event_name' => $event['name'] ?? 'Unknown Event',
                'date' => $event['date'],
                'activity' => $activity['name'] ?? 'No Activity Assigned',
                'status' => $status,
            ];
        });

        return view('Volunteers.assignments', [
            'assignments' => $assignments->values()
        ]);
    }
}