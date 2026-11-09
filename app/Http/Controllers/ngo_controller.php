<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ngo_controller extends Controller
{
    public function ngosPage()
    {
        $headers = [
            'apikey' => env('SUPABASE_SERVICE_KEY'),
            'Authorization' => 'Bearer ' . env('SUPABASE_SERVICE_KEY'),
            'Content-Type' => 'application/json',
        ];

        $response = Http::withHeaders($headers)->get(
            env('SUPABASE_URL') . '/rest/v1/ngo_profile?select=*'
        );

        $ngos = $response->json();

        if (!$response->successful() || empty($ngos) || !is_array($ngos)) {
            $ngos = [];
            return view('ngos', compact('ngos'));
        }

        $accountResponse = Http::withHeaders($headers)->get(
            env('SUPABASE_URL') . '/rest/v1/bank_accounts?select=*'
        );

        $bankAccounts = $accountResponse->successful()
            ? ($accountResponse->json() ?? [])
            : [];

        foreach ($ngos as &$ngo) {
            $ngo['bank_accounts'] = array_values(array_filter(
                $bankAccounts,
                function ($account) use ($ngo) {
                    return isset($account['ngo_id'], $ngo['id'])
                        && $account['ngo_id'] == $ngo['id'];
                }
            ));
        }
        unset($ngo);

        // Get the latest logo for each NGO from media_table.
        $logoMap = [];
        $ngoIds = array_column($ngos, 'id');

        if (!empty($ngoIds)) {
            $mediaResponse = Http::withHeaders($headers)->get(
                env('SUPABASE_URL') .
                '/rest/v1/media_table?select=key_id,path' .
                '&type=eq.NGO_LOGO' .
                '&key_id=in.(' . implode(',', $ngoIds) . ')' .
                '&order=id.desc'
            );

            if ($mediaResponse->successful()) {
                foreach (($mediaResponse->json() ?? []) as $media) {
                    if (isset($media['key_id'], $media['path'])
                        && !isset($logoMap[$media['key_id']])) {
                        $logoMap[$media['key_id']] = $media['path'];
                    }
                }
            }
        }

        foreach ($ngos as &$ngo) {
            $ngo['logo_url'] = $logoMap[$ngo['id']] ?? null;
        }
        unset($ngo);

        return view('ngos', compact('ngos'));
    }

    public function profile()
    {
        $headers = [
            'apikey' => env('SUPABASE_SERVICE_KEY'),
            'Authorization' => 'Bearer ' . env('SUPABASE_SERVICE_KEY'),
            'Content-Type' => 'application/json',
        ];

        $supabaseUrl = env('SUPABASE_URL');
        $ngoId = session('ngo_id');

        if (!$ngoId) {
            abort(403, 'No NGO associated with this account.');
        }

        // 1. Get the NGO profile.
        $response = Http::withHeaders($headers)->get(
            $supabaseUrl . '/rest/v1/ngo_profile?select=*&id=eq.' . $ngoId
        );

        $data = $response->successful() ? ($response->json() ?? []) : [];
        $ngo = isset($data[0]) ? (object) $data[0] : null;

        if ($ngo) {
            // 2. Get the latest NGO logo from media_table.
            $ngo->logo_url = null;
            $ngo->email = session('user_email');

            $logoResponse = Http::withHeaders($headers)->get(
                $supabaseUrl .
                '/rest/v1/media_table?select=path' .
                '&type=eq.NGO_LOGO' .
                '&key_id=eq.' . $ngo->id .
                '&order=id.desc&limit=1'
            );

            if ($logoResponse->successful()) {
                $logoData = $logoResponse->json() ?? [];
                $ngo->logo_url = $logoData[0]['path'] ?? null;
            }

            // 3. Get all bank accounts belonging to this NGO.
            $bankResponse = Http::withHeaders($headers)->get(
                $supabaseUrl . '/rest/v1/bank_accounts?select=*&ngo_id=eq.' . $ngo->id
            );

            $bankData = $bankResponse->successful()
                ? ($bankResponse->json() ?? [])
                : [];

            $ngo->bank_accounts = [];

            // 4. Get QR-code paths for those bank accounts.
            $accountIds = array_column($bankData, 'id');
            $qrCodes = [];

            if (!empty($accountIds)) {
                $mediaResponse = Http::withHeaders($headers)->get(
                    $supabaseUrl .
                    '/rest/v1/media_table?select=key_id,path,type' .
                    '&key_id=in.(' . implode(',', $accountIds) . ')' .
                    '&type=eq.BANK_ACC'
                );

                if ($mediaResponse->successful()) {
                    foreach (($mediaResponse->json() ?? []) as $media) {
                        if (isset($media['key_id'], $media['path'])) {
                            $qrCodes[$media['key_id']] = $media['path'];
                        }
                    }
                }
            }

            // 5. Combine each bank account with its QR-code URL.
            foreach ($bankData as $account) {
                $account['qr_code'] = $qrCodes[$account['id']] ?? null;
                $ngo->bank_accounts[] = $account;
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

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'contact_number' => 'required|string|max:50',
            'address' => 'required|string|max:500',
        ]);

        $response = Http::withHeaders($headers)->patch(
            env('SUPABASE_URL') . '/rest/v1/ngo_profile?id=eq.' . $ngoId,
            [
                'name' => $validated['name'],
                'description' => $validated['description'],
                'contact_number' => $validated['contact_number'],
                'address' => $validated['address'],
            ]
        );

        if (!$response->successful()) {
            Log::error('NGO profile update failed', [
                'status' => $response->status(),
                'response' => $response->body(),
            ]);

            return back()->with('error', 'Failed to update organization details.');
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

        // 1. Validate input.
        $validated = $request->validate([
            'type' => 'required|string|max:50',
            'account_name' => 'required|string|max:255',
            'account_number' => 'required|string|max:100',
            'qr_code' => 'required|image|mimes:jpg,jpeg,png|max:5120',
        ]);

        $file = $request->file('qr_code');
        if (!$file) {
            return back()->withErrors(['qr_code' => 'Please upload a QR-code image.']);
        }

        $type = strtoupper($validated['type']);

        // 2. Create the bank account.
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
            Log::error('Bank account insert failed', [
                'status' => $accountResponse->status(),
                'response' => $accountResponse->body(),
            ]);

            return back()->with('error', 'Failed to add payment account.');
        }

        // 3. Retrieve the newly created bank-account ID.
        $accountData = $accountResponse->json();

        if (empty($accountData) || !isset($accountData[0]['id'])) {
            Log::error('Bank account ID not returned', [
                'response' => $accountData,
            ]);

            return back()->with(
                'error',
                'Bank account was created but its ID could not be retrieved.'
            );
        }

        $bankAccountId = $accountData[0]['id'];

        // 4. Upload the QR image to the Supabase Storage qrcodes bucket.
        $fileName = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
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
            Log::error('QR image upload failed', [
                'status' => $uploadResponse->status(),
                'response' => $uploadResponse->body(),
            ]);

            return back()->with(
                'error',
                'Bank account was created but QR image upload failed.'
            );
        }

        // 5. Build the public URL for the uploaded QR image.
        $qrPath = $supabaseUrl .
            '/storage/v1/object/public/qrcodes/' .
            $storagePath;

        // 6. Save the QR-image URL in media_table.
        $mediaResponse = Http::withHeaders($headers)->post(
            $supabaseUrl . '/rest/v1/media_table',
            [
                'key_id' => $bankAccountId,
                'type' => 'BANK_ACC',
                'path' => $qrPath,
            ]
        );

        if (!$mediaResponse->successful()) {
            Log::error('media_table insert failed', [
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

        return back()->with(
            'success',
            'Payment account and QR code added successfully.'
        );
    }
}
