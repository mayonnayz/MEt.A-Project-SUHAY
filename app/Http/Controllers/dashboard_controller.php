<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;
use Carbon\Carbon;

class dashboard_controller extends Controller
{
    public function index()
    {
        $ngoId = session('ngo_id');

        if (!$ngoId) {
            return redirect('/login')->with('error', 'NGO session not found.');
        }

        $supabaseUrl = env('SUPABASE_URL');
        $supabaseKey = env('SUPABASE_SERVICE_KEY');

        $headers = [
            'apikey' => $supabaseKey,
            'Authorization' => 'Bearer ' . $supabaseKey,
        ];
        /*
|--------------------------------------------------------------------------
| NGO PROFILE
|--------------------------------------------------------------------------
*/

$ngoResponse = Http::withHeaders($headers)
    ->get($supabaseUrl . '/rest/v1/ngo_profile', [
        'select' => '*',
        'id' => 'eq.' . $ngoId,
    ]);

$ngo = collect($ngoResponse->json())->first();

$ngoName = $ngo['name']
    ?? $ngo['ngo_name']
    ?? 'Your NGO';


/*
|--------------------------------------------------------------------------
| LOGGED-IN USER
|--------------------------------------------------------------------------
*/

$userId = session('user_id');

$user = null;

if ($userId) {
    $userResponse = Http::withHeaders($headers)
        ->get($supabaseUrl . '/rest/v1/accounts', [
            'select' => 'first_name,last_name',
            'id' => 'eq.' . $userId,
        ]);

    $user = collect($userResponse->json())->first();
}

$userName = trim(
    ($user['first_name'] ?? '') . ' ' .
    ($user['last_name'] ?? '')
);

if ($userName === '') {
    $userName = session('user_name', 'NGO Head');
}

        /*
        |--------------------------------------------------------------------------
        | DONATIONS
        |--------------------------------------------------------------------------
        */

        $donationsResponse = Http::withHeaders($headers)
            ->get($supabaseUrl . '/rest/v1/donations_table', [
                'select' => '*',
                'ngo_id' => 'eq.' . $ngoId,
            ]);

        $donations = collect($donationsResponse->json());

        /*
        |--------------------------------------------------------------------------
        | MONTHLY MONETARY DONATIONS
        |--------------------------------------------------------------------------
        */

        $currentMonth = now()->month;
        $currentYear = now()->year;

        $monthlyDonations = $donations
            ->filter(function ($donation) use ($currentMonth, $currentYear) {

                $type = strtoupper($donation['type'] ?? '');

                if (!in_array($type, ['MONETARY', 'ONLINE_MONETARY'])) {
                    return false;
                }

                if (empty($donation['date'])) {
                    return false;
                }

                $date = Carbon::parse($donation['date']);

                return $date->month == $currentMonth
                    && $date->year == $currentYear;
            })
            ->sum(function ($donation) {
                return (float) ($donation['amount'] ?? 0);
            });


        /*
        |--------------------------------------------------------------------------
        | INVENTORY
        |--------------------------------------------------------------------------
        */

        $inventoryResponse = Http::withHeaders($headers)
            ->get($supabaseUrl . '/rest/v1/inventory', [
                'select' => '*',
                'ngo_id' => 'eq.' . $ngoId,
            ]);

        $inventory = collect($inventoryResponse->json());

        $inventoryItems = $inventory->count();

        $lowStockInventory = $inventory->filter(function ($item) {
            return (float) ($item['current_quantity'] ?? 0)
                <= (float) ($item['minimum_threshold'] ?? 0);
        });

        $lowStockItems = $lowStockInventory->count();


        /*
        |--------------------------------------------------------------------------
        | VOLUNTEER EVENTS
        |--------------------------------------------------------------------------
        */

        $eventsResponse = Http::withHeaders($headers)
            ->get($supabaseUrl . '/rest/v1/volunteer_events', [
                'select' => '*',
                'ngo_id' => 'eq.' . $ngoId,
            ]);

        $events = collect($eventsResponse->json());

        /*
        |--------------------------------------------------------------------------
        | VOLUNTEER APPLICATIONS
        |--------------------------------------------------------------------------
        */

        $applicationsResponse = Http::withHeaders($headers)
            ->get($supabaseUrl . '/rest/v1/volunteer_applications', [
                'select' => '*',
            ]);

        $applications = collect($applicationsResponse->json());

        /*
        |--------------------------------------------------------------------------
        | APPLICATIONS FOR THIS NGO
        |--------------------------------------------------------------------------
        */

        $ngoEventIds = $events
            ->pluck('id')
            ->map(fn ($id) => (string) $id)
            ->toArray();

        $ngoApplications = $applications->filter(function ($application) use ($ngoEventIds) {

            return in_array(
                (string) ($application['volunteer_event_id'] ?? ''),
                $ngoEventIds
            );
        });


        /*
        |--------------------------------------------------------------------------
        | APPLICATION COUNTS
        |--------------------------------------------------------------------------
        */

        $pendingApplications = $ngoApplications
            ->where('status', 0)
            ->count();

        $approvedApplications = $ngoApplications
            ->where('status', 1)
            ->count();

        $rejectedApplications = $ngoApplications
            ->where('status', 2)
            ->count();


        /*
        |--------------------------------------------------------------------------
        | ACTIVE VOLUNTEERS
        |--------------------------------------------------------------------------
        */

        $activeVolunteers = $ngoApplications
            ->where('status', 1)
            ->pluck('account_id')
            ->filter()
            ->unique()
            ->count();


        /*
        |--------------------------------------------------------------------------
        | UPCOMING EVENTS
        |--------------------------------------------------------------------------
        */

        $today = now()->startOfDay();

        $upcomingEvents = $events
            ->filter(function ($event) use ($today) {

                if (($event['status'] ?? null) != 1) {
                    return false;
                }

                if (empty($event['date'])) {
                    return false;
                }

                return Carbon::parse($event['date'])->gte($today);
            })
            ->sortBy(function ($event) {
                return $event['date'] ?? '';
            })
            ->take(3)
            ->map(function ($event) {

                return (object) [
                    'id' => $event['id'] ?? null,
                    'name' => $event['name'] ?? 'Unnamed Event',
                    'date' => $event['date'] ?? null,
                    'description' => $event['description'] ?? null,
                    'status' => $event['status'] ?? null,
                ];
            })
            ->values();


        /*
        |--------------------------------------------------------------------------
        | INVENTORY ALERTS
        |--------------------------------------------------------------------------
        */

        $inventoryAlerts = $lowStockInventory
            ->sortBy(function ($item) {

                $current = (float) ($item['current_quantity'] ?? 0);
                $minimum = (float) ($item['minimum_threshold'] ?? 0);

                return $current - $minimum;
            })
            ->take(5)
            ->map(function ($item) {

                return (object) [
                    'id' => $item['id'] ?? null,
                    'name' => $item['name'] ?? 'Unnamed Item',
                    'current_quantity' => $item['current_quantity'] ?? 0,
                    'minimum_threshold' => $item['minimum_threshold'] ?? 0,
                    'unit' => $item['unit'] ?? '',
                ];
            })
            ->values();


        /*
        |--------------------------------------------------------------------------
        | ACCOUNTS
        |--------------------------------------------------------------------------
        */

        $accountsResponse = Http::withHeaders($headers)
            ->get($supabaseUrl . '/rest/v1/accounts', [
                'select' => '*',
            ]);

        $accounts = collect($accountsResponse->json());

        $accountMap = $accounts->keyBy(function ($account) {
            return (string) ($account['id'] ?? '');
        });


        /*
        |--------------------------------------------------------------------------
        | RECENT APPLICATIONS
        |--------------------------------------------------------------------------
        */

        $eventMap = $events->keyBy(function ($event) {
            return (string) ($event['id'] ?? '');
        });

        $recentApplications = $ngoApplications
            ->sortByDesc(function ($application) {
                return $application['application_date'] ?? '';
            })
            ->take(5)
            ->map(function ($application) use ($accountMap, $eventMap) {

                $accountId = (string) ($application['account_id'] ?? '');
                $eventId = (string) ($application['volunteer_event_id'] ?? '');

                $account = $accountMap->get($accountId);
                $event = $eventMap->get($eventId);

                return (object) [
                    'id' => $application['id'] ?? null,

                    'application_date' =>
                        $application['application_date'] ?? null,

                    'status' =>
                        $application['status'] ?? 0,

                    'first_name' =>
                        $account['first_name'] ?? 'Unknown',

                    'last_name' =>
                        $account['last_name'] ?? '',

                    'event_name' =>
                        $event['name'] ?? 'Unknown Event',
                ];
            })
            ->values();


        /*
        |--------------------------------------------------------------------------
        | RECENT DONATIONS
        |--------------------------------------------------------------------------
        */

        $recentDonations = $donations
            ->sortByDesc(function ($donation) {
                return $donation['date'] ?? '';
            })
            ->take(5)
            ->map(function ($donation) {

                return (object) [
                    'id' => $donation['id'] ?? null,
                    'source' => $donation['source'] ?? null,
                    'amount' => $donation['amount'] ?? 0,
                    'description' => $donation['description'] ?? null,
                    'type' => $donation['type'] ?? null,
                    'date' => $donation['date'] ?? null,
                    'status' => $donation['status'] ?? null,
                ];
            })
            ->values();


        /*
        |--------------------------------------------------------------------------
        | RECENT ACTIVITIES
        |--------------------------------------------------------------------------
        */

        $recentActivities = collect();

        foreach ($recentApplications as $application) {

            $statusText = match ((int) $application->status) {
                0 => 'Pending',
                1 => 'Approved',
                2 => 'Rejected',
                3 => 'Archived',
                default => 'Unknown',
            };

            $recentActivities->push((object) [
                'type' => 'application',
                'title' =>
                    $application->first_name . ' ' .
                    $application->last_name .
                    ' applied for ' .
                    $application->event_name,

                'description' => 'Application ' . strtolower($statusText),

                'date' => $application->application_date,
            ]);
        }

        foreach ($recentDonations as $donation) {

            $recentActivities->push((object) [
                'type' => 'donation',

                'title' =>
                    'Donation from ' .
                    ($donation->source ?? 'Unknown Source'),

                'description' =>
                    $donation->description ??
                    ucfirst(strtolower($donation->type ?? 'Donation')),

                'date' => $donation->date,
            ]);
        }

        $recentActivities = $recentActivities
            ->sortByDesc(function ($activity) {
                return $activity->date ?? '';
            })
            ->take(5)
            ->values();


        /*
        |--------------------------------------------------------------------------
        | DASHBOARD VIEW
        |--------------------------------------------------------------------------
        */

       return view('dashboard', compact(
        'monthlyDonations',
        'inventoryItems',
        'lowStockItems',
        'activeVolunteers',
        'pendingApplications',
        'approvedApplications',
        'rejectedApplications',
        'upcomingEvents',
        'inventoryAlerts',
        'recentApplications',
        'recentDonations',
        'recentActivities',
        'ngoName',
        'userName'
    ));
    }
}