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
            env('SUPABASE_URL') . '/rest/v1/'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | MARK NOTIFICATION AS READ
    |--------------------------------------------------------------------------
    */

    public function markAsRead($notificationId)
    {
        /*
        |--------------------------------------------------------------------------
        | GET LOGGED-IN USER
        |--------------------------------------------------------------------------
        */

        $userId = session('user_id');

        if (!$userId) {

            return response()->json([
                'success' => false,
                'message' => 'User is not logged in.',
            ], 401);
        }


        /*
        |--------------------------------------------------------------------------
        | GET USER ACCOUNT
        |--------------------------------------------------------------------------
        */

        $userResponse = $this->supabase()->get(
            'accounts',
            [
                'select' => 'id,roles,ngo_id',
                'id' => 'eq.' . (int) $userId,
            ]
        );


        if (!$userResponse->successful()) {

            Log::error(
                'Failed to retrieve account for notification.',
                [
                    'user_id' => $userId,
                    'status' => $userResponse->status(),
                    'body' => $userResponse->body(),
                ]
            );

            return response()->json([
                'success' => false,
                'message' => 'Unable to verify user account.',
            ], 500);
        }


        $accounts = $userResponse->json();

        $user = collect($accounts)->first();


        if (!$user) {

            return response()->json([
                'success' => false,
                'message' => 'User account not found.',
            ], 404);
        }


        $userRole = (int) ($user['roles'] ?? 3);
        $ngoId = $user['ngo_id'] ?? null;


        /*
        |--------------------------------------------------------------------------
        | GET NOTIFICATION
        |--------------------------------------------------------------------------
        */

        $notificationResponse = $this->supabase()->get(
            'notifications',
            [
                'select' => '*',
                'id' => 'eq.' . (int) $notificationId,
            ]
        );


        if (!$notificationResponse->successful()) {

            Log::error(
                'Failed to retrieve notification.',
                [
                    'notification_id' => $notificationId,
                    'status' => $notificationResponse->status(),
                    'body' => $notificationResponse->body(),
                ]
            );

            return response()->json([
                'success' => false,
                'message' => 'Unable to retrieve notification.',
            ], 500);
        }


        $notificationData = $notificationResponse->json();

        $notification = collect($notificationData)->first();


        if (!$notification) {

            return response()->json([
                'success' => false,
                'message' => 'Notification not found.',
            ], 404);
        }


        /*
        |--------------------------------------------------------------------------
        | NOTIFICATION INFORMATION
        |--------------------------------------------------------------------------
        */

        $notificationUserId =
            $notification['user_id'] ?? null;

        $notificationNgoId =
            $notification['related_id'] ?? null;


        /*
        |--------------------------------------------------------------------------
        | CHECK PERSONAL NOTIFICATION
        |--------------------------------------------------------------------------
        */

        $isPersonalNotification =
            !is_null($notificationUserId)
            &&
            (int) $notificationUserId === (int) $userId;


        /*
        |--------------------------------------------------------------------------
        | CHECK NGO-WIDE NOTIFICATION
        |--------------------------------------------------------------------------
        */

        $isNgoNotification =
            is_null($notificationUserId)
            &&
            !is_null($ngoId)
            &&
            !is_null($notificationNgoId)
            &&
            (int) $notificationNgoId === (int) $ngoId
            &&
            in_array(
                $userRole,
                [0, 1, 2],
                true
            );


        /*
        |--------------------------------------------------------------------------
        | LOG ACCESS CHECK
        |--------------------------------------------------------------------------
        */

        Log::info(
            'NOTIFICATION ACCESS CHECK',
            [
                'notification_id' => $notificationId,
                'logged_in_user_id' => $userId,
                'user_role' => $userRole,
                'user_ngo_id' => $ngoId,
                'notification_user_id' => $notificationUserId,
                'notification_ngo_id' => $notificationNgoId,
                'is_personal' => $isPersonalNotification,
                'is_ngo_notification' => $isNgoNotification,
            ]
        );


        /*
        |--------------------------------------------------------------------------
        | DENY UNAUTHORIZED ACCESS
        |--------------------------------------------------------------------------
        */

        if (
            !$isPersonalNotification
            &&
            !$isNgoNotification
        ) {

            return response()->json([
                'success' => false,
                'message' => 'You are not authorized to modify this notification.',
            ], 403);
        }


        /*
        |--------------------------------------------------------------------------
        | NGO-WIDE NOTIFICATION
        |--------------------------------------------------------------------------
        |
        | IMPORTANT:
        |
        | NGO-wide notifications have:
        |
        |     user_id = NULL
        | |   related_id = NGO ID
        |
        | The "is_read" column belongs to the notification itself.
        |
        | Therefore, setting is_read = 1 here would mark the notification
        | as read for EVERY user in the NGO.
        |
        | We do NOT globally mark NGO-wide notifications as read.
        |
        */

        if ($isNgoNotification) {

            Log::info(
                'NGO-WIDE NOTIFICATION OPENED',
                [
                    'notification_id' => $notificationId,
                    'user_id' => $userId,
                    'ngo_id' => $ngoId,
                    'message' => 'NGO-wide notification was opened. Global is_read was not changed.',
                ]
            );

            return response()->json([
                'success' => true,
                'message' => 'NGO notification opened.',
                'notification_type' => 'ngo',
            ]);
        }


        /*
        |--------------------------------------------------------------------------
        | PERSONAL NOTIFICATION
        |--------------------------------------------------------------------------
        |
        | Personal notifications belong to one specific user, so it is safe
        | to mark them as read.
        |
        */

        if ($isPersonalNotification) {

            $updateResponse = $this->supabase()
                ->withHeaders([
                    'Prefer' => 'return=representation',
                ])
                ->patch(
                    'notifications?id=eq.' . (int) $notificationId,
                    [
                        'is_read' => 1,
                        'updated_at' => now()->toDateTimeString(),
                    ]
                );


            /*
            |--------------------------------------------------------------------------
            | CHECK UPDATE RESULT
            |--------------------------------------------------------------------------
            */

            if (!$updateResponse->successful()) {

                Log::error(
                    'Failed to mark personal notification as read.',
                    [
                        'notification_id' => $notificationId,
                        'user_id' => $userId,
                        'ngo_id' => $ngoId,
                        'status' => $updateResponse->status(),
                        'body' => $updateResponse->body(),
                    ]
                );

                return response()->json([
                    'success' => false,
                    'message' => 'Failed to mark notification as read.',
                ], 500);
            }


            Log::info(
                'PERSONAL NOTIFICATION MARKED AS READ',
                [
                    'notification_id' => $notificationId,
                    'user_id' => $userId,
                ]
            );


            return response()->json([
                'success' => true,
                'message' => 'Notification marked as read.',
                'notification_type' => 'personal',
            ]);
        }


        /*
        |--------------------------------------------------------------------------
        | FALLBACK
        |--------------------------------------------------------------------------
        */

        return response()->json([
            'success' => false,
            'message' => 'Unable to determine notification type.',
        ], 400);
    }
}