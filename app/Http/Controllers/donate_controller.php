<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class donate_controller extends Controller
{
    private function supabase()
    {
        return Http::withHeaders([
            'apikey' => env('SUPABASE_SERVICE_KEY'),
            'Authorization' => 'Bearer ' . env('SUPABASE_SERVICE_KEY'),
            'Content-Type' => 'application/json',
        ])->baseUrl(env('SUPABASE_URL') . '/rest/v1/');
    }

    public function index()
    {
        $ngoResponse = $this->supabase()
            ->get('ngo_profile?select=*');

        if (!$ngoResponse->successful()) {
         
        }

        $ngos = $ngoResponse->json();


        $accountResponse = $this->supabase()
            ->get('bank_accounts?select=*');

        if (!$accountResponse->successful()) {
         
        }

        $bankAccounts = $accountResponse->json();

        foreach ($ngos as &$ngo) {

            $ngo['bank_accounts'] = array_values(
                array_filter(
                    $bankAccounts,
                    function ($account) use ($ngo) {
                        return $account['ngo_id'] == $ngo['id'];
                    }
                )
            );

        }
        unset($ngo);

            $logoIds = array_filter(
                array_map(
                    fn ($ngo) => $ngo['logo'] ?? null,
                    $ngos
                )
            );

        $logoMap = [];

        if (!empty($logoIds)) {

            $ids = implode(',', $logoIds);

            $mediaResponse = $this->supabase()
                ->get(
                    'media_table?select=id,path'
                    . '&id=in.(' . $ids . ')'
                    . '&type=eq.NGO_LOGO'
                );

            if ($mediaResponse->successful()) {

                foreach ($mediaResponse->json() as $media) {
                    $logoMap[$media['id']] = $media['path'];
                }
            }
        }

        foreach ($ngos as &$ngo) {
            $logoId = $ngo['logo'] ?? null;

            $ngo['logo_url'] = $logoId
                ? ($logoMap[$logoId] ?? null)
                : null;
        }
        unset($ngo);


        return view('donate', compact('ngos'));
    }



public function submitDonation(Request $request)
{
    $validated = $request->validate([
        'ngo_id' => 'required|integer',
        'type' => 'required|in:MONETARY,ONLINE_MONETARY,CONSUMABLE,REUSABLE',
        'description' => 'required|string|max:1000',
        'amount' => 'required|numeric|min:0.01',
        'unit' => 'nullable|string|max:100',
        'source' => 'nullable|string|max:255',
        'anonymous' => 'nullable|boolean',
        'proof_of_payment' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:10240',
    ]);

    $isAnonymous = $request->boolean('anonymous');

    $accountId = null;

    if (!$isAnonymous) {
        $accountId = session('user_id');

        if (!$accountId) {
            return response()->json([
                'success' => false,
                'message' => 'Please log in before submitting a non-anonymous donation.'
            ], 401);
        }
    }

    $source = $isAnonymous
        ? 'Anonymous'
        : ($validated['source'] ?? null);

    $unit = in_array($validated['type'], ['MONETARY', 'ONLINE_MONETARY'])
    ? 'N/A'
    : ($validated['unit'] ?? 'N/A');

    if (
        $validated['type'] === 'ONLINE_MONETARY' &&
        !$request->hasFile('proof_of_payment')
    ) {
        return response()->json([
            'success' => false,
            'message' => 'Please upload your proof of payment.'
        ], 422);
    }

    $donationData = [
        'ngo_id' => (int) $validated['ngo_id'],
        'account_id' => $accountId,
        'type' => $validated['type'],
        'description' => trim($validated['description']),
        'amount' => (float) $validated['amount'],
        'unit' => $unit,
        'source' => $source,
        'date' => now('Asia/Manila')->toDateString(),
        'status' => 'Pending',
    ];

    $response = $this->supabase()
        ->withHeaders([
            'Prefer' => 'return=representation',
        ])
        ->post('donations_table', $donationData);

    if (!$response->successful()) {
        \Illuminate\Support\Facades\Log::error(
            'Donation insertion failed.',
            [
                'status' => $response->status(),
                'response' => $response->body(),
            ]
        );

        return response()->json([
            'success' => false,
            'message' => 'Failed to submit donation. Please try again.'
        ], 500);
    }

    $donations = $response->json();

    if (empty($donations) || !isset($donations[0]['id'])) {
        return response()->json([
            'success' => false,
            'message' => 'Donation was submitted, but its ID could not be retrieved.'
        ], 500);
    }

    $donationId = $donations[0]['id'];

    // Upload proof of payment for online monetary donations.
    if ($validated['type'] === 'ONLINE_MONETARY') {

        $file = $request->file('proof_of_payment');

        $extension = strtolower($file->getClientOriginalExtension());

        $fileName = time()
            . '_donation_'
            . $donationId
            . '_'
            . uniqid()
            . '.'
            . $extension;

        $storagePath = $fileName;

        $uploadResponse = Http::withHeaders([
            'apikey' => env('SUPABASE_SERVICE_KEY'),
            'Authorization' => 'Bearer ' . env('SUPABASE_SERVICE_KEY'),
            'Content-Type' => $file->getMimeType(),
        ])
        ->withBody(
            file_get_contents($file->getRealPath()),
            $file->getMimeType()
        )
        ->post(
            env('SUPABASE_URL')
            . '/storage/v1/object/proof/'
            . $storagePath
        );

        if (!$uploadResponse->successful()) {

            $this->supabase()->delete(
                'donations_table?id=eq.' . $donationId
            );

            \Illuminate\Support\Facades\Log::error(
                'Proof of payment upload failed.',
                [
                    'donation_id' => $donationId,
                    'status' => $uploadResponse->status(),
                    'response' => $uploadResponse->body(),
                ]
            );

            return response()->json([
                'success' => false,
                'message' => 'Failed to upload proof of payment.'
            ], 500);
        }

        $publicPath = env('SUPABASE_URL')
            . '/storage/v1/object/public/proof/'
            . $storagePath;

        $mediaResponse = $this->supabase()->post('media_table', [
            'key_id' => $donationId,
            'type' => 'PROOF_OF_DONATION',
            'path' => $publicPath,
        ]);

        if (!$mediaResponse->successful()) {

            Http::withHeaders([
                'apikey' => env('SUPABASE_SERVICE_KEY'),
                'Authorization' => 'Bearer ' . env('SUPABASE_SERVICE_KEY'),
            ])->delete(
                env('SUPABASE_URL')
                . '/storage/v1/object/proof/'
                . $storagePath
            );

            $this->supabase()->delete(
                'donations_table?id=eq.' . $donationId
            );

            \Illuminate\Support\Facades\Log::error(
                'Proof of payment media insertion failed.',
                [
                    'donation_id' => $donationId,
                    'response' => $mediaResponse->body(),
                ]
            );

            return response()->json([
                'success' => false,
                'message' => 'Failed to save proof of payment information.'
            ], 500);
        }
    }

    // Create NGO-wide notification.
    $notificationData = [
        'title' => 'New Donation Received',
        'message' => 'A new '
            . strtolower($validated['type'])
            . ' donation has been submitted: '
            . trim($validated['description']) . '.',
        'type' => 'Donation',
        'reference_id' => $donationId,
        'created_at' => now('Asia/Manila')->toIso8601String(),
        'related_id' => (int) $validated['ngo_id'],
    ];

    $notificationResponse = $this->supabase()
        ->withHeaders([
            'Prefer' => 'return=representation',
        ])
        ->post('notifications', $notificationData);

    if (!$notificationResponse->successful()) {
        \Illuminate\Support\Facades\Log::error(
            'Donation notification creation failed.',
            [
                'donation_id' => $donationId,
                'status' => $notificationResponse->status(),
                'response' => $notificationResponse->body(),
            ]
        );
    }

    return response()->json([
        'success' => true,
        'message' => 'Donation submitted successfully!',
        'donation_id' => $donationId,
    ], 200);
}


}