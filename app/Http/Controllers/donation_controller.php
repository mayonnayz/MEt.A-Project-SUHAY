<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class donation_controller extends Controller
{
    // =========================
    // ADMIN / NGO SIDE (LIST)
    // =========================
    public function index()
    {
        $ngo_id = session('ngo_id');

        if (!$ngo_id) {
            abort(403, 'Unauthorized');
        }

        $supabaseUrl = env('SUPABASE_URL');
        $supabaseKey = env('SUPABASE_SERVICE_KEY');

        $response = Http::withHeaders([
            'apikey' => $supabaseKey,
            'Authorization' => 'Bearer ' . $supabaseKey,
        ])->get($supabaseUrl . '/rest/v1/donation_history', [
            'select' => '*, donation_items(*), accounts(*)',
            'ngo_id' => 'eq.' . $ngo_id,
            'order' => 'id.desc'
        ]);

        if (!$response->successful()) {
            Log::error('Fetch donations failed', [
                'status' => $response->status(),
                'body' => $response->body()
            ]);

            return view('donations', [
                'donations' => [],
                'total_donations' => 0
            ]);
        }

        $donations = collect($response->json())->map(function ($item) {
            return (object) $item;
        });

        $total_donations = $donations->count();

        return view('donations', compact('donations', 'total_donations'));
    }

    // =========================
    // VOLUNTEER SIDE (HISTORY)
    // =========================
    public function history()
    {
        $userId = session('user_id');

        if (!$userId) {
            return redirect()->back()->with('error', 'Unauthorized access');
        }

        $supabaseUrl = env('SUPABASE_URL');
        $supabaseKey = env('SUPABASE_SERVICE_KEY');

        // GET DONATIONS
        $donationResponse = Http::withHeaders([
            'apikey' => $supabaseKey,
            'Authorization' => 'Bearer ' . $supabaseKey,
        ])->get($supabaseUrl . '/rest/v1/donation_history', [
            'select' => '*, donation_items(*)',
            'account_id' => 'eq.' . $userId,
            'order' => 'date.desc'
        ]);

        if (!$donationResponse->successful()) {
            Log::error('Fetch donation history failed', [
                'status' => $donationResponse->status(),
                'body' => $donationResponse->body()
            ]);

            return view('Volunteers.donationhistory', ['donations' => []]);
        }

        $donationsRaw = collect($donationResponse->json());

        // GET NGO NAMES
        $ngoResponse = Http::withHeaders([
            'apikey' => $supabaseKey,
            'Authorization' => 'Bearer ' . $supabaseKey,
        ])->get($supabaseUrl . '/rest/v1/ngo_profile', [
            'select' => 'id,name'
        ]);

        $ngoMap = collect($ngoResponse->json())->keyBy('id');

        // MAP DATA
        $donations = $donationsRaw->map(function ($item) use ($ngoMap) {
            $item = (object) $item;
            $item->name = $ngoMap[$item->ngo_id]['name'] ?? 'Unknown NGO';
            return $item;
        });

        return view('Volunteers.donationhistory', compact('donations'));
    }

    // =========================
    // STORE DONATION
    // =========================
    public function store(Request $request)
    {
        try {
            $userId = session('user_id') ?? 1; // fallback for testing

            $supabaseUrl = env('SUPABASE_URL');
            $supabaseKey = env('SUPABASE_SERVICE_KEY');

            Log::info('Donation data received:', $request->all());
            Log::info('TYPE VALUE:', ['type' => $request->type]);
            Log::info('PAYMENT VALUE:', ['payment_type' => $request->payment_type]);

            // =========================
            // INSERT MAIN DONATION
            // =========================
            $response = Http::withHeaders([
                'apikey' => $supabaseKey,
                'Authorization' => 'Bearer ' . $supabaseKey,
                'Content-Type' => 'application/json',
                'Prefer' => 'return=representation'
            ])->post($supabaseUrl . '/rest/v1/donation_history', [
                'account_id' => $userId,
                'ngo_id' => $request->ngo_id,
                'type' => $request->type,
                'payment_type' => ($request->type === 'monetary' && $request->payment_type)
                    ? $request->payment_type
                    : 'N/A',
                'reference_number' => $request->reference_number ?? null,
                'date' => now()
            ]);

            // =========================
            // CHECK ERROR
            // =========================
            if (!$response->successful()) {
                Log::error('Supabase Donation Insert Error:', [
                    'status' => $response->status(),
                    'body' => $response->body()
                ]);

                return response()->json([
                    'success' => false,
                    'message' => $response->body()
                ], 500);
            }

            $donationData = $response->json();
            $donationId = $donationData[0]['id'] ?? null;

            Log::info('Donation created', ['id' => $donationId]);

            // =========================
            // INSERT ITEMS (NON-MONETARY)
            // =========================
            if ($request->type === 'non-monetary' && $donationId && is_array($request->items)) {

                foreach ($request->items as $item) {

                    Http::withHeaders([
                        'apikey' => $supabaseKey,
                        'Authorization' => 'Bearer ' . $supabaseKey,
                        'Content-Type' => 'application/json'
                    ])->post($supabaseUrl . '/rest/v1/donation_items', [
                        'donation_id' => $donationId,
                        'name' => $item['name'] ?? 'Item',
                        'quantity' => (int) ($item['quantity'] ?? 1)
                    ]);
                }

                Log::info('Items inserted', ['count' => count($request->items)]);
            }

            // =========================
            // SUCCESS RESPONSE
            // =========================
            return response()->json([
                'success' => true,
                'message' => 'Donation saved successfully!',
                'donation_id' => $donationId
            ]);

        } catch (\Exception $e) {

            Log::error('Donation error: ' . $e->getMessage());
            Log::error('Request data:', $request->all());

            return response()->json([
                'success' => false,
                'message' => 'Server error: ' . $e->getMessage()
            ], 500);
        }
    }
}