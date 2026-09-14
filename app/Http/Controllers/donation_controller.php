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
}