<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ngo_controller extends Controller
{
    public function ngosPage()
    {
        $response = Http::withHeaders([
            'apikey' => env('SUPABASE_SERVICE_KEY'),
            'Authorization' => 'Bearer ' . env('SUPABASE_SERVICE_KEY'),
        ])->get(env('SUPABASE_URL') . '/rest/v1/ngo_profile?select=*');

        $ngos = $response->json();

        return view('ngos', compact('ngos'));
    }


public function profile()
{
    $headers = [
        'apikey' => env('SUPABASE_SERVICE_KEY'),
        'Authorization' => 'Bearer ' . env('SUPABASE_SERVICE_KEY'),
        'Content-Type' => 'application/json',
    ];

    $ngoId = session('ngo_id');

    if (!$ngoId) {
        abort(403, 'No NGO associated with this account.');
    }

    // 1. Get NGO profile
    $response = Http::withHeaders($headers)
        ->get(
            env('SUPABASE_URL') .
            '/rest/v1/ngo_profile?select=*&id=eq.' . $ngoId
        );

    $data = $response->json();

    $ngo = isset($data[0])
        ? (object) $data[0]
        : null;

    if ($ngo) {

        // 2. NGO Logo
        $ngo->logo_url = null;
        $ngo->email = session('user_email');

        if (!empty($ngo->logo)) {

            $mediaResponse = Http::withHeaders($headers)->get(
                env('SUPABASE_URL') .
                '/rest/v1/media_table?select=path' .
                '&id=eq.' . $ngo->logo .
                '&type=eq.NGO_LOGO'
            );

            $mediaData = $mediaResponse->json();

            $ngo->logo_url = $mediaData[0]['path'] ?? null;
        }


        // 3. Get ALL bank accounts
        $bankResponse = Http::withHeaders($headers)->get(
            env('SUPABASE_URL') .
            '/rest/v1/bank_accounts?select=*&ngo_id=eq.' . $ngo->id
        );

        $bankData = $bankResponse->json();

        $ngo->bank_accounts = [];


        // 4. Get QR codes for bank accounts
        $accountIds = array_column($bankData, 'id');

        if (!empty($accountIds)) {

            $ids = implode(',', $accountIds);

            $mediaResponse = Http::withHeaders($headers)->get(
                env('SUPABASE_URL') .
                '/rest/v1/media_table' .
                '?select=key_id,path,type' .
                '&key_id=in.(' . $ids . ')' .
                '&type=eq.BANK_ACC'
            );

            $mediaData = $mediaResponse->json();

            // Map:
            // bank_account_id => QR code path
            $qrCodes = [];

            foreach ($mediaData as $media) {
                $qrCodes[$media['key_id']] = $media['path'];
            }


            // 5. Combine account + QR code
            foreach ($bankData as $account) {

                $account['qr_code'] =
                    $qrCodes[$account['id']] ?? null;

                $ngo->bank_accounts[] = $account;
            }
        }
    }

    return view('ngo_management', compact('ngo'));
}


public function update(Request $request)
{
    $headers = [
        'apikey' => env('SUPABASE_SERVICE_KEY'),
        'Authorization' => 'Bearer ' . env('SUPABASE_SERVICE_KEY'),
        'Content-Type' => 'application/json',
    ];

    $ngoId = session('ngo_id');

    if (!$ngoId) {
        abort(403, 'No NGO associated with this account.');
    }

    // Validate organization details
    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'description' => 'required|string',
        'contact_number' => 'required|string|max:50',
        'address' => 'required|string|max:500',
    ]);

    // Update NGO profile
    $response = Http::withHeaders($headers)->patch(
        env('SUPABASE_URL') .
        '/rest/v1/ngo_profile?id=eq.' . $ngoId,
        [
            'name' => $validated['name'],
            'description' => $validated['description'],
            'contact_number' => $validated['contact_number'],
            'address' => $validated['address'],
        ]
    );

    if (!$response->successful()) {
        return back()->with(
            'error',
            'Failed to update organization details.'
        );
    }

    return redirect('/sm-ngos')
        ->with('success', 'Organization details updated successfully.');
}

