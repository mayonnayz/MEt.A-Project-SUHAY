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

            // Check if email already exists
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

            // Create account
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
            $response = Http::withHeaders([
                'apikey' => env('SUPABASE_SERVICE_KEY'),
                'Authorization' => 'Bearer ' . env('SUPABASE_SERVICE_KEY'),
                'Content-Type' => 'application/json'
            ])->get(env('SUPABASE_URL') . '/rest/v1/accounts', [
                'email' => 'eq.' . $request->email
            ]);

            $users = $response->json();
            $user = $users[0] ?? null;

            if (!$user) {
                return back()->with('error', 'Wrong credentials. Please try again.');
            }

            if ($request->password !== $user['password']) {
                return back()->with('error', 'Wrong credentials. Please try again.');
            }

            if ($user['status'] != 1) {
                return back()->with('error', 'Your account is inactive. Please contact admin.');
            }

            // ✅ FIX: STORE NGO ID HERE
            session([
                'user_id' => $user['id'],
                'user_name' => $user['first_name'] . ' ' . $user['last_name'],
                'user_email' => $user['email'],
                'role' => $user['roles'],
                'ngo_id' => $user['ngo_id'] ?? null, 
            ]);

            return match (strtolower($user['roles'])) {
                '0' => redirect('/service-management'),
                '1' => redirect('/service-management'),
                '2' => redirect('/service-management'),
                '3' => redirect('/volunteer/dashboard'),
                default => redirect('/user-dashboard'),
            };

        } catch (\Exception $e) {
            return back()->with('error', 'Something went wrong. Please try again.');
        }
    }

    public function logout()
    {
        session()->flush();
        return redirect('/login-page');
    }
}