<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class donation_history_controller extends Controller
{
    public function index(Request $request)
    {
       
        $headers = [
            'apikey' => env('SUPABASE_SERVICE_KEY'),
            'Authorization' => 'Bearer ' . env('SUPABASE_SERVICE_KEY'),
        ];

        $baseUrl = env('SUPABASE_URL');

        $accountId = session('user_id');


        if (!$accountId) {

            return view('Volunteers.donation_history', [
                'donations' => [],
                'error' => 'Account session not found.'
            ]);
        }


        $donationResponse = Http::withHeaders($headers)
            ->get($baseUrl . '/rest/v1/donations_table', [

                'select' => '*',

             
                'account_id' => 'eq.' . $accountId,

                'order' => 'date.desc'
            ]);

        if (!$donationResponse->successful()) {

            return view('Volunteers.donation_history', [
                'donations' => [],
                'error' => 'Unable to retrieve donation history.'
            ]);
        }

        $donations = $donationResponse->json();

        if (!is_array($donations)) {
            $donations = [];
        }

        $ngoResponse = Http::withHeaders($headers)
            ->get($baseUrl . '/rest/v1/ngo_profile', [
                'select' => '*'
            ]);

        $ngoMap = [];


        if ($ngoResponse->successful()) {

            $ngos = $ngoResponse->json();

            if (is_array($ngos)) {

                foreach ($ngos as $ngo) {

                    $ngoId = $ngo['id'] ?? null;

                    if (!$ngoId) {
                        continue;
                    }


                 
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


        foreach ($donations as &$donation) {

            $ngoId = $donation['ngo_id'] ?? null;

            $donation['ngo_name'] =
                $ngoMap[$ngoId] ?? 'Unknown NGO';

            $donation['type'] =
                strtoupper(
                    trim(
                        $donation['type'] ?? 'UNKNOWN'
                    )
                );


            $donation['description'] =
                $donation['description'] ?? 'N/A';


            $donation['amount'] =
                $donation['amount'] ?? null;


            $donation['unit'] =
                $donation['unit'] ?? null;


            $donation['reference_no'] =
                $donation['reference_no'] ?? null;


            $donation['source'] =
                $donation['source'] ?? 'N/A';

            $donation['date'] =
                $donation['date'] ?? null;

            $donation['status'] =
                strtoupper(
                    trim(
                        $donation['status'] ?? 'PENDING'
                    )
                );
        }

        unset($donation);

        return view('Volunteers.donation_history', [
            'donations' => $donations,
            'error' => null
        ]);
    }
}