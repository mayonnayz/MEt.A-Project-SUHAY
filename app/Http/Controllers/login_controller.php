<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class login_controller extends Controller
{
    public function signup(Request $request){
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'password' => 'required|string|min:8',
            'address' => 'required|string|max:255',
            'birthdate' => 'required|date_format:Y-m-d',
            'contact_number' => 'required|string|max:20',
        ]);

        try {

            
            $existingUser = Http::withHeaders([
                'apikey' => env('SUPABASE_SERVICE_KEY'),
                'Authorization' => 'Bearer ' . env('SUPABASE_SERVICE_KEY'),
                'Content-Type' => 'application/json'
            ])->get(env('SUPABASE_URL') . '/rest/v1/accounts', [
                'email' => 'eq.' . $request->email
            ]);

            if (!$existingUser->successful()) {
                return back()
                    ->withInput()
                    ->with('error', 'Unable to check email. Please try again.');
            }

            if (count($existingUser->json()) > 0) {
                return back()
                    ->withInput()
                    ->with('error', 'This email is already registered. Please use a different email.');
            }

            
            $response = Http::withHeaders([
                'apikey' => env('SUPABASE_SERVICE_KEY'),
                'Authorization' => 'Bearer ' . env('SUPABASE_SERVICE_KEY'),
                'Content-Type' => 'application/json',
                'Prefer' => 'return=minimal'
            ])->post(env('SUPABASE_URL') . '/rest/v1/accounts', [
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'email' => $request->email,
                'password' => $request->password,
                'role' => 3,
                'status' => 1,
                'ngo_id' => null,
                'address' => $request->address,
                'birth_date' => $request->birthdate,
                'contact_number' => $request->contact_number,
            ]);

            if (!$response->successful()) {
                return back()
                    ->withInput()
                    ->with('error', 'Unable to create your account. Please try again.');
            }

            return redirect('/login-page')
                ->with('success', 'Account created successfully. You can now log in.');

        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('error', 'Something went wrong. Please try again.');
        }
    }

    
public function login(Request $request)
{
    $request->validate([
        'email' => 'required|email',
        'password' => 'required'
    ]);

    try {
        $headers = [
            'apikey' => env('SUPABASE_SERVICE_KEY'),
            'Authorization' => 'Bearer ' . env('SUPABASE_SERVICE_KEY'),
            'Content-Type' => 'application/json'
        ];

        $baseUrl = env('SUPABASE_URL') . '/rest/v1/';

       
        $response = Http::withHeaders($headers)->get(
            $baseUrl . 'accounts',
            [
                'select' => '*',
                'email' => 'eq.' . $request->email,
                'limit' => 1
            ]
        );

        if (!$response->successful()) {
            return back()->with('error', 'Unable to connect to the server. Please try again.');
        }

        $users = $response->json();
        $user = $users[0] ?? null;

        if (!$user || $request->password !== $user['password']) {
            return back()->with('error', 'Wrong credentials. Please try again.');
        }

        if ((int) $user['status'] !== 1) {
            return back()->with('error', 'Your account is inactive. Please contact admin.');
        }

        $accountId = $user['id'];
        $ngoId = $user['ngo_id'] ?? null;

        // GET PERSONAL PROFILE PICTURE
        $profilePicture = null;

        $profileResponse = Http::withHeaders($headers)->get(
            $baseUrl . 'media_table',
            [
                'select' => 'path',
                'key_id' => 'eq.' . $accountId,
                'type' => 'eq.PROFILE_PICTURE',
                'limit' => 1
            ]
        );

        if ($profileResponse->successful()) {
            $profileMedia = $profileResponse->json();
            $profilePicture = $profileMedia[0]['path'] ?? null;
        }

      
        $ngoLogoUrl = null;

        if ($ngoId !== null) {
            $ngoResponse = Http::withHeaders($headers)->get(
                $baseUrl . 'ngo_profile',
                [
                    'select' => 'logo',
                    'id' => 'eq.' . $ngoId,
                    'limit' => 1
                ]
            );

            if ($ngoResponse->successful()) {
                $ngoData = $ngoResponse->json();
                $logoId = $ngoData[0]['logo'] ?? null;

                if ($logoId !== null) {
                    $mediaResponse = Http::withHeaders($headers)->get(
                        $baseUrl . 'media_table',
                        [
                            'select' => 'path',
                            'id' => 'eq.' . $logoId,
                            'type' => 'eq.NGO_LOGO',
                            'limit' => 1
                        ]
                    );

                    if ($mediaResponse->successful()) {
                        $mediaData = $mediaResponse->json();
                        $ngoLogoUrl = $mediaData[0]['path'] ?? null;
                    }
                }
            }
        }

      
        session([
            'user_id' => $accountId,
            'user_name' => $user['first_name'] . ' ' . $user['last_name'],
            'user_email' => $user['email'],
            'role' => $user['roles'],
            'ngo_id' => $ngoId,
            'ngo_logo' => $ngoLogoUrl,
            'profile_picture' => $profilePicture,
        ]);

        return match ((int) $user['roles']) {
            0, 1, 2 => redirect()->route('sm.dashboard'),
            3 => redirect('/volunteer/dashboard'),
            default => redirect()->route('login.page')
                ->with('error', 'Invalid account role.'),
        };

    } catch (\Exception $e) {
        \Illuminate\Support\Facades\Log::error(
            'Login error: ' . $e->getMessage()
        );

        return back()->with('error', 'Something went wrong. Please try again.');
    }
}

    public function logout()
    {
        session()->flush();
        return redirect('/login-page');
    }
}