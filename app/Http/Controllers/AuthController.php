<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class AuthController extends Controller
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

            if ($response->failed()) {
                return back()->with('error', 'Something went wrong. Please try again.');
            }

            $users = $response->json();

            if (empty($users) || $users[0]['password'] !== $request->password) {
                return back()->with('error', 'Wrong credentials. Please try again.');
            }

            $user = $users[0];

            // ❗ STATUS CHECK (ONLY ACTIVE USERS ALLOWED)
            if ($user['status'] != 1) {
                return back()->with('error', 'Your account is inactive. Please contact admin.');
            }

            session([
                'user_id' => $user['id'],
                'user_name' => $user['first_name'] . ' ' . $user['last_name'],
                'user_email' => $user['email'],
                'role' => $user['roles']
            ]);

            // ROLE ROUTING
            $role = strtolower($user['roles']);

            if ($role === 'admin') {
                return redirect('/service-management');
            }

            if ($role === 'volunteer manager') {
                return redirect('/volunteer-manager/dashboard');
            }

            return redirect('/user-dashboard');

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