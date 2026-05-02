<?php

namespace App\Http\Controllers;

use App\Models\donation_history;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
class donation_controller extends Controller{
public function index()
{
    $ngo_id = session('ngo_id');

    if (!$ngo_id) {
        abort(403, 'Unauthorized');
    }

    $donations = donation_history::with(['items', 'account'])
        ->where('ngo_id', $ngo_id)
        ->orderBy('id', 'desc')
        ->get();

    $total_donations = donation_history::where('ngo_id', $ngo_id)->count();

    return view('donations', compact('donations', 'total_donations'));
}

// public function history()
// {
//     $userId = session('user_id');

//     if (!$userId) {
//         return redirect()->back()->with('error', 'Unauthorized access');
//     }

//     $supabaseUrl = env('SUPABASE_URL');
//     $supabaseKey = env('SUPABASE_SERVICE_KEY');

//     // STEP 1: get donations (only using ngo_id)
//     $donationResponse = Http::withHeaders([
//         'apikey' => $supabaseKey,
//         'Authorization' => 'Bearer ' . $supabaseKey,
//     ])->get($supabaseUrl . '/rest/v1/donation_history', [
//         'select' => 'id,date,ngo_id,type',
//         'account_id' => 'eq.' . $userId,
//         'order' => 'date.desc'
//     ]);

//     $donationsRaw = collect($donationResponse->json());

//     // STEP 2: get all NGOs (map once, not per row = faster)
//     $ngoResponse = Http::withHeaders([
//         'apikey' => $supabaseKey,
//         'Authorization' => 'Bearer ' . $supabaseKey,
//     ])->get($supabaseUrl . '/rest/v1/ngo_profile', [
//         'select' => 'id,name'
//     ]);

//     $ngoMap = collect($ngoResponse->json())->keyBy('id');

//     // STEP 3: attach NGO name to each donation
//     $donations = $donationsRaw->map(function ($item) use ($ngoMap) {
//         $item = (object) $item;

//         $item->name = $ngoMap[$item->ngo_id]['name'] ?? 'Unknown NGO';

//         return $item;
//     });


//     return view('Volunteers.donationhistory', compact('donations'));
// }

public function history()
{
    $userId = session('user_id');

    if (!$userId) {
        return redirect()->back()->with('error', 'Unauthorized access');
    }

    $supabaseUrl = env('SUPABASE_URL');
    $supabaseKey = env('SUPABASE_SERVICE_KEY');

    // ✅ GET ALL FIELDS
    $donationResponse = Http::withHeaders([
        'apikey' => $supabaseKey,
        'Authorization' => 'Bearer ' . $supabaseKey,
    ])->get($supabaseUrl . '/rest/v1/donation_history', [
        'select' => '*,  donation_items(*)', 
        'account_id' => 'eq.' . $userId,
        'order' => 'date.desc'
    ]);

    $donationsRaw = collect($donationResponse->json());

    // ✅ GET NGO NAMES
    $ngoResponse = Http::withHeaders([
        'apikey' => $supabaseKey,
        'Authorization' => 'Bearer ' . $supabaseKey,
    ])->get($supabaseUrl . '/rest/v1/ngo_profile', [
        'select' => 'id,name'
    ]);

    $ngoMap = collect($ngoResponse->json())->keyBy('id');

    // ✅ MAP DATA
    $donations = $donationsRaw->map(function ($item) use ($ngoMap) {
        $item = (object) $item;

        $item->name = $ngoMap[$item->ngo_id]['name'] ?? 'Unknown NGO';

        return $item;
    });

    return view('Volunteers.donationhistory', compact('donations'));
}
}
