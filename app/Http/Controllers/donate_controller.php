<?php

namespace App\Http\Controllers;

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
        $response = $this->supabase()
            ->get('ngo_profile?select=*');

        if (!$response->successful()) {
            dd($response->body()); // 🔥 SEE REAL ERROR
        }

        $ngos = $response->json();

        return view('donate', compact('ngos'));
    }
}