<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

// Landing Page
Route::get('/', function () {
    return view('landing');
});

// Login Page
Route::get('/login-page', function () {
    return view('login');
});

// LOGIN (Supabase)
Route::post('/login', [AuthController::class, 'login']);

// Service Management
Route::get('/service-management', function () {
    return view('ServiceManagement');
});

// Applications
Route::get('/applications', function () {
    return view('applications');
});

// Assignments
Route::get('/assignments', function () {
    return view('assignments');
});

// Track Activity
Route::get('/track-activity', function () {
    return view('track_activity');
});

Route::get('/sm-logout', [AuthController::class, 'logout']);