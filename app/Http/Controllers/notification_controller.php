<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class notification_controller extends Controller
{
    private function supabase()
    {
        return Http::withHeaders([
            'apikey' => env('SUPABASE_SERVICE_KEY'),
            'Authorization' => 'Bearer ' . env('SUPABASE_SERVICE_KEY'),
            'Content-Type' => 'application/json',
        ])->baseUrl(
            rtrim(env('SUPABASE_URL'), '/') . '/rest/v1/'
        );
    }

    private function getLoggedInAccount()
    {
        $userId = session('user_id');

        if (!$userId || !is_numeric($userId)) {
            return [
                'error' => response()->json([
                    'success' => false,
                    'message' => 'User is not logged in.',
                ], 401),
            ];
        }

        $response = $this->supabase()->get('accounts', [
            'select' => 'id,roles,ngo_id',
            'id' => 'eq.' . (int) $userId,
            'limit' => 1,
        ]);

        if (!$response->successful()) {
            Log::error('Failed to retrieve account for notifications.', [
                'user_id' => $userId,
                'status' => $response->status(),
                'body' => $response->body(),
            ]);

            return [
                'error' => response()->json([
                    'success' => false,
                    'message' => 'Unable to verify your account.',
                ], 500),
            ];
        }

        $account = collect($response->json())->first();

        if (!$account) {
            return [
                'error' => response()->json([
                    'success' => false,
                    'message' => 'User account not found.',
                ], 404),
            ];
        }

        return [
            'account' => $account,
            'user_id' => (int) $account['id'],
            'role' => (int) ($account['roles'] ?? 3),
            'ngo_id' => $account['ngo_id'] ?? null,
        ];
    }

    //is_read = 0 : Unread
    //is_read = 1 : Read
    //is_read = 2 : Deleted for this account

    public function getNotifications()
    {
        try {
            $accountData = $this->getLoggedInAccount();

            if (isset($accountData['error'])) {
                return $accountData['error'];
            }

            $userId = (int) $accountData['user_id'];
            $role = (int) $accountData['role'];
            $ngoId = $accountData['ngo_id'];

            
            $statusResponse = $this->supabase()->get(
                'notification_status',
                [
                    'select' => 'notification_id,is_read,updated_at',
                    'user_id' => 'eq.' . $userId,
                ]
            );

            if (!$statusResponse->successful()) {
                Log::error('Failed to fetch notification statuses.', [
                    'user_id' => $userId,
                    'status' => $statusResponse->status(),
                    'body' => $statusResponse->body(),
                ]);

                return response()->json([
                    'success' => false,
                    'message' => 'Unable to load notification statuses.',
                ], 500);
            }

            $statuses = $statusResponse->json() ?? [];

            $readStatuses = [];
            $personalNotificationIds = [];
            $hiddenNotificationIds = [];

            foreach ($statuses as $status) {
                $notificationId = (int) $status['notification_id'];
                $isRead = (int) ($status['is_read'] ?? 0);

                // Exclude notifications deleted by this account.
                if ($isRead === 2) {
                    $hiddenNotificationIds[] = $notificationId;
                    continue;
                }

                $readStatuses[(string) $notificationId] = (
                    $isRead === 1
                );

                $personalNotificationIds[] = $notificationId;
            }

            $notifications = [];

            if (in_array($role, [0, 1, 2], true) && $ngoId !== null) {
                $ngoFilters = [
                    'select' => 'id,title,message,type,reference_id,created_at,related_id',
                    'related_id' => 'eq.' . (int) $ngoId,
                    'order' => 'created_at.desc,id.desc',
                    'limit' => 100,
                ];

                if ($role === 0) {
            
                }

               
                elseif ($role === 1) {
                    $ngoFilters['type'] = 'eq.Volunteer Application';
                }

               
                elseif ($role === 2) {
                    $ngoFilters['type'] = 'eq.Donation';
                }

                $ngoResponse = $this->supabase()->get(
                    'notifications',
                    $ngoFilters
                );

                if (!$ngoResponse->successful()) {
                    Log::error('Failed to fetch NGO notifications.', [
                        'user_id' => $userId,
                        'role' => $role,
                        'ngo_id' => $ngoId,
                        'status' => $ngoResponse->status(),
                        'body' => $ngoResponse->body(),
                    ]);

                    return response()->json([
                        'success' => false,
                        'message' => 'Unable to load NGO notifications.',
                    ], 500);
                }

                $notifications = $ngoResponse->json() ?? [];
            }


            $personalNotificationIds = array_values(
                array_unique($personalNotificationIds)
            );

            if (!empty($personalNotificationIds)) {
                $ids = implode(',', $personalNotificationIds);

                $personalResponse = $this->supabase()->get(
                    'notifications',
                    [
                        'select' => 'id,title,message,type,reference_id,created_at,related_id',
                        'id' => 'in.(' . $ids . ')',
                        'related_id' => 'is.null',
                        'order' => 'created_at.desc,id.desc',
                        'limit' => 100,
                    ]
                );

                if (!$personalResponse->successful()) {
                    Log::error('Failed to fetch personal notifications.', [
                        'user_id' => $userId,
                        'status' => $personalResponse->status(),
                        'body' => $personalResponse->body(),
                    ]);

                    return response()->json([
                        'success' => false,
                        'message' => 'Unable to load personal notifications.',
                    ], 500);
                }

                $notifications = array_merge(
                    $notifications,
                    $personalResponse->json() ?? []
                );
            }

     
            $uniqueNotifications = [];

            foreach ($notifications as $notification) {
                $id = (string) $notification['id'];

                if (in_array(
                    (int) $id,
                    $hiddenNotificationIds,
                    true
                )) {
                    continue;
                }

                $notification['is_read'] =
                    $readStatuses[$id] ?? false;

                $uniqueNotifications[$id] = $notification;
            }

            $notifications = array_values($uniqueNotifications);

     
            usort($notifications, function ($a, $b) {
                $dateA = strtotime($a['created_at'] ?? '') ?: 0;
                $dateB = strtotime($b['created_at'] ?? '') ?: 0;

                if ($dateA === $dateB) {
                    return (int) $b['id'] <=> (int) $a['id'];
                }

                return $dateB <=> $dateA;
            });


            $unreadCount = count(array_filter(
                $notifications,
                fn ($notification) => !$notification['is_read']
            ));

            return response()->json([
                'success' => true,
                'notifications' => $notifications,
                'unread_count' => $unreadCount,
            ]);

        } catch (\Throwable $e) {
            Log::error('Notification loading failed.', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Unable to load notifications.',
            ], 500);
        }
    }


    public function markAsRead($notificationId)
    {
        try {
            $accountData = $this->getLoggedInAccount();

            if (isset($accountData['error'])) {
                return $accountData['error'];
            }

            $accountId = (int) $accountData['user_id'];
            $userRole = (int) $accountData['role'];
            $ngoId = $accountData['ngo_id'];

            if (
                !is_numeric($notificationId) ||
                (int) $notificationId <= 0
            ) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid notification ID.',
                ], 400);
            }

            $notificationId = (int) $notificationId;

          
            $notificationResponse = $this->supabase()->get(
                'notifications',
                [
                    'select' => 'id,related_id',
                    'id' => 'eq.' . $notificationId,
                    'limit' => 1,
                ]
            );

            if (!$notificationResponse->successful()) {
                Log::error('Notification lookup failed.', [
                    'notification_id' => $notificationId,
                    'body' => $notificationResponse->body(),
                ]);

                return response()->json([
                    'success' => false,
                    'message' => 'Unable to retrieve notification.',
                ], 500);
            }

            $notification = $notificationResponse->json()[0] ?? null;

            if (!$notification) {
                return response()->json([
                    'success' => false,
                    'message' => 'Notification not found.',
                ], 404);
            }

   
            if ($notification['related_id'] === null) {
                $ownershipResponse = $this->supabase()->get(
                    'notification_status',
                    [
                        'select' => 'id',
                        'user_id' => 'eq.' . $accountId,
                        'notification_id' => 'eq.' . $notificationId,
                        'limit' => 1,
                    ]
                );

                if (!$ownershipResponse->successful()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Unable to verify notification ownership.',
                    ], 500);
                }

                if (empty($ownershipResponse->json())) {
                    return response()->json([
                        'success' => false,
                        'message' => 'You are not authorized to read this notification.',
                    ], 403);
                }
            } else {
                if (
                    !in_array($userRole, [0, 1, 2], true) ||
                    $ngoId === null ||
                    (string) $notification['related_id'] !== (string) $ngoId
                ) {
                    return response()->json([
                        'success' => false,
                        'message' => 'You are not authorized to read this notification.',
                    ], 403);
                }
            }

  
            $statusLookup = $this->supabase()->get(
                'notification_status',
                [
                    'select' => 'id,is_read,updated_at',
                    'user_id' => 'eq.' . $accountId,
                    'notification_id' => 'eq.' . $notificationId,
                    'limit' => 1,
                ]
            );

            if (!$statusLookup->successful()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unable to retrieve notification status.',
                ], 500);
            }

            $existingStatus = $statusLookup->json()[0] ?? null;


            if (
                $existingStatus &&
                (int) $existingStatus['is_read'] === 2
            ) {
                return response()->json([
                    'success' => false,
                    'message' => 'This notification has already been deleted.',
                ], 410);
            }


            if (
                $existingStatus &&
                (int) $existingStatus['is_read'] === 1
            ) {
                return response()->json([
                    'success' => true,
                    'notification_id' => $notificationId,
                    'is_read' => 1,
                ]);
            }

            $now = now('UTC')->toIso8601String();

            if ($existingStatus) {
                $statusResponse = $this->supabase()->patch(
                    'notification_status?id=eq.' .
                        (int) $existingStatus['id'],
                    [
                        'is_read' => 1,
                        'updated_at' => $now,
                    ]
                );
            } else {
                $statusResponse = $this->supabase()->post(
                    'notification_status',
                    [[
                        'user_id' => $accountId,
                        'notification_id' => $notificationId,
                        'is_read' => 1,
                        'updated_at' => $now,
                    ]]
                );
            }

            if (!$statusResponse->successful()) {
                Log::error('Notification read-status update failed.', [
                    'notification_id' => $notificationId,
                    'user_id' => $accountId,
                    'body' => $statusResponse->body(),
                ]);

                return response()->json([
                    'success' => false,
                    'message' => 'Unable to save notification read status.',
                ], 500);
            }

            return response()->json([
                'success' => true,
                'notification_id' => $notificationId,
                'is_read' => 1,
            ]);

        } catch (\Throwable $e) {
            Log::error('Mark notification as read failed.', [
                'notification_id' => $notificationId ?? null,
                'message' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Unable to mark notification as read.',
            ], 500);
        }
    }

    public function deleteNotification($notificationId)
    {
        try {
            $accountData = $this->getLoggedInAccount();

            if (isset($accountData['error'])) {
                return $accountData['error'];
            }

            $accountId = (int) $accountData['user_id'];
            $role = (int) $accountData['role'];
            $ngoId = $accountData['ngo_id'];

            if (
                !is_numeric($notificationId) ||
                (int) $notificationId <= 0
            ) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid notification ID.',
                ], 400);
            }

            $notificationId = (int) $notificationId;

    
            $notificationResponse = $this->supabase()->get(
                'notifications',
                [
                    'select' => 'id,related_id',
                    'id' => 'eq.' . $notificationId,
                    'limit' => 1,
                ]
            );

            if (!$notificationResponse->successful()) {
                Log::error('Notification lookup failed during deletion.', [
                    'notification_id' => $notificationId,
                    'body' => $notificationResponse->body(),
                ]);

                return response()->json([
                    'success' => false,
                    'message' => 'Unable to retrieve notification.',
                ], 500);
            }

            $notification = $notificationResponse->json()[0] ?? null;

            if (!$notification) {
                return response()->json([
                    'success' => false,
                    'message' => 'Notification not found.',
                ], 404);
            }


            if ($notification['related_id'] === null) {
                
                $ownershipResponse = $this->supabase()->get(
                    'notification_status',
                    [
                        'select' => 'id',
                        'user_id' => 'eq.' . $accountId,
                        'notification_id' => 'eq.' . $notificationId,
                        'limit' => 1,
                    ]
                );

                if (!$ownershipResponse->successful()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Unable to verify notification ownership.',
                    ], 500);
                }

                if (empty($ownershipResponse->json())) {
                    return response()->json([
                        'success' => false,
                        'message' => 'You are not authorized to delete this notification.',
                    ], 403);
                }
            } else {
               
                if (
                    !in_array($role, [0, 1, 2], true) ||
                    $ngoId === null ||
                    (string) $notification['related_id'] !== (string) $ngoId
                ) {
                    return response()->json([
                        'success' => false,
                        'message' => 'You are not authorized to delete this notification.',
                    ], 403);
                }
            }

         
            $statusLookup = $this->supabase()->get(
                'notification_status',
                [
                    'select' => 'id,is_read,updated_at',
                    'user_id' => 'eq.' . $accountId,
                    'notification_id' => 'eq.' . $notificationId,
                    'limit' => 1,
                ]
            );

            if (!$statusLookup->successful()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unable to retrieve notification status.',
                ], 500);
            }

            $existingStatus = $statusLookup->json()[0] ?? null;

            if (
                $existingStatus &&
                (int) $existingStatus['is_read'] === 2
            ) {
                return response()->json([
                    'success' => true,
                    'message' => 'Notification is already deleted for your account.',
                    'notification_id' => $notificationId,
                ]);
            }

            $now = now('UTC')->toIso8601String();

      
            if ($existingStatus) {
                $statusResponse = $this->supabase()->patch(
                    'notification_status?id=eq.' .
                        (int) $existingStatus['id'],
                    [
                        'is_read' => 2,
                        'updated_at' => $now,
                    ]
                );
            } else {

                $statusResponse = $this->supabase()->post(
                    'notification_status',
                    [[
                        'user_id' => $accountId,
                        'notification_id' => $notificationId,
                        'is_read' => 2,
                        'updated_at' => $now,
                    ]]
                );
            }

            if (!$statusResponse->successful()) {
                Log::error('Failed to mark notification as deleted.', [
                    'notification_id' => $notificationId,
                    'user_id' => $accountId,
                    'status_code' => $statusResponse->status(),
                    'body' => $statusResponse->body(),
                ]);

                return response()->json([
                    'success' => false,
                    'message' => 'Unable to delete notification for your account.',
                ], 500);
            }

            return response()->json([
                'success' => true,
                'message' => 'Notification deleted from your notifications.',
                'notification_id' => $notificationId,
            ]);

        } catch (\Throwable $e) {
            Log::error('Notification deletion failed.', [
                'notification_id' => $notificationId ?? null,
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'An unexpected error occurred while deleting the notification.',
            ], 500);
        }
    }
}
