<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class login_controller extends Controller
{
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

            
            session([
                'user_id' => $user['id'],
                'user_name' => $user['first_name'] . ' ' . $user['last_name'],
                'user_email' => $user['email'],
                'role' => $user['roles'],
                'ngo_id' => $user['ngo_id'] ?? null, // 🔥 THIS IS THE MISSING PIECE
            ]);

            return match (strtolower($user['roles'])) {
                'ngo head' => redirect('/service-management'),
                'donation manager' => redirect('/service-management'),
                'volunteer manager' => redirect('/service-management'),
                'volunteer' => redirect('/volunteer/dashboard'),
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