<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ServiceManagementController;

// Landing Page
Route::get('/', function () {
    return view('landing');
});

// Get Involved Page
Route::get('/volunteer-page', [CategoryController::class, 'volunteerPage']);

// Login Page
Route::get('/login-page', function () {
    return view('login');
});

// LOGIN (Supabase)
Route::post('/login', [AuthController::class, 'login']);

// Service Management

Route::get('/service-management', [ServiceManagementController::class, 'volunteers']);
Route::get('/applications', [ServiceManagementController::class, 'applications']);

// Assignments
Route::get('/assignments', function () {
    return view('assignments');
});

Route::get('/volunteer-application-form', function () {
    return view('volunteer-application-form');
});

// Service Categories
Route::get('/categories', [CategoryController::class, 'index']);
Route::post('/categories', [CategoryController::class, 'store']);
Route::put('/categories/{id}', [CategoryController::class, 'update']);
Route::put('/categories/{id}/archive', [CategoryController::class, 'archive']);

// Track Activity
Route::get('/track-activity', function () {
    return view('track_activity');
});

Route::get('/volunteer-manager/dashboard', function () {
    return view('VolunteerManager.dashboard');
});

Route::get('/sm-logout', [AuthController::class, 'logout']);