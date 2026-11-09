<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class donation_controller extends Controller
{
    // =========================================================
    // ADMIN / NGO SIDE - DONATION MANAGEMENT
    // =========================================================

    public function index()
    {
        $ngo_id = session('ngo_id');

        if (!$ngo_id) {
            abort(403, 'Unauthorized');
        }

        $supabaseUrl = env('SUPABASE_URL');
        $supabaseKey = env('SUPABASE_SERVICE_KEY');

        $headers = [
            'apikey' => $supabaseKey,
            'Authorization' => 'Bearer ' . $supabaseKey,
        ];

        // =====================================================
        // GET DONATIONS
        // =====================================================

        $donationResponse = Http::withHeaders($headers)
            ->get($supabaseUrl . '/rest/v1/donations_table', [
                'select' => '*',
                'ngo_id' => 'eq.' . $ngo_id,
                'order' => 'id.desc'
            ]);

        if (!$donationResponse->successful()) {

            Log::error('Fetch donations failed', [
                'status' => $donationResponse->status(),
                'body' => $donationResponse->body()
            ]);

            return view('donations', [
                'donations' => [],
                'total_donations' => 0
            ]);
        }

        $donationsRaw = collect($donationResponse->json());

        // =====================================================
        // GET ACCOUNT IDS
        // =====================================================

        $accountIds = $donationsRaw
            ->pluck('account_id')
            ->filter()
            ->unique()
            ->values()
            ->all();

        $accounts = collect();

        // =====================================================
        // GET ACCOUNT INFORMATION
        // =====================================================

        if (!empty($accountIds)) {

            $accountResponse = Http::withHeaders($headers)
                ->get($supabaseUrl . '/rest/v1/accounts', [
                    'select' => 'id,first_name,last_name',
                    'id' => 'in.(' . implode(',', $accountIds) . ')'
                ]);

            if ($accountResponse->successful()) {

                $accounts = collect($accountResponse->json())
                    ->keyBy('id');
            }
        }

        // =====================================================
        // ADD DONOR NAME
        // =====================================================

        $donations = $donationsRaw->map(function ($donation) use ($accounts) {

            $donation = (object) $donation;

            $account = $accounts->get($donation->account_id);

            if ($account) {

                $donation->donor_name = trim(
                    ($account['first_name'] ?? '') . ' ' .
                    ($account['last_name'] ?? '')
                );

            } else {

                $donation->donor_name = 'Unknown';
            }

            return $donation;
        });

        $total_donations = $donations->count();

        return view('donations', compact(
            'donations',
            'total_donations'
        ));
    }


    // =========================================================
    // VOLUNTEER SIDE - DONATION HISTORY
    // =========================================================

    public function history()
    {
        $userId = session('user_id');

        if (!$userId) {

            return redirect()->back()
                ->with('error', 'Unauthorized access');
        }

        $supabaseUrl = env('SUPABASE_URL');
        $supabaseKey = env('SUPABASE_SERVICE_KEY');

        $headers = [
            'apikey' => $supabaseKey,
            'Authorization' => 'Bearer ' . $supabaseKey,
        ];

        // =====================================================
        // GET USER DONATIONS
        // =====================================================

        $donationResponse = Http::withHeaders($headers)
            ->get($supabaseUrl . '/rest/v1/donations_table', [
                'select' => '*',
                'account_id' => 'eq.' . $userId,
                'order' => 'date.desc'
            ]);

        if (!$donationResponse->successful()) {

            Log::error('Fetch donation history failed', [
                'status' => $donationResponse->status(),
                'body' => $donationResponse->body()
            ]);
            

            return view('Volunteers.donationhistory', [
                'donations' => []
            ]);
        }

        $donationsRaw = collect($donationResponse->json());

        // =====================================================
        // GET NGO INFORMATION
        // =====================================================

        $ngoResponse = Http::withHeaders($headers)
            ->get($supabaseUrl . '/rest/v1/ngo_profile', [
                'select' => 'id,name'
            ]);

        $ngoMap = collect();

        if ($ngoResponse->successful()) {

            $ngoMap = collect($ngoResponse->json())
                ->keyBy('id');
        }

        // =====================================================
        // ADD NGO NAME
        // =====================================================

        $donations = $donationsRaw->map(function ($donation) use ($ngoMap) {

            $donation = (object) $donation;

            $ngo = $ngoMap->get($donation->ngo_id);

            $donation->name = $ngo['name'] ?? 'Unknown NGO';

            return $donation;
        });

        return view(
            'Volunteers.donationhistory',
            compact('donations')
        );
    }


    // =========================================================
    // STORE DONATION
    // =========================================================

    public function store(Request $request)
    {
        try {

            // =================================================
            // GET LOGGED-IN USER
            // =================================================

            $userId = session('user_id');

            if (!$userId) {

                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized.'
                ], 401);
            }


            // =================================================
            // SUPABASE SETTINGS
            // =================================================

            $supabaseUrl = env('SUPABASE_URL');
            $supabaseKey = env('SUPABASE_SERVICE_KEY');


            // =================================================
            // LOG EVERYTHING RECEIVED
            // =================================================

            Log::info('========================================');
            Log::info('DONATION SUBMISSION');
            Log::info('Request data:', $request->all());
            Log::info('Donation type received:', [
                'type' => $request->input('type')
            ]);
            Log::info('========================================');


            // =================================================
            // BASIC VALIDATION ONLY
            // =================================================
            //
            // IMPORTANT:
            // Do NOT use "in:" validation here.
            //
            // The frontend may send:
            //
            // monetary
            // Monetary
            // MONETARY
            // online_monetary
            // Online Monetary
            // ONLINE_MONETARY
            //
            // We normalize it below.
            // =================================================

            $validated = $request->validate([

                'ngo_id' => 'required|integer',

                'type' => 'required|string',

                'description' => 'nullable|string',

                'amount' => 'nullable|numeric',

                'unit' => 'nullable|string',

                'reference_no' => 'nullable|string',

                'date' => 'nullable|date',
            ]);


            // =================================================
            // NORMALIZE DONATION TYPE
            // =================================================

            $rawType = trim($validated['type']);

            $normalizedType = strtolower($rawType);

            /*
             * Convert spaces and hyphens to underscores.
             *
             * Examples:
             *
             * Online Monetary
             * Online-Monetary
             * ONLINE MONETARY
             *
             * all become:
             *
             * online_monetary
             */

            $normalizedType = str_replace(
                [' ', '-'],
                '_',
                $normalizedType
            );


            // =================================================
            // DONATION TYPE MAP
            // =================================================

            $typeMap = [

                'monetary' =>
                    'MONETARY',

                'online_monetary' =>
                    'ONLINE_MONETARY',

                'consumable' =>
                    'CONSUMABLE',

                'reusable' =>
                    'REUSABLE',
            ];


            // =================================================
            // CHECK TYPE
            // =================================================

            if (!isset($typeMap[$normalizedType])) {

                Log::error('Invalid donation type', [
                    'received' => $rawType,
                    'normalized' => $normalizedType,
                    'request' => $request->all()
                ]);

                return response()->json([

                    'success' => false,

                    'message' =>
                        'Invalid donation type received: ' .
                        $rawType

                ], 422);
            }


            // =================================================
            // FINAL DATABASE TYPE
            // =================================================

            $donationType =
                $typeMap[$normalizedType];


            // =================================================
            // GET DONOR ACCOUNT
            // =================================================

            $accountResponse = Http::withHeaders([

                'apikey' =>
                    $supabaseKey,

                'Authorization' =>
                    'Bearer ' . $supabaseKey,

            ])->get(
                $supabaseUrl . '/rest/v1/accounts',
                [
                    'select' =>
                        'id,first_name,last_name',

                    'id' =>
                        'eq.' . $userId,

                    'limit' =>
                        1
                ]
            );


            if (!$accountResponse->successful()) {

                Log::error(
                    'Fetch donor account failed',
                    [
                        'status' =>
                            $accountResponse->status(),

                        'body' =>
                            $accountResponse->body()
                    ]
                );

                return response()->json([

                    'success' => false,

                    'message' =>
                        'Unable to get donor information.'

                ], 500);
            }


            // =================================================
            // GET ACCOUNT DATA
            // =================================================

            $accountData = collect(
                $accountResponse->json()
            )->first();


            if (!$accountData) {

                return response()->json([

                    'success' => false,

                    'message' =>
                        'Donor account not found.'

                ], 404);
            }


            // =================================================
            // CREATE SOURCE / DONOR NAME
            // =================================================

            $source = trim(

                ($accountData['first_name'] ?? '') .
                ' ' .
                ($accountData['last_name'] ?? '')

            );


            if ($source === '') {

                $source = 'Unknown User';
            }


            // =================================================
            // PREPARE DONATION DATA
            // =================================================

            $donationData = [

                'ngo_id' =>
                    $validated['ngo_id'],

                'account_id' =>
                    $userId,

                'type' =>
                    $donationType,

                'description' =>
                    $validated['description'] ?? null,

                'amount' =>
                    $validated['amount'] ?? null,

                'unit' =>
                    $validated['unit'] ?? null,

                'source' =>
                    $source,

                'date' =>
                    $validated['date']
                    ?? now()->format('Y-m-d'),

                'status' =>
                    'Pending',
            ];


            // =================================================
            // REFERENCE NUMBER
            // =================================================
            //
            // Mainly used for ONLINE_MONETARY.
            //
            // Only add it when the frontend actually sends it.
            // =================================================

            if (
                isset($validated['reference_no']) &&
                trim($validated['reference_no']) !== ''
            ) {

                $donationData['reference_no'] =
                    trim($validated['reference_no']);
            }


            // =================================================
            // LOG FINAL DATA
            // =================================================

            Log::info(
                'Final donation data:',
                $donationData
            );


            // =================================================
            // INSERT INTO SUPABASE
            // =================================================

            $response = Http::withHeaders([

                'apikey' =>
                    $supabaseKey,

                'Authorization' =>
                    'Bearer ' . $supabaseKey,

                'Content-Type' =>
                    'application/json',

                'Prefer' =>
                    'return=representation'

            ])->post(

                $supabaseUrl .
                '/rest/v1/donations_table',

                $donationData
            );

            if (!$response->successful()) {

                Log::error(
                    'Donation insert failed',
                    [
                        'status' =>
                            $response->status(),

                        'body' =>
                            $response->body(),

                        'data' =>
                            $donationData
                    ]
                );

                return response()->json([

                    'success' => false,

                    'message' =>
                        'Failed to save donation: ' .
                        $response->body()

                ], 500);
            }


            // =================================================
            // GET SAVED DONATION
            // =================================================

            $savedDonation =
                $response->json();


            // =================================================
            // SUCCESS LOG
            // =================================================

            Log::info(
                'Donation created successfully',
                [
                    'data' =>
                        $savedDonation
                ]
            );


            // =================================================
            // RETURN SUCCESS
            // =================================================

            return response()->json([

                'success' => true,

                'message' =>
                    'Donation saved successfully!',

                'donation' =>
                    $savedDonation

            ]);


        } catch (ValidationException $e) {

            // =================================================
            // VALIDATION ERROR
            // =================================================

            Log::error(
                'Donation validation failed',
                [
                    'errors' =>
                        $e->errors(),

                    'request' =>
                        $request->all()
                ]
            );


            return response()->json([

                'success' => false,

                'message' =>
                    $e->validator
                        ->errors()
                        ->first(),

                'errors' =>
                    $e->validator->errors()

            ], 422);


        } catch (\Exception $e) {

            // =================================================
            // GENERAL ERROR
            // =================================================

            Log::error(
                'Donation error',
                [
                    'message' =>
                        $e->getMessage(),

                    'file' =>
                        $e->getFile(),

                    'line' =>
                        $e->getLine()
                ]
            );


            return response()->json([

                'success' => false,

                'message' =>
                    'Server error: ' .
                    $e->getMessage()

            ], 500);
        }
    }

    

