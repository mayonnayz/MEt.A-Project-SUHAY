<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class application_controller extends Controller
{
    private function supabaseRequest($endpoint, $params = [])
    {
        $response = Http::withHeaders([
            'apikey' => env('SUPABASE_SERVICE_KEY'),
            'Authorization' => 'Bearer ' . env('SUPABASE_SERVICE_KEY'),
        ])->get(env('SUPABASE_URL') . '/rest/v1/' . $endpoint, $params);

        return $response->json();
    }

    // ✅ GET APPLICATIONS
    public function applications()
    {
        $data = $this->supabaseRequest('volunteer_applications', [
            'select' => 'id,status,availability,skills,interests,has_experience,experience_details,accounts!inner(first_name,last_name,email,address,contact_number,birth_date)',
        ]);

        $applications = collect($data)->map(function ($item) {

            $account = $item['accounts'] ?? [];

            return [
                'id' => $item['id'],
                'status' => $item['status'] ?? 0,

                'first_name' => $account['first_name'] ?? '',
                'last_name' => $account['last_name'] ?? '',
                'email' => $account['email'] ?? '',
                'address' => $account['address'] ?? '',
                'contact_number' => $account['contact_number'] ?? '',
                'birth_date' => $account['birth_date'] ?? '',

                'availability' => $item['availability'] ?? '',
                'skills' => $item['skills'] ?? '',
                'interests' => $item['interests'] ?? '',

                'has_experience' => $item['has_experience'] ?? 0,
                'experience_details' => $item['experience_details'] ?? '',
            ];
        });

        $skills = $applications
            ->pluck('skills')
            ->filter()
            ->flatMap(fn($item) => explode(',', $item))
            ->map(fn($s) => trim($s))
            ->unique()
            ->values();

        $availability = $applications
            ->pluck('availability')
            ->filter()
            ->map(fn($a) => trim($a))
            ->unique()
            ->values();

        $applications = $applications->sortBy(function ($item) {
            return strtolower(($item['first_name'] ?? '') . ' ' . ($item['last_name'] ?? ''));
        })->values();

        return view('applications', compact('applications', 'skills', 'availability'));
    }

    // ✅ APPROVE
    public function approveApplication($id)
    {
        return $this->updateStatus($id, 1);
    }

    // ❌ REJECT
    public function rejectApplication($id)
    {
        return $this->updateStatus($id, 2);
    }

    // ♻️ RESTORE
    public function restoreApplication($id)
    {
        return $this->updateStatus($id, 0);
    }

    // 📦 ARCHIVE
    public function archiveApplication($id)
    {
        return $this->updateStatus($id, 3);
    }

    // 🔧 UPDATE STATUS (CORE FUNCTION)
    private function updateStatus($id, $status)
    {
        $response = Http::withHeaders([
            'apikey' => env('SUPABASE_SERVICE_KEY'),
            'Authorization' => 'Bearer ' . env('SUPABASE_SERVICE_KEY'),
            'Content-Type' => 'application/json',
        ])->patch(env('SUPABASE_URL') . "/rest/v1/volunteer_applications?id=eq.$id", [
            'status' => $status
        ]);

        return response()->json([
            'success' => $response->successful(),
            'data' => $response->json()
        ]);
    }
}