<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class notification_controller extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | SUPABASE CONNECTION
    |--------------------------------------------------------------------------
    */

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

    /*
    |--------------------------------------------------------------------------
    | GET LOGGED-IN ACCOUNT
    |--------------------------------------------------------------------------
    */

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

    /*
    |--------------------------------------------------------------------------
    | GET NOTIFICATIONS FOR THE HEADER
    |--------------------------------------------------------------------------
    |
    | NGO staff (roles 0, 1, 2):
    | - Can see notifications for their NGO.
    | - Can see personal notifications belonging to their account.
    |
    | Personal users (role 3):
    | - Can only see notifications belonging to their account.
    |
    | Read status is retrieved from notification_status for this account.
    | Missing status rows are treated as unread.
    |
    */

    


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

        // 1. Fetch this account's notification statuses.
        $statusResponse = $this->supabase()->get('notification_status', [
            'select' => 'notification_id,is_read',
            'user_id' => 'eq.' . $userId,
        ]);

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

        foreach ($statuses as $status) {
            $notificationId = (int) $status['notification_id'];

            $readStatuses[(string) $notificationId] =
                filter_var(
                    $status['is_read'] ?? false,
                    FILTER_VALIDATE_BOOLEAN
                );

            $personalNotificationIds[] = $notificationId;
        }

        $notifications = [];

        // 2. Fetch NGO-wide notifications for NGO staff.
        if (in_array($role, [0, 1, 2], true) && $ngoId !== null) {
            $ngoResponse = $this->supabase()->get('notifications', [
                'select' => 'id,title,message,type,reference_id,created_at,related_id',
                'related_id' => 'eq.' . (int) $ngoId,
                'order' => 'created_at.desc,id.desc',
                'limit' => 100,
            ]);

            if (!$ngoResponse->successful()) {
                Log::error('Failed to fetch NGO notifications.', [
                    'user_id' => $userId,
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

        // 3. Fetch personal notifications assigned to this account.
        // The account must have a notification_status row for the notification.
        $personalNotificationIds = array_values(
            array_unique($personalNotificationIds)
        );

        if (!empty($personalNotificationIds)) {
            $ids = implode(',', $personalNotificationIds);

            $personalResponse = $this->supabase()->get('notifications', [
                'select' => 'id,title,message,type,reference_id,created_at,related_id',
                'id' => 'in.(' . $ids . ')',
                'related_id' => 'is.null',
                'order' => 'created_at.desc,id.desc',
                'limit' => 100,
            ]);

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

            $personalNotifications = $personalResponse->json() ?? [];

            $notifications = array_merge(
                $notifications,
                $personalNotifications
            );
        }

        // 4. Remove duplicates and attach the current account's read status.
        $uniqueNotifications = [];

        foreach ($notifications as $notification) {
            $id = (string) $notification['id'];

            $notification['is_read'] =
                $readStatuses[$id] ?? false;

            $uniqueNotifications[$id] = $notification;
        }

        $notifications = array_values($uniqueNotifications);

        // 5. Sort newest first.
        usort($notifications, function ($a, $b) {
            $dateA = strtotime($a['created_at'] ?? '') ?: 0;
            $dateB = strtotime($b['created_at'] ?? '') ?: 0;

            if ($dateA === $dateB) {
                return (int) $b['id'] <=> (int) $a['id'];
            }

            return $dateB <=> $dateA;
        });

        // 6. Calculate unread count.
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




    /*
    |--------------------------------------------------------------------------
    | MARK NOTIFICATION AS READ
    |--------------------------------------------------------------------------
    |
    | Save read status in notification_status for the current account only.
    | Do not update notifications.is_read, because that could affect other
    | accounts that share the same NGO-wide notification.
    |
    */

    


public function markAsRead($notificationId)
{
    $accountData = $this->getLoggedInAccount();

    if (isset($accountData['error'])) {
        return $accountData['error'];
    }

    $accountId = (int) $accountData['user_id'];
    $userRole = (int) $accountData['role'];
    $ngoId = $accountData['ngo_id'];
    $notificationId = (int) $notificationId;

    // Retrieve the notification.
    $notificationResponse = $this->supabase()->get('notifications', [
        'select' => 'id,related_id',
        'id' => 'eq.' . $notificationId,
        'limit' => 1,
    ]);

    if (!$notificationResponse->successful()) {
        Log::error('Notification lookup failed.', [
            'notification_id' => $notificationId,
            'response' => $notificationResponse->body(),
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

    // Verify access based on notification type.
    if ($notification['related_id'] === null) {
        // Personal notification: only the assigned account may access it.
        $statusLookup = $this->supabase()->get('notification_status', [
            'select' => 'id',
            'user_id' => 'eq.' . $accountId,
            'notification_id' => 'eq.' . $notificationId,
            'limit' => 1,
        ]);

        if (!$statusLookup->successful()) {
            return response()->json([
                'success' => false,
                'message' => 'Unable to verify notification ownership.',
            ], 500);
        }

        if (empty($statusLookup->json())) {
            return response()->json([
                'success' => false,
                'message' => 'You are not authorized to read this notification.',
            ], 403);
        }
    } else {
        // NGO-wide notification: only authorized staff from that NGO.
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

    // Update only this account's read status.
    $statusLookup = $this->supabase()->get('notification_status', [
        'select' => 'id',
        'user_id' => 'eq.' . $accountId,
        'notification_id' => 'eq.' . $notificationId,
        'limit' => 1,
    ]);

    if (!$statusLookup->successful()) {
        return response()->json([
            'success' => false,
            'message' => 'Unable to retrieve notification status.',
        ], 500);
    }

    $existingStatus = $statusLookup->json()[0] ?? null;

    if ($existingStatus) {
        $statusResponse = $this->supabase()->patch(
            'notification_status?user_id=eq.' . $accountId
                . '&notification_id=eq.' . $notificationId,
            ['is_read' => 1]
        );
    } else {
        $statusResponse = $this->supabase()->post(
            'notification_status',
            [[
                'user_id' => $accountId,
                'notification_id' => $notificationId,
                'is_read' => 1,
            ]]
        );
    }

    if (!$statusResponse->successful()) {
        Log::error('Notification read-status update failed.', [
            'notification_id' => $notificationId,
            'user_id' => $accountId,
            'response' => $statusResponse->body(),
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
}

}