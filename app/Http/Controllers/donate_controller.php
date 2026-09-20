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


    // =========================================
    // DONATION PAGE
    // =========================================

    public function index()
    {
        // Get NGO profiles
        $ngoResponse = $this->supabase()
            ->get('ngo_profile?select=*');

        if (!$ngoResponse->successful()) {
            dd($ngoResponse->body());
        }

        $ngos = $ngoResponse->json();


        // Get all bank accounts
        $accountResponse = $this->supabase()
            ->get('bank_accounts?select=*');

        if (!$accountResponse->successful()) {
            dd($accountResponse->body());
        }

        $bankAccounts = $accountResponse->json();


        // Attach bank accounts to their respective NGO
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

        return view('donate', compact('ngos'));
    }


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

    /*
    |--------------------------------------------------------------------------
    | DONOR INFORMATION
    |--------------------------------------------------------------------------
    */

    $isAnonymous = $request->boolean('anonymous');

    $accountId = $isAnonymous
        ? null
        : session('account_id');

    $source = $isAnonymous
        ? 'Anonymous'
        : ($validated['source'] ?? null);


    /*
    |--------------------------------------------------------------------------
    | UNIT
    |--------------------------------------------------------------------------
    |
    | Monetary donations do not need a unit.
    |
    */

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


    /*
    |--------------------------------------------------------------------------
    | PROOF OF PAYMENT
    |--------------------------------------------------------------------------
    |
    | Only ONLINE_MONETARY requires proof.
    |
    */

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


    /*
    |--------------------------------------------------------------------------
    | DONATION DATA
    |--------------------------------------------------------------------------
    */

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


    /*
    |--------------------------------------------------------------------------
    | INSERT DONATION
    |--------------------------------------------------------------------------
    */

    $response = $this->supabase()
        ->withHeaders([
            'Prefer' => 'return=representation',
        ])
        ->post('donations_table', $donationData);


    /*
    |--------------------------------------------------------------------------
    | DONATION INSERT FAILED
    |--------------------------------------------------------------------------
    */

    if (!$response->successful()) {

        return back()
            ->withErrors([
                'donation' =>
                    'Failed to submit donation: ' .
                    $response->body()
            ])
            ->withInput();
    }


    /*
    |--------------------------------------------------------------------------
    | GET DONATION ID
    |--------------------------------------------------------------------------
    */

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


    /*
    |--------------------------------------------------------------------------
    | ONLINE MONETARY PROOF
    |--------------------------------------------------------------------------
    */

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

        /*
        | Bucket = proof
        | File = filename
        */

        $storagePath = $fileName;


        /*
        |--------------------------------------------------------------------------
        | UPLOAD FILE TO SUPABASE STORAGE
        |--------------------------------------------------------------------------
        */

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
        );


        /*
        |--------------------------------------------------------------------------
        | UPLOAD FAILED
        |--------------------------------------------------------------------------
        */

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
        }


        /*
        |--------------------------------------------------------------------------
        | PUBLIC PROOF URL
        |--------------------------------------------------------------------------
        */

        $publicPath =
            env('SUPABASE_URL')
            . '/storage/v1/object/public/proof/'
            . $storagePath;


        /*
        |--------------------------------------------------------------------------
        | SAVE PROOF TO MEDIA TABLE
        |--------------------------------------------------------------------------
        */

        $mediaData = [
            'key_id' => $donationId,
            'type' => 'PROOF_OF_DONATION',
            'path' => $publicPath,
        ];

        $mediaResponse = $this->supabase()
            ->post(
                'media_table',
                $mediaData
            );


        /*
        |--------------------------------------------------------------------------
        | MEDIA INSERT FAILED
        |--------------------------------------------------------------------------
        */

        if (!$mediaResponse->successful()) {

            Http::withHeaders([
                'apikey' =>
                    env('SUPABASE_SERVICE_KEY'),

                'Authorization' =>
                    'Bearer ' .
                    env('SUPABASE_SERVICE_KEY'),
            ])
            ->delete(
                env('SUPABASE_URL')
                . '/storage/v1/object/proof/'
                . $storagePath
            );

            $this->supabase()
                ->delete(
                    'donations_table?id=eq.'
                    . $donationId
                );

            return back()
                ->withErrors([
                    'proof_of_payment' =>
                        'Failed to save proof information: '
                        . $mediaResponse->body()
                ])
                ->withInput();
        }
    }


    /*
    |--------------------------------------------------------------------------
    | SUCCESS
    |--------------------------------------------------------------------------
    */

    return redirect('/donate')
        ->with('success', 'Donation submitted successfully!');
}

}