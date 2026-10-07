<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;
use Carbon\Carbon;

class dashboard_controller extends Controller
{
    public function index()
    {
        /*
        |--------------------------------------------------------------------------
        | SUPABASE CONNECTION
        |--------------------------------------------------------------------------
        */

        $supabaseUrl = env('SUPABASE_URL');
        $supabaseKey = env('SUPABASE_SERVICE_KEY');

        $headers = [
            'apikey' => $supabaseKey,
            'Authorization' => 'Bearer ' . $supabaseKey,
            'Content-Type' => 'application/json',
        ];

        /*
        |--------------------------------------------------------------------------
        | LOGGED-IN USER
        |--------------------------------------------------------------------------
        */

        $userId = session('user_id');

        if (!$userId) {
            return redirect('/login')
                ->with('error', 'User session not found.');
        }

        /*
        |--------------------------------------------------------------------------
        | GET ACCOUNT
        |--------------------------------------------------------------------------
        */

        $userResponse = Http::withHeaders($headers)
            ->get($supabaseUrl . '/rest/v1/accounts', [
                'select' => 'id,first_name,last_name,roles,ngo_id',
                'id' => 'eq.' . (int) $userId,
            ]);

        if (!$userResponse->successful()) {
            return redirect('/login')
                ->with('error', 'Unable to retrieve account.');
        }

        $user = collect($userResponse->json())->first();

        if (!$user) {
            return redirect('/login')
                ->with('error', 'Account not found.');
        }

        $userRole = (int) ($user['roles'] ?? 3);

        $userNgoId = $user['ngo_id'] ?? null;

        /*
        |--------------------------------------------------------------------------
        | NGO ID
        |--------------------------------------------------------------------------
        */

        $ngoId = $userNgoId;
        \Log::info('NOTIFICATION DEBUG - LOGGED IN USER', [
    'session_user_id' => $userId,
    'account_id' => $user['id'] ?? null,
    'role' => $userRole,
    'ngo_id' => $ngoId,
]);

        /*
        |--------------------------------------------------------------------------
        | USER NAME
        |--------------------------------------------------------------------------
        */

        $userName = trim(
            ($user['first_name'] ?? '') . ' ' .
            ($user['last_name'] ?? '')
        );

        if ($userName === '') {
            $userName = session('user_name', 'Guest');
        }

        /*
        |--------------------------------------------------------------------------
        | NGO STAFF MUST HAVE AN NGO
        |--------------------------------------------------------------------------
        */

        if (
            in_array($userRole, [0, 1, 2], true)
            && empty($ngoId)
        ) {
            return redirect('/login')
                ->with('error', 'NGO assignment not found.');
        }

        /*
        |--------------------------------------------------------------------------
        | NGO PROFILE
        |--------------------------------------------------------------------------
        */

        $ngo = null;

        $ngoName = 'Your NGO';

        if (!empty($ngoId)) {

            $ngoResponse = Http::withHeaders($headers)
                ->get($supabaseUrl . '/rest/v1/ngo_profile', [
                    'select' => '*',
                    'id' => 'eq.' . (int) $ngoId,
                ]);

            if ($ngoResponse->successful()) {

                $ngo = collect($ngoResponse->json())->first();

                if ($ngo) {
                    $ngoName =
                        $ngo['name']
                        ?? $ngo['ngo_name']
                        ?? 'Your NGO';
                }
            }
        }

/*
|--------------------------------------------------------------------------
| NOTIFICATIONS
|--------------------------------------------------------------------------
*/

$notifications = collect();

if (
    in_array($userRole, [0, 1, 2], true)
    && !empty($ngoId)
) {

    /*
    |--------------------------------------------------------------------------
    | Get ALL notifications belonging to this NGO
    |--------------------------------------------------------------------------
    |
    | We intentionally do NOT filter user_id in the Supabase request.
    | This allows us to receive both:
    |
    | 1. NGO-wide notifications: user_id = NULL
    | 2. Personal notifications: user_id = current account ID
    |
    */

    $notificationResponse = Http::withHeaders($headers)
        ->get(
            $supabaseUrl . '/rest/v1/notifications',
            [
                'select' => '*',
                'related_id' => 'eq.' . (int) $ngoId,
                'order' => 'created_at.desc',
            ]
        );

    \Log::info('NOTIFICATION DEBUG - NGO ALL', [
        'user_id' => $userId,
        'role' => $userRole,
        'ngo_id' => $ngoId,
        'status' => $notificationResponse->status(),
        'body' => $notificationResponse->body(),
    ]);

    if ($notificationResponse->successful()) {

        $notificationData = $notificationResponse->json();

        if (is_array($notificationData)) {

            /*
            |--------------------------------------------------------------------------
            | Keep:
            | - NGO-wide notifications (user_id = NULL)
            | - Personal notifications belonging to this account
            |--------------------------------------------------------------------------
            */

            $notifications = collect($notificationData)
                ->filter(function ($notification) use ($userId) {

                    $notificationUserId = $notification['user_id'] ?? null;

                    return (
                        $notificationUserId === null
                        || (int) $notificationUserId === (int) $userId
                    );
                })
                ->values();
        }

    } else {

        \Log::error(
            'Dashboard NGO notification query failed',
            [
                'user_id' => $userId,
                'user_role' => $userRole,
                'ngo_id' => $ngoId,
                'status' => $notificationResponse->status(),
                'body' => $notificationResponse->body(),
            ]
        );
    }

} elseif ($userRole === 3) {

    /*
    |--------------------------------------------------------------------------
    | PERSONAL USER NOTIFICATIONS
    |--------------------------------------------------------------------------
    */

    $notificationResponse = Http::withHeaders($headers)
        ->get(
            $supabaseUrl . '/rest/v1/notifications',
            [
                'select' => '*',
                'user_id' => 'eq.' . (int) $userId,
                'order' => 'created_at.desc',
            ]
        );

    \Log::info('NOTIFICATION DEBUG - PERSONAL', [
        'user_id' => $userId,
        'role' => $userRole,
        'status' => $notificationResponse->status(),
        'body' => $notificationResponse->body(),
    ]);

    if ($notificationResponse->successful()) {

        $notificationData = $notificationResponse->json();

        if (is_array($notificationData)) {
            $notifications = collect($notificationData);
        }

    } else {

        \Log::error(
            'Dashboard personal notification query failed',
            [
                'user_id' => $userId,
                'user_role' => $userRole,
                'status' => $notificationResponse->status(),
                'body' => $notificationResponse->body(),
            ]
        );
    }
}


/*
|--------------------------------------------------------------------------
| SORT NOTIFICATIONS
|--------------------------------------------------------------------------
*/

$notifications = $notifications
    ->sortByDesc(function ($notification) {
        return $notification['created_at'] ?? '';
    })
    ->values();


/*
|--------------------------------------------------------------------------
| COUNT UNREAD
|--------------------------------------------------------------------------
*/

$unreadNotifications = $notifications
    ->filter(function ($notification) {
        return (int) ($notification['is_read'] ?? 0) === 0;
    })
    ->count();


/*
|--------------------------------------------------------------------------
| LIMIT DISPLAY
|--------------------------------------------------------------------------
*/

$notifications = $notifications
    ->take(10)
    ->values();


\Log::info('FINAL NOTIFICATIONS TO BLADE', [
    'count' => $notifications->count(),
    'unread' => $unreadNotifications,
    'data' => $notifications->toArray(),
]);
        /*
        |--------------------------------------------------------------------------
        | DONATIONS
        |--------------------------------------------------------------------------
        */

        $donations = collect();

        if (!empty($ngoId)) {

            $donationsResponse = Http::withHeaders($headers)
                ->get(
                    $supabaseUrl . '/rest/v1/donations_table',
                    [
                        'select' => '*',
                        'ngo_id' => 'eq.' . (int) $ngoId,
                    ]
                );

            if ($donationsResponse->successful()) {

                $donations =
                    collect($donationsResponse->json());
            }
        }

        /*
        |--------------------------------------------------------------------------
        | MONTHLY MONETARY DONATIONS
        |--------------------------------------------------------------------------
        */

        $currentMonth = now()->month;
        $currentYear = now()->year;

        $monthlyDonations = $donations
            ->filter(function ($donation) use (
                $currentMonth,
                $currentYear
            ) {

                $type = strtoupper(
                    $donation['type'] ?? ''
                );

                if (!in_array(
                    $type,
                    [
                        'MONETARY',
                        'ONLINE_MONETARY'
                    ]
                )) {
                    return false;
                }

                if (empty($donation['date'])) {
                    return false;
                }

                try {

                    $date = Carbon::parse(
                        $donation['date']
                    );

                    return
                        $date->month == $currentMonth
                        &&
                        $date->year == $currentYear;

                } catch (\Exception $e) {

                    return false;
                }
            })
            ->sum(function ($donation) {

                return (float) (
                    $donation['amount'] ?? 0
                );
            });

        /*
        |--------------------------------------------------------------------------
        | INVENTORY
        |--------------------------------------------------------------------------
        */

        $inventory = collect();

        if (!empty($ngoId)) {

            $inventoryResponse = Http::withHeaders($headers)
                ->get(
                    $supabaseUrl . '/rest/v1/inventory',
                    [
                        'select' => '*',
                        'ngo_id' => 'eq.' . (int) $ngoId,
                    ]
                );

            if ($inventoryResponse->successful()) {

                $inventory =
                    collect($inventoryResponse->json());
            }
        }

        $inventoryItems =
            $inventory->count();

        /*
        |--------------------------------------------------------------------------
        | LOW STOCK INVENTORY
        |--------------------------------------------------------------------------
        */

        $lowStockInventory = $inventory
            ->filter(function ($item) {

                return
                    (float) (
                        $item['current_quantity'] ?? 0
                    )
                    <=
                    (float) (
                        $item['minimum_threshold'] ?? 0
                    );
            });

        $lowStockItems =
            $lowStockInventory->count();

        /*
        |--------------------------------------------------------------------------
        | VOLUNTEER EVENTS
        |--------------------------------------------------------------------------
        */

        $events = collect();

        if (!empty($ngoId)) {

            $eventsResponse = Http::withHeaders($headers)
                ->get(
                    $supabaseUrl . '/rest/v1/volunteer_events',
                    [
                        'select' => '*',
                        'ngo_id' => 'eq.' . (int) $ngoId,
                    ]
                );

            if ($eventsResponse->successful()) {

                $events =
                    collect($eventsResponse->json());
            }
        }

        /*
        |--------------------------------------------------------------------------
        | VOLUNTEER APPLICATIONS
        |--------------------------------------------------------------------------
        */

        $applications = collect();

        $applicationsResponse = Http::withHeaders($headers)
            ->get(
                $supabaseUrl . '/rest/v1/volunteer_applications',
                [
                    'select' => '*',
                ]
            );

        if ($applicationsResponse->successful()) {

            $applications =
                collect($applicationsResponse->json());
        }

        /*
        |--------------------------------------------------------------------------
        | APPLICATIONS FOR THIS NGO
        |--------------------------------------------------------------------------
        */

        $ngoEventIds = $events
            ->pluck('id')
            ->map(function ($id) {

                return (string) $id;
            })
            ->toArray();

        $ngoApplications =
            $applications->filter(
                function ($application) use (
                    $ngoEventIds
                ) {

                    return in_array(
                        (string) (
                            $application['volunteer_event_id']
                            ?? ''
                        ),
                        $ngoEventIds
                    );
                }
            );

        /*
        |--------------------------------------------------------------------------
        | APPLICATION COUNTS
        |--------------------------------------------------------------------------
        */

        $pendingApplications =
            $ngoApplications
                ->where('status', 0)
                ->count();

        $approvedApplications =
            $ngoApplications
                ->where('status', 1)
                ->count();

        $rejectedApplications =
            $ngoApplications
                ->where('status', 2)
                ->count();

        /*
        |--------------------------------------------------------------------------
        | ACTIVE VOLUNTEERS
        |--------------------------------------------------------------------------
        */

        $activeVolunteers =
            $ngoApplications
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

        $today =
            now()->startOfDay();

        $upcomingEvents =
            $events
                ->filter(function ($event) use ($today) {

                    if (($event['status'] ?? null) != 1) {
                        return false;
                    }

                    if (empty($event['date'])) {
                        return false;
                    }

                    try {

                        return Carbon::parse(
                            $event['date']
                        )->gte($today);

                    } catch (\Exception $e) {

                        return false;
                    }
                })
                ->sortBy(function ($event) {

                    return $event['date'] ?? '';
                })
                ->take(3)
                ->map(function ($event) {

                    return (object) [

                        'id' =>
                            $event['id'] ?? null,

                        'name' =>
                            $event['name']
                            ?? 'Unnamed Event',

                        'date' =>
                            $event['date'] ?? null,

                        'description' =>
                            $event['description']
                            ?? null,

                        'status' =>
                            $event['status'] ?? null,
                    ];
                })
                ->values();

        /*
        |--------------------------------------------------------------------------
        | INVENTORY ALERTS
        |--------------------------------------------------------------------------
        */

        $inventoryAlerts =
            $lowStockInventory
                ->sortBy(function ($item) {

                    $current =
                        (float) (
                            $item['current_quantity']
                            ?? 0
                        );

                    $minimum =
                        (float) (
                            $item['minimum_threshold']
                            ?? 0
                        );

                    return $current - $minimum;
                })
                ->take(5)
                ->map(function ($item) {

                    return (object) [

                        'id' =>
                            $item['id'] ?? null,

                        'name' =>
                            $item['name']
                            ?? 'Unnamed Item',

                        'current_quantity' =>
                            $item['current_quantity']
                            ?? 0,

                        'minimum_threshold' =>
                            $item['minimum_threshold']
                            ?? 0,

                        'unit' =>
                            $item['unit'] ?? '',
                    ];
                })
                ->values();

        /*
        |--------------------------------------------------------------------------
        | ACCOUNTS
        |--------------------------------------------------------------------------
        */

        $accounts = collect();

        $accountsResponse = Http::withHeaders($headers)
            ->get(
                $supabaseUrl . '/rest/v1/accounts',
                [
                    'select' => '*',
                ]
            );

        if ($accountsResponse->successful()) {

            $accounts =
                collect($accountsResponse->json());
        }

        $accountMap =
            $accounts->keyBy(function ($account) {

                return (string) (
                    $account['id'] ?? ''
                );
            });

        /*
        |--------------------------------------------------------------------------
        | RECENT APPLICATIONS
        |--------------------------------------------------------------------------
        */

        $eventMap =
            $events->keyBy(function ($event) {

                return (string) (
                    $event['id'] ?? ''
                );
            });

        $recentApplications =
            $ngoApplications
                ->sortByDesc(function ($application) {

                    return
                        $application['application_date']
                        ?? '';
                })
                ->take(5)
                ->map(function ($application) use (
                    $accountMap,
                    $eventMap
                ) {

                    $accountId =
                        (string) (
                            $application['account_id']
                            ?? ''
                        );

                    $eventId =
                        (string) (
                            $application['volunteer_event_id']
                            ?? ''
                        );

                    $account =
                        $accountMap->get($accountId);

                    $event =
                        $eventMap->get($eventId);

                    return (object) [

                        'id' =>
                            $application['id']
                            ?? null,

                        'application_date' =>
                            $application['application_date']
                            ?? null,

                        'status' =>
                            $application['status']
                            ?? 0,

                        'first_name' =>
                            $account['first_name']
                            ?? 'Unknown',

                        'last_name' =>
                            $account['last_name']
                            ?? '',

                        'event_name' =>
                            $event['name']
                            ?? 'Unknown Event',
                    ];
                })
                ->values();

        /*
        |--------------------------------------------------------------------------
        | RECENT DONATIONS
        |--------------------------------------------------------------------------
        */

        $recentDonations =
            $donations
                ->sortByDesc(function ($donation) {

                    return
                        $donation['date'] ?? '';
                })
                ->take(5)
                ->map(function ($donation) {

                    return (object) [

                        'id' =>
                            $donation['id']
                            ?? null,

                        'source' =>
                            $donation['source']
                            ?? null,

                        'amount' =>
                            $donation['amount']
                            ?? 0,

                        'description' =>
                            $donation['description']
                            ?? null,

                        'type' =>
                            $donation['type']
                            ?? null,

                        'date' =>
                            $donation['date']
                            ?? null,

                        'status' =>
                            $donation['status']
                            ?? null,
                    ];
                })
                ->values();

        /*
        |--------------------------------------------------------------------------
        | RECENT ACTIVITIES
        |--------------------------------------------------------------------------
        */

        $recentActivities = collect();

        /*
        | Recent volunteer applications
        */

        foreach ($recentApplications as $application) {

            $statusText = match (
                (int) $application->status
            ) {

                0 => 'Pending',

                1 => 'Approved',

                2 => 'Rejected',

                3 => 'Archived',

                default => 'Unknown',
            };

            $recentActivities->push(
                (object) [

                    'type' =>
                        'application',

                    'title' =>
                        $application->first_name
                        . ' '
                        . $application->last_name
                        . ' applied for '
                        . $application->event_name,

                    'description' =>
                        'Application '
                        . strtolower($statusText),

                    'date' =>
                        $application->application_date,
                ]
            );
        }

        /*
        | Recent donations
        */

        foreach ($recentDonations as $donation) {

            $recentActivities->push(
                (object) [

                    'type' =>
                        'donation',

                    'title' =>
                        'Donation from '
                        . (
                            $donation->source
                            ?? 'Unknown Source'
                        ),

                    'description' =>
                        $donation->description
                        ??
                        ucfirst(
                            strtolower(
                                $donation->type
                                ?? 'Donation'
                            )
                        ),

                    'date' =>
                        $donation->date,
                ]
            );
        }

        /*
        |--------------------------------------------------------------------------
        | SORT RECENT ACTIVITIES
        |--------------------------------------------------------------------------
        */

        $recentActivities =
            $recentActivities
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

        return view(
    'dashboard',
    compact(
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
        'userName',
        'notifications',
        'unreadNotifications'
    )
);
    }
}