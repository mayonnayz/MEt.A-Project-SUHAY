<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\login_controller;
use App\Http\Controllers\category_controller;
use App\Http\Controllers\event_controller;
use App\Http\Controllers\service_management_controller;
use App\Http\Controllers\program_controller;
use App\Http\Controllers\track_activity_controller;
use App\Http\Controllers\ngo_controller;
use App\Http\Controllers\volunteer_controller;
use App\Http\Controllers\donation_controller;
use App\Http\Controllers\inventory_controller;
use App\Http\Controllers\volunteer_application_controller;
use App\Http\Controllers\donate_controller;
use App\Http\Controllers\application_controller;


// ======================
// LANDING & AUTH
// ======================
Route::get('/', function () {
    return view('landing');
})->name('home');

Route::get('/about', function () {
    return view('about_us');
})->name('about');

Route::get('/login-page', function () {
    return view('login');
})->name('login.page');

Route::get('/donations', function () {
    return view('donations');
});
Route::get('/impact', function () {
    return view('impact');
})->name('impact');

Route::get('/donate', [donate_controller::class, 'index'])->name('donate');


Route::get('/ngos', [ngo_controller::class, 'ngosPage'])
    ->name('ngos');
Route::post('/login', [login_controller::class, 'login']);
Route::get('/sm-logout', [login_controller::class, 'logout']);


Route::prefix('volunteer')->group(function () {

    Route::get('/dashboard', [volunteer_controller::class, 'dashboard']);

    Route::get('/applications', [volunteer_controller::class, 'applications'])
        ->name('volunteer.applications');

    Route::post('/update-account', [volunteer_controller::class, 'updateAccount']);

    Route::get('/ngos', [volunteer_controller::class, 'ngos'])
    ->name('volunteer.ngos');

    // EVENTS PAGE
    Route::get('/events', [volunteer_controller::class, 'activeEvents']);

    // ✅ ASSIGNMENTS PAGE (FIXED)
    Route::get('/assignments', [volunteer_controller::class, 'assignments']);
});

// ======================
// VOLUNTEER / EVENTS PAGE
// ======================
Route::get('/volunteer-page', [event_controller::class, 'volunteerPage'])
    ->name('volunteer.page');



// ======================
// SERVICE MANAGEMENT
// ======================
Route::get('/service-management', [service_management_controller::class, 'volunteers']);
Route::get('/volunteers', [service_management_controller::class, 'volunteers']);
Route::post('/assign-volunteer', [service_management_controller::class, 'store']);
Route::patch('/volunteers/deactivate/{id}', [service_management_controller::class, 'deactivate']);

//application

Route::get('/applications', [application_controller::class, 'applications']);
Route::patch('/applications/approve/{id}', [application_controller::class, 'approveApplication']);
Route::patch('/applications/reject/{id}', [application_controller::class, 'rejectApplication']);
Route::patch('/applications/restore/{id}', [application_controller::class, 'restoreApplication']);
Route::patch('/applications/archive/{id}', [application_controller::class, 'archiveApplication']);


// ======================
// EVENTS (UPDATED SECTION)
// ======================
Route::get('/events', [event_controller::class, 'index']);
Route::post('/events', [event_controller::class, 'store']);
Route::put('/events/{id}', [event_controller::class, 'update']);
Route::put('/events/{id}/archive', [event_controller::class, 'archive']);
Route::put('/events/{id}/reactivate', [event_controller::class, 'reactivate']);
Route::delete('/activities/{id}', [event_controller::class, 'deleteActivity']);


// 🔥 THIS IS WHAT YOUR MODAL USES
Route::get('/events/{id}/activities', [event_controller::class, 'getActivities']);


// ======================
// ASSIGNMENTS (FIXED)
// ======================
Route::get('/assignments', [event_controller::class, 'assignments']);
Route::get('/api/volunteers', [service_management_controller::class, 'getVolunteers']);

Route::delete('/remove-assignment/{id}', [service_management_controller::class, 'destroy']);
// ======================
// PROGRAM CONTROLLER
// ======================
Route::post('/assign-volunteer', [service_management_controller::class, 'store']);

// ======================
// OTHER PAGES
// ======================
Route::get('/volunteer-application-form', function () {
    return view('volunteer-application-form');
});


Route::get('/volunteer-manager/dashboard', function () {
    return view('VolunteerManager.dashboard');
});


//TRACK ACTIVITY
Route::get('/track-activity', [track_activity_controller::class, 'index']);


// NGO MANAGEMENT
Route::get('/sm-ngos', [ngo_controller::class, 'profile']);
Route::post('/update-ngo', [ngo_controller::class, 'update']);
Route::get('/ngo-members', function () {
    return view('ngo_members');
});
Route::post(
    '/sm-ngos/accounts',
    [ngo_controller::class, 'addAccount']
);

Route::patch(
    '/sm-ngos/accounts/{id}',
    [ngo_controller::class, 'updateAccount']
);

Route::delete(
    '/sm-ngos/accounts/{id}',
    [ngo_controller::class, 'deleteAccount']
);




// DONATION

Route::get('/donations', [donation_controller::class, 'index']);

//Inventory

Route::get('/inventory-master-list', [inventory_controller::class, 'index'])
    ->name('inventory.master');
Route::get('/inventory', [inventory_controller::class, 'index']);
Route::post('/inventory/update/{id}', [inventory_controller::class, 'update']);

//Volunteer applicartion
Route::get('/volunteer-application-form', [volunteer_application_controller::class, 'showForm']);
Route::post('/submit-application', [volunteer_application_controller::class, 'submit_application']);