public function updateStatus(Request $request, $id)
{
    $ngoId = session('ngo_id');

    if (empty($ngoId)) {
        return response()->json([
            'success' => false,
            'message' => 'Unauthorized. Please log in again.',
        ], 401);
    }

    $validated = $request->validate([
        'status' => 'required|in:Confirmed,Rejected',
    ]);

    $supabaseUrl = rtrim(env('SUPABASE_URL', ''), '/');
    $supabaseKey = env('SUPABASE_SERVICE_KEY');

    if (empty($supabaseUrl) || empty($supabaseKey)) {
        Log::error('Supabase configuration missing.');

        return response()->json([
            'success' => false,
            'message' => 'Supabase configuration is missing.',
        ], 500);
    }

    $headers = [
        'apikey' => $supabaseKey,
        'Authorization' => 'Bearer ' . $supabaseKey,
        'Accept' => 'application/json',
        'Prefer' => 'return=representation',
    ];

    try {
        // 1. Retrieve the donation belonging to this NGO.
        $response = Http::withHeaders($headers)
            ->get($supabaseUrl . '/rest/v1/donations_table', [
                'select' => 'id,ngo_id,account_id,type,amount,unit,description,status',
                'id' => 'eq.' . $id,
                'ngo_id' => 'eq.' . $ngoId,
                'limit' => 1,
            ]);

        if (!$response->successful()) {
            Log::error('Failed to retrieve donation.', [
                'donation_id' => $id,
                'response' => $response->body(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve the donation.',
            ], 500);
        }

        $donation = collect($response->json())->first();

        if (!$donation) {
            return response()->json([
                'success' => false,
                'message' => 'Donation not found or it does not belong to your NGO.',
            ], 404);
        }

        // Prevent duplicate notifications for an already-confirmed donation.
        $alreadyConfirmed = ($donation['status'] ?? '') === 'Confirmed';

        // 2. Update the donation status.
        $updateResponse = Http::withHeaders($headers)
            ->withQueryParameters([
                'id' => 'eq.' . $id,
                'ngo_id' => 'eq.' . $ngoId,
                'select' => 'id,ngo_id,account_id,type,amount,unit,description,status',
            ])
            ->patch(
                $supabaseUrl . '/rest/v1/donations_table',
                ['status' => $validated['status']]
            );

        if (!$updateResponse->successful()) {
            Log::error('Donation status update failed.', [
                'donation_id' => $id,
                'response' => $updateResponse->body(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to update donation status.',
            ], 500);
        }

        $updatedDonation = collect($updateResponse->json())->first();

        if (!$updatedDonation) {
            return response()->json([
                'success' => false,
                'message' => 'No donation was updated.',
            ], 404);
        }

        // 3. Only create a notification when the status becomes Confirmed.
        if ($validated['status'] !== 'Confirmed') {
            return response()->json([
                'success' => true,
                'notification_created' => false,
                'message' => 'Donation status updated successfully.',
                'donation' => $updatedDonation,
            ]);
        }

        if ($alreadyConfirmed) {
            return response()->json([
                'success' => true,
                'notification_created' => false,
                'message' => 'Donation is already confirmed. No duplicate notification was created.',
                'donation' => $updatedDonation,
            ]);
        }

        // IMPORTANT: The recipient is the donor, not the logged-in NGO user.
        $donorUserId = $updatedDonation['account_id'] ?? null;

        // If the donation has no donor account, skip both inserts.
        if (empty($donorUserId)) {
            Log::info('Donation confirmed without a donor account; notification skipped.', [
                'donation_id' => $id,
            ]);

            return response()->json([
                'success' => true,
                'notification_created' => false,
                'message' => 'Donation confirmed. No notification was created because the donor account is missing.',
                'donation' => $updatedDonation,
            ]);
        }

        // 4. Prepare the notification message.
        $description = trim($updatedDonation['description'] ?? '');
        $amount = $updatedDonation['amount'] ?? null;
        $unit = trim($updatedDonation['unit'] ?? '');

        $message = 'Your donation has been confirmed and received.';

        if ($description !== '') {
            $message .= ' Donation: ' . $description . '.';
        }

        if ($amount !== null) {
            $message .= ' Amount: ' . $amount;

            if ($unit !== '') {
                $message .= ' ' . $unit;
            }

            $message .= '.';
        }

        // 5. Insert the notification.
        $notificationResponse = Http::withHeaders($headers)
            ->post(
                $supabaseUrl . '/rest/v1/notifications',
                [
                    'title' => 'Donation Received',
                    'message' => $message,
                    'type' => 'Donation',
                    'reference_id' => $updatedDonation['id'],
                    'related_id' => null,
                    'created_at' => now()->toIso8601String(),
                ]
            );

        if (!$notificationResponse->successful()) {
            Log::error('Donation notification creation failed.', [
                'donation_id' => $id,
                'donor_user_id' => $donorUserId,
                'http_status' => $notificationResponse->status(),
                'response' => $notificationResponse->body(),
            ]);

            return response()->json([
                'success' => true,
                'notification_created' => false,
                'message' => 'Donation confirmed, but the notification could not be created. Check the Laravel log.',
                'donation' => $updatedDonation,
            ]);
        }

        $createdNotification = collect(
            $notificationResponse->json()
        )->first();

        $notificationId = $createdNotification['id'] ?? null;

        if (empty($notificationId)) {
            Log::error('Notification ID missing after insertion.', [
                'donation_id' => $id,
                'response' => $notificationResponse->body(),
            ]);

            return response()->json([
                'success' => true,
                'notification_created' => false,
                'message' => 'Donation confirmed, but the notification ID could not be retrieved.',
                'donation' => $updatedDonation,
            ]);
        }

        // 6. Save unread status for the donor only.
        $statusResponse = Http::withHeaders($headers)
            ->post(
                $supabaseUrl . '/rest/v1/notification_status',
                [
                    'user_id' => $donorUserId,
                    'notification_id' => $notificationId,
                    'is_read' => 0,
                ]
            );

        if (!$statusResponse->successful()) {
            Log::error('Donation notification status creation failed.', [
                'notification_id' => $notificationId,
                'donor_user_id' => $donorUserId,
                'http_status' => $statusResponse->status(),
                'response' => $statusResponse->body(),
            ]);

            return response()->json([
                'success' => true,
                'notification_created' => true,
                'notification_status_created' => false,
                'message' => 'Donation confirmed and notification created, but the donor unread status could not be saved.',
                'donation' => $updatedDonation,
            ]);
        }

        return response()->json([
            'success' => true,
            'notification_created' => true,
            'notification_status_created' => true,
            'message' => 'Donation confirmed.',
            'donation' => $updatedDonation,
        ]);

    } catch (\Throwable $e) {
        Log::error('Donation status update exception.', [
            'donation_id' => $id,
            'ngo_id' => $ngoId,
            'message' => $e->getMessage(),
            'file' => $e->getFile(),
            'line' => $e->getLine(),
        ]);

        return response()->json([
            'success' => false,
            'message' => 'A server error occurred while updating the donation.',
        ], 500);
    }
}

}