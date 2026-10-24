<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

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

<<<<<<< HEAD
    // Always return JSON on failure so donate.js can show the real error
    private function fail(string $message, int $status = 422)
    {
        return response()->json([
            'success' => false,
            'message' => $message,
        ], $status);
    }


    // =========================================
    // DONATION PAGE
    // =========================================

    public function index()
    {
        $ngoResponse = $this->supabase()
            ->get('ngo_profile?select=*');

        if (!$ngoResponse->successful()) {
            dd($ngoResponse->body());
        }

        $ngos = $ngoResponse->json();


        $accountResponse = $this->supabase()
            ->get('bank_accounts?select=*');

        if (!$accountResponse->successful()) {
            dd($accountResponse->body());
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


        // ---------- NGO LOGOS ----------
        // media_table links to the NGO through key_id (= ngo id),
        // with type = NGO_LOGO.

        $logoMap = [];

        $ngoIds = implode(',', array_column($ngos, 'id'));

        if ($ngoIds !== '') {

            $mediaResponse = $this->supabase()
                ->get(
                    'media_table?select=key_id,path'
                    . '&type=eq.NGO_LOGO'
                    . '&key_id=in.(' . $ngoIds . ')'
                    . '&order=id.desc'
                );

            if ($mediaResponse->successful()) {

                foreach ($mediaResponse->json() as $media) {

                    // Newest logo comes first, so keep only the first one per NGO
                    if (!isset($logoMap[$media['key_id']])) {
                        $logoMap[$media['key_id']] = $media['path'];
                    }
                }

            } else {

                Log::error('NGO logo lookup failed.', [
                    'status' => $mediaResponse->status(),
                    'response' => $mediaResponse->body(),
                ]);
            }
        }

        foreach ($ngos as &$ngo) {
            $ngo['logo_url'] = $logoMap[$ngo['id']] ?? null;
        }
        unset($ngo);


        return view('donate', compact('ngos'));
    }


<<<<<<< HEAD
    // =========================================
    // SUBMIT DONATION
    // =========================================

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

        // ---------- DONOR INFORMATION ----------
=======
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

>>>>>>> 7c34a96c6d5e8c974358f1343a33a5600d8354cd

        $isAnonymous = $request->boolean('anonymous');

        $accountId = $isAnonymous
            ? null
            : session('account_id');

        $source = $isAnonymous
            ? 'Anonymous'
            : ($validated['source'] ?? null);

        // ---------- UNIT ----------

<<<<<<< HEAD
        $isMonetary = in_array(
            $validated['type'],
            ['MONETARY', 'ONLINE_MONETARY'],
            true
=======
    if (
        $validated['type'] === 'MONETARY' ||
        $validated['type'] === 'ONLINE_MONETARY'
    ) {
        $unit = null;
    } else {
        $unit = !empty($validated['unit'])
            ? $validated['unit']
            : null;
    }


    if ($validated['type'] === 'ONLINE_MONETARY') {

        if (!$request->hasFile('proof_of_payment')) {

            return back()
                ->withErrors([
                    'proof_of_payment' =>
                        'Please upload your proof of payment.'
                ])
                ->withInput();
        }
    }


    $donationData = [
        'ngo_id' => (int) $validated['ngo_id'],
        'account_id' => $accountId,
        'type' => $validated['type'],
        'description' => trim($validated['description']),
        'amount' => (float) $validated['amount'],
        'unit' => $unit,
        'source' => $source,
        'date' => now()->toDateString(),
        'status' => 'Pending',
    ];


    $response = $this->supabase()
        ->withHeaders([
            'Prefer' => 'return=representation',
        ])
        ->post('donations_table', $donationData);


    if (!$response->successful()) {

        return back()
            ->withErrors([
                'donation' =>
                    'Failed to submit donation: ' .
                    $response->body()
            ])
            ->withInput();
    }

    $donations = $response->json();

    if (
        empty($donations) ||
        !isset($donations[0]['id'])
    ) {

        return back()
            ->withErrors([
                'donation' =>
                    'Donation was submitted, but the donation ID could not be retrieved.'
            ])
            ->withInput();
    }

    $donationId = $donations[0]['id'];


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



    if ($validated['type'] === 'ONLINE_MONETARY') {

        $file = $request->file('proof_of_payment');

        $extension =
            strtolower(
                $file->getClientOriginalExtension()
            );

        $fileName =
            time()
            . '_donation_'
            . $donationId
            . '_'
            . uniqid()
            . '.'
            . $extension;



        $storagePath = $fileName;


        $uploadResponse = Http::withHeaders([
            'apikey' =>
                env('SUPABASE_SERVICE_KEY'),

            'Authorization' =>
                'Bearer ' . env('SUPABASE_SERVICE_KEY'),

            'Content-Type' =>
                $file->getMimeType(),
        ])
        ->withBody(
            file_get_contents(
                $file->getRealPath()
            ),
            $file->getMimeType()
        )
        ->post(
            env('SUPABASE_URL')
            . '/storage/v1/object/proof/'
            . $storagePath
>>>>>>> 7c34a96c6d5e8c974358f1343a33a5600d8354cd
        );

        $unit = $isMonetary
            ? null
            : (!empty($validated['unit']) ? $validated['unit'] : null);

<<<<<<< HEAD
        // ---------- PROOF OF PAYMENT CHECK ----------

        if (
            $validated['type'] === 'ONLINE_MONETARY' &&
            !$request->hasFile('proof_of_payment')
        ) {
            return $this->fail('Please upload your proof of payment.');
=======
        if (!$uploadResponse->successful()) {

            $this->supabase()
                ->delete(
                    'donations_table?id=eq.'
                    . $donationId
                );

            return back()
                ->withErrors([
                    'proof_of_payment' =>
                        'Failed to upload proof of payment: '
                        . $uploadResponse->body()
                ])
                ->withInput();
>>>>>>> 7c34a96c6d5e8c974358f1343a33a5600d8354cd
        }

        // ---------- DONATION DATA ----------

<<<<<<< HEAD
        $donationData = [
            'ngo_id' => (int) $validated['ngo_id'],
            'account_id' => $accountId,
            'type' => $validated['type'],
            'description' => trim($validated['description']),
            'amount' => (float) $validated['amount'],
            'unit' => $unit,
            'source' => $source,
            'date' => now()->toDateString(),
            'status' => 'Pending',
=======
        $publicPath =
            env('SUPABASE_URL')
            . '/storage/v1/object/public/proof/'
            . $storagePath;


        $mediaData = [
            'key_id' => $donationId,
            'type' => 'PROOF_OF_DONATION',
            'path' => $publicPath,
>>>>>>> 7c34a96c6d5e8c974358f1343a33a5600d8354cd
        ];

        // ---------- INSERT DONATION ----------

        $response = $this->supabase()
            ->withHeaders(['Prefer' => 'return=representation'])
            ->post('donations_table', $donationData);

        if (!$response->successful()) {

            Log::error('Donation insert failed.', [
                'status' => $response->status(),
                'response' => $response->body(),
                'data' => $donationData,
            ]);

            return $this->fail(
                'Failed to submit donation: ' . $response->body(),
                500
            );
        }

        $donations = $response->json();

<<<<<<< HEAD
        if (empty($donations) || !isset($donations[0]['id'])) {
            return $this->fail(
                'Donation was submitted, but the donation ID could not be retrieved.',
                500
            );
        }

        $donationId = $donations[0]['id'];
=======
        if (!$mediaResponse->successful()) {
>>>>>>> 7c34a96c6d5e8c974358f1343a33a5600d8354cd

        // ---------- NOTIFICATION ----------

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
            ->withHeaders(['Prefer' => 'return=representation'])
            ->post('notifications', $notificationData);

        if (!$notificationResponse->successful()) {
            Log::error('Donation notification creation failed.', [
                'donation_id' => $donationId,
                'status' => $notificationResponse->status(),
                'response' => $notificationResponse->body(),
            ]);
        }

        // ---------- ONLINE MONETARY PROOF ----------

        if ($validated['type'] === 'ONLINE_MONETARY') {

            $file = $request->file('proof_of_payment');

            $extension = strtolower($file->getClientOriginalExtension());

            $storagePath = time()
                . '_donation_'
                . $donationId
                . '_'
                . uniqid()
                . '.'
                . $extension;

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

                $this->supabase()->delete('donations_table?id=eq.' . $donationId);

                return $this->fail(
                    'Failed to upload proof of payment: ' . $uploadResponse->body(),
                    500
                );
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

                $this->supabase()->delete('donations_table?id=eq.' . $donationId);

                return $this->fail(
                    'Failed to save proof information: ' . $mediaResponse->body(),
                    500
                );
            }
        }

        // ---------- SUCCESS ----------

        return response()->json([
            'success' => true,
            'message' => 'Donation submitted successfully!',
        ], 200);
    }
<<<<<<< HEAD
=======


    return response()->json([
    'success' => true,
    'message' => 'Donation submitted successfully!',
], 200);
}

>>>>>>> 7c34a96c6d5e8c974358f1343a33a5600d8354cd
}