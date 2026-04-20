<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ngo_controller extends Controller
{
    public function profile()
    {
        $url = env('SUPABASE_URL') . '/rest/v1/ngo_profile?select=*';

        $response = Http::withHeaders([
            'apikey' => env('SUPABASE_SERVICE_KEY'),
            'Authorization' => 'Bearer ' . env('SUPABASE_SERVICE_KEY'),
            'Content-Type' => 'application/json'
        ])->get($url);

        $data = $response->json();

        $ngo = isset($data[0]) ? (object) $data[0] : null;

        return view('ngo_management', compact('ngo'));
    }

    public function update(Request $request)
    {
        $url = env('SUPABASE_URL') . '/rest/v1/ngo_profile?id=eq.1';

        Http::withHeaders([
            'apikey' => env('SUPABASE_SERVICE_KEY'),
            'Authorization' => 'Bearer ' . env('SUPABASE_SERVICE_KEY'),
            'Content-Type' => 'application/json'
        ])->patch($url, [
            'name' => $request->name,
            'description' => $request->description,
            'contact_number' => $request->contact_number,
            'bank_account' => $request->bank_account,
            'gcash' => $request->gcash,
            'address' => $request->address,
        ]);

        return redirect('/sm-ngos');
    }
}