public function addAccount(Request $request)
{
    $supabaseUrl = env('SUPABASE_URL');
    $serviceKey = env('SUPABASE_SERVICE_KEY');

    $headers = [
        'apikey' => $serviceKey,
        'Authorization' => 'Bearer ' . $serviceKey,
        'Content-Type' => 'application/json',
        'Prefer' => 'return=representation',
    ];

    $ngoId = session('ngo_id');

    if (!$ngoId) {
        abort(403, 'No NGO associated with this account.');
    }

    /*
    |--------------------------------------------------------------------------
    | 1. VALIDATE INPUT
    |--------------------------------------------------------------------------
    */

    $validated = $request->validate([
        'type' => 'required|string|max:50',
        'account_name' => 'required|string|max:255',
        'account_number' => 'required|string|max:100',
        'qr_code' => 'required|image|mimes:jpg,jpeg,png|max:5120',
    ]);

    $type = strtoupper($validated['type']);

    /*
    |--------------------------------------------------------------------------
    | 2. ADD BANK ACCOUNT
    |--------------------------------------------------------------------------
    */

    $accountResponse = Http::withHeaders($headers)->post(
        $supabaseUrl . '/rest/v1/bank_accounts',
        [
            'ngo_id' => $ngoId,
            'account_name' => $validated['account_name'],
            'account_number' => $validated['account_number'],
            'type' => $type,
        ]
    );

    if (!$accountResponse->successful()) {

        \Log::error('Bank account insert failed', [
            'status' => $accountResponse->status(),
            'response' => $accountResponse->body(),
        ]);

        return back()->with(
            'error',
            'Failed to add payment account.'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | 3. GET NEW BANK ACCOUNT ID
    |--------------------------------------------------------------------------
    */

    $accountData = $accountResponse->json();

    if (empty($accountData) || !isset($accountData[0]['id'])) {

        \Log::error('Bank account ID not returned', [
            'response' => $accountData,
        ]);

        return back()->with(
            'error',
            'Bank account was created but ID could not be retrieved.'
        );
    }

    $bankAccountId = $accountData[0]['id'];

    /*
    |--------------------------------------------------------------------------
    | 4. UPLOAD QR IMAGE TO SUPABASE STORAGE
    |--------------------------------------------------------------------------
    */

    $file = $request->file('qr_code');

    // Create unique filename
    $fileName = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();

    // Storage path inside qrcodes bucket
    $storagePath = 'bank_accounts/' . $ngoId . '/' . $fileName;

    $uploadResponse = Http::withHeaders([
        'apikey' => $serviceKey,
        'Authorization' => 'Bearer ' . $serviceKey,
        'Content-Type' => $file->getMimeType(),
    ])->withBody(
        file_get_contents($file->getRealPath()),
        $file->getMimeType()
    )->post(
        $supabaseUrl . '/storage/v1/object/qrcodes/' . $storagePath
    );

    if (!$uploadResponse->successful()) {

        \Log::error('QR image upload failed', [
            'status' => $uploadResponse->status(),
            'response' => $uploadResponse->body(),
        ]);

        return back()->with(
            'error',
            'Bank account was created but QR image upload failed.'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | 5. CREATE PUBLIC QR IMAGE URL
    |--------------------------------------------------------------------------
    */

    $qrPath = $supabaseUrl .
        '/storage/v1/object/public/qrcodes/' .
        $storagePath;

    /*
    |--------------------------------------------------------------------------
    | 6. SAVE QR PATH TO MEDIA TABLE
    |--------------------------------------------------------------------------
    */

    $mediaResponse = Http::withHeaders($headers)->post(
        $supabaseUrl . '/rest/v1/media_table',
        [
            'key_id' => $bankAccountId,
            'type' => 'BANK_ACC',
            'path' => $qrPath,
        ]
    );

    if (!$mediaResponse->successful()) {

        \Log::error('media_table insert failed', [
            'bank_account_id' => $bankAccountId,
            'qr_path' => $qrPath,
            'status' => $mediaResponse->status(),
            'response' => $mediaResponse->body(),
        ]);

        return back()->with(
            'error',
            'Bank account was created but QR information failed to save.'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | 7. SUCCESS
    |--------------------------------------------------------------------------
    */

    return back()->with(
        'success',
        'Payment account and QR code added successfully.'
    );
}
}