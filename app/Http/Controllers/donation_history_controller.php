<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class donation_history_controller extends Controller
{
    public function index(Request $request)
    {
        // =========================================================
        // SUPABASE HEADERS
        // =========================================================

        $headers = [
            'apikey' => env('SUPABASE_SERVICE_KEY'),
            'Authorization' => 'Bearer ' . env('SUPABASE_SERVICE_KEY'),
        ];

        $baseUrl = env('SUPABASE_URL');


        // =========================================================
        // GET LOGGED-IN ACCOUNT ID
        // =========================================================

        $accountId = session('user_id');


        if (!$accountId) {

            return view('Volunteers.donation_history', [
                'donations' => [],
                'error' => 'Account session not found.'
            ]);
        }


        // =========================================================
        // GET ONLY DONATIONS MADE BY LOGGED-IN USER
        // =========================================================

        $donationResponse = Http::withHeaders($headers)
            ->get($baseUrl . '/rest/v1/donations_table', [

                'select' => '*',

                // IMPORTANT:
                // Only get donations belonging to
                // the currently logged-in account.
                'account_id' => 'eq.' . $accountId,

                // Newest donations first
                'order' => 'date.desc'
            ]);


        // =========================================================
        // CHECK DONATION REQUEST
        // =========================================================

        if (!$donationResponse->successful()) {

            return view('Volunteers.donation_history', [
                'donations' => [],
                'error' => 'Unable to retrieve donation history.'
            ]);
        }


        // =========================================================
        // CONVERT RESPONSE TO ARRAY
        // =========================================================

        $donations = $donationResponse->json();

        if (!is_array($donations)) {
            $donations = [];
        }


        // =========================================================
        // GET NGO INFORMATION
        // =========================================================

        $ngoResponse = Http::withHeaders($headers)
            ->get($baseUrl . '/rest/v1/ngo_profile', [
                'select' => '*'
            ]);

        $ngoMap = [];


        // =========================================================
        // CREATE NGO ID => NGO NAME MAP
        // =========================================================

        if ($ngoResponse->successful()) {

            $ngos = $ngoResponse->json();

            if (is_array($ngos)) {

                foreach ($ngos as $ngo) {

                    $ngoId = $ngo['id'] ?? null;

                    if (!$ngoId) {
                        continue;
                    }


                    // Try possible NGO name columns
                    $ngoName =
                        $ngo['ngo_name']
                        ?? $ngo['name']
                        ?? $ngo['organization_name']
                        ?? $ngo['org_name']
                        ?? 'Unknown NGO';


                    $ngoMap[$ngoId] = $ngoName;
                }
            }
        }


        // =========================================================
        // PREPARE DONATION DATA
        // =========================================================

        foreach ($donations as &$donation) {

            // -----------------------------------------------------
            // NGO NAME
            // -----------------------------------------------------

            $ngoId = $donation['ngo_id'] ?? null;

            $donation['ngo_name'] =
                $ngoMap[$ngoId] ?? 'Unknown NGO';


            // -----------------------------------------------------
            // TYPE
            // -----------------------------------------------------

            $donation['type'] =
                strtoupper(
                    trim(
                        $donation['type'] ?? 'UNKNOWN'
                    )
                );


            // -----------------------------------------------------
            // DESCRIPTION
            // -----------------------------------------------------

            $donation['description'] =
                $donation['description'] ?? 'N/A';


            // -----------------------------------------------------
            // AMOUNT
            // -----------------------------------------------------

            $donation['amount'] =
                $donation['amount'] ?? null;


            // -----------------------------------------------------
            // UNIT
            // -----------------------------------------------------

            $donation['unit'] =
                $donation['unit'] ?? null;


            // -----------------------------------------------------
            // REFERENCE NUMBER
            // -----------------------------------------------------

            $donation['reference_no'] =
                $donation['reference_no'] ?? null;


            // -----------------------------------------------------
            // SOURCE
            // -----------------------------------------------------

            $donation['source'] =
                $donation['source'] ?? 'N/A';


            // -----------------------------------------------------
            // DATE
            // -----------------------------------------------------

            $donation['date'] =
                $donation['date'] ?? null;


            // -----------------------------------------------------
            // STATUS
            // -----------------------------------------------------

            $donation['status'] =
                strtoupper(
                    trim(
                        $donation['status'] ?? 'PENDING'
                    )
                );
        }

        unset($donation);


        // =========================================================
        // RETURN DONATION HISTORY VIEW
        // =========================================================

        return view('Volunteers.donation_history', [
            'donations' => $donations,
            'error' => null
        ]);
    }
}