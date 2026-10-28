<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;
use Carbon\Carbon;

class dashboard_controller extends Controller
{
    public function index()
    {
       

        $supabaseUrl = rtrim(env('SUPABASE_URL'), '/');
        $supabaseKey = env('SUPABASE_SERVICE_KEY');

        $headers = [
            'apikey' => $supabaseKey,
            'Authorization' => 'Bearer ' . $supabaseKey,
            'Content-Type' => 'application/json',
        ];

        

        $userId = session('user_id');

        if (!$userId) {
            return redirect('/login')
                ->with('error', 'User session not found.');
        }

        
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
        $ngoId = $userNgoId;

        

        $userName = trim(
            ($user['first_name'] ?? '') . ' ' .
            ($user['last_name'] ?? '')
        );

        if ($userName === '') {
            $userName = session('user_name', 'Guest');
        }

        

        if (
            in_array($userRole, [0, 1, 2], true)
            && empty($ngoId)
        ) {
            return redirect('/login')
                ->with('error', 'NGO assignment not found.');
        }

        

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
                    $ngoName = $ngo['name']
                        ?? $ngo['ngo_name']
                        ?? 'Your NGO';
                }
            }
        }

        

            
        
$updatePosts = collect();

if (!empty($ngoId)) {

    // Fetch NGO posts.
    $updatePostsResponse = Http::withHeaders($headers)
        ->get($supabaseUrl . '/rest/v1/update_posts', [
            'select' => '*',
            'ngo_id' => 'eq.' . (int) $ngoId,
            'order' => 'id.desc',
        ]);

    if ($updatePostsResponse->successful()) {

        $updatePosts = collect($updatePostsResponse->json())
            ->map(function ($post) {
                $post['image'] = null;

                return (object) $post;
            })
            ->values();

        // Collect post IDs.
        $postIds = $updatePosts
            ->pluck('id')
            ->filter()
            ->unique()
            ->values()
            ->all();

        if (!empty($postIds)) {

            // Fetch media associated with these posts.
            $mediaResponse = Http::withHeaders($headers)
                ->get($supabaseUrl . '/rest/v1/media_table', [
                    'select' => 'key_id,type,path',
                    'type' => 'eq.POST',
                    'key_id' => 'in.(' . implode(',', $postIds) . ')',
                ]);

            if ($mediaResponse->successful()) {

                $postImages = collect($mediaResponse->json())
                    ->keyBy(function ($media) {
                        return (string) $media['key_id'];
                    });

                // Attach image URLs to their respective posts.
                $updatePosts = $updatePosts
                    ->map(function ($post) use ($postImages) {

                        $media = $postImages->get(
                            (string) $post->id
                        );

                        $post->image = $media['path'] ?? null;

                        return $post;
                    });

            } else {

                \Log::error('Failed to fetch NGO update images.', [
                    'status' => $mediaResponse->status(),
                    'response' => $mediaResponse->body(),
                    'post_ids' => $postIds,
                    'ngo_id' => $ngoId,
                ]);
            }

        }

    } else {

        \Log::error('Failed to fetch NGO updates.', [
            'status' => $updatePostsResponse->status(),
            'response' => $updatePostsResponse->body(),
            'ngo_id' => $ngoId,
        ]);
    }
}



        $notifications = collect();
        $unreadNotifications = 0;

        
        $donations = collect();

        if (!empty($ngoId)) {
            $donationsResponse = Http::withHeaders($headers)
                ->get($supabaseUrl . '/rest/v1/donations_table', [
                    'select' => '*',
                    'ngo_id' => 'eq.' . (int) $ngoId,
                ]);

            if ($donationsResponse->successful()) {
                $donations = collect($donationsResponse->json());
            }
        }

        

        $currentMonth = now()->month;
        $currentYear = now()->year;

        $monthlyDonations = $donations
            ->filter(function ($donation) use (
                $currentMonth,
                $currentYear
            ) {
                $type = strtoupper($donation['type'] ?? '');

                if (!in_array($type, [
                    'MONETARY',
                    'ONLINE_MONETARY',
                ], true)) {
                    return false;
                }

                if (empty($donation['date'])) {
                    return false;
                }

                try {
                    $date = Carbon::parse($donation['date']);

                    return $date->month == $currentMonth
                        && $date->year == $currentYear;
                } catch (\Exception $e) {
                    return false;
                }
            })
            ->sum(function ($donation) {
                return (float) ($donation['amount'] ?? 0);
            });

        

        $inventory = collect();

        if (!empty($ngoId)) {
            $inventoryResponse = Http::withHeaders($headers)
                ->get($supabaseUrl . '/rest/v1/inventory', [
                    'select' => '*',
                    'ngo_id' => 'eq.' . (int) $ngoId,
                ]);

            if ($inventoryResponse->successful()) {
                $inventory = collect($inventoryResponse->json());
            }
        }

        $inventoryItems = $inventory->count();

        $lowStockInventory = $inventory
            ->filter(function ($item) {
                return (float) ($item['current_quantity'] ?? 0)
                    <= (float) ($item['minimum_threshold'] ?? 0);
            });

        $lowStockItems = $lowStockInventory->count();


        $events = collect();

        if (!empty($ngoId)) {
            $eventsResponse = Http::withHeaders($headers)
                ->get($supabaseUrl . '/rest/v1/volunteer_events', [
                    'select' => '*',
                    'ngo_id' => 'eq.' . (int) $ngoId,
                ]);

            if ($eventsResponse->successful()) {
                $events = collect($eventsResponse->json());
            }
        }


        $applications = collect();

        $applicationsResponse = Http::withHeaders($headers)
            ->get($supabaseUrl . '/rest/v1/volunteer_applications', [
                'select' => '*',
            ]);

        if ($applicationsResponse->successful()) {
            $applications = collect($applicationsResponse->json());
        }

        $ngoEventIds = $events
            ->pluck('id')
            ->map(function ($id) {
                return (string) $id;
            })
            ->toArray();

        $ngoApplications = $applications->filter(
            function ($application) use ($ngoEventIds) {
                return in_array(
                    (string) ($application['volunteer_event_id'] ?? ''),
                    $ngoEventIds,
                    true
                );
            }
        );


        $pendingApplications = $ngoApplications
            ->where('status', 0)
            ->count();

        $approvedApplications = $ngoApplications
            ->where('status', 1)
            ->count();

        $rejectedApplications = $ngoApplications
            ->where('status', 2)
            ->count();


        $activeVolunteers = $ngoApplications
            ->where('status', 1)
            ->pluck('account_id')
            ->filter()
            ->unique()
            ->count();


        $today = now()->startOfDay();

        $upcomingEvents = $events
            ->filter(function ($event) use ($today) {
                if (($event['status'] ?? null) != 1) {
                    return false;
                }

                if (empty($event['date'])) {
                    return false;
                }

                try {
                    return Carbon::parse($event['date'])->gte($today);
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
                    'id' => $event['id'] ?? null,
                    'name' => $event['name'] ?? 'Unnamed Event',
                    'date' => $event['date'] ?? null,
                    'description' => $event['description'] ?? null,
                    'status' => $event['status'] ?? null,
                ];
            })
            ->values();

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

        $accounts = collect();

        $accountsResponse = Http::withHeaders($headers)
            ->get($supabaseUrl . '/rest/v1/accounts', [
                'select' => '*',
            ]);

        if ($accountsResponse->successful()) {
            $accounts = collect($accountsResponse->json());
        }

        $accountMap = $accounts->keyBy(function ($account) {
            return (string) ($account['id'] ?? '');
        });

       

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
                    'application_date' => $application['application_date'] ?? null,
                    'status' => $application['status'] ?? 0,
                    'first_name' => $account['first_name'] ?? 'Unknown',
                    'last_name' => $account['last_name'] ?? '',
                    'event_name' => $event['name'] ?? 'Unknown Event',
                ];
            })
            ->values();


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
                'title' => $application->first_name
                    . ' '
                    . $application->last_name
                    . ' applied for '
                    . $application->event_name,
                'description' => 'Application ' . strtolower($statusText),
                'date' => $application->application_date,
            ]);
        }


        foreach ($recentDonations as $donation) {
            $recentActivities->push((object) [
                'type' => 'donation',
                'title' => 'Donation from '
                    . ($donation->source ?? 'Unknown Source'),
                'description' => $donation->description
                    ?? ucfirst(strtolower($donation->type ?? 'Donation')),
                'date' => $donation->date,
            ]);
        }


        $recentActivities = $recentActivities
            ->sortByDesc(function ($activity) {
                return $activity->date ?? '';
            })
            ->take(5)
            ->values();


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
            'userName',
            'notifications',
            'unreadNotifications',
            'updatePosts'
        ));
    }
}
