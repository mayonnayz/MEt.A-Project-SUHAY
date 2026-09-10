
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


/*
|--------------------------------------------------------------------------
| LANDING & AUTHENTICATION
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('landing');
})->name('home');

Route::get('/about', function () {
    return view('about_us');
})->name('about');

Route::get('/login-page', function () {
    return view('login');
})->name('login.page');

Route::post('/login', [login_controller::class, 'login']);

Route::get('/sm-logout', [login_controller::class, 'logout']);


/*
|--------------------------------------------------------------------------
| DONATIONS
|--------------------------------------------------------------------------
*/

Route::get('/donations', [donation_controller::class, 'index']);

Route::get('/impact', function () {
    return view('impact');
})->name('impact');

Route::get('/donate', [donate_controller::class, 'index'])
    ->name('donate');


/*
|--------------------------------------------------------------------------
| NGO PUBLIC PAGE
|--------------------------------------------------------------------------
*/

Route::get('/ngos', [ngo_controller::class, 'ngosPage'])
    ->name('ngos');


/*
|--------------------------------------------------------------------------
| VOLUNTEER USER PAGES
|--------------------------------------------------------------------------
*/

Route::prefix('volunteer')->group(function () {

    // Volunteer Dashboard
    Route::get('/dashboard', [volunteer_controller::class, 'dashboard']);

    // Volunteer Applications
    Route::get('/applications', [volunteer_controller::class, 'applications'])
        ->name('volunteer.applications');

    // Update Account
    Route::post('/update-account', [volunteer_controller::class, 'updateAccount']);

    // NGO Page
    Route::get('/ngos', [volunteer_controller::class, 'ngos'])
        ->name('volunteer.ngos');

    // Volunteer Events
    Route::get('/events', [volunteer_controller::class, 'activeEvents']);

    // Volunteer Assignments
    Route::get('/assignments', [volunteer_controller::class, 'assignments']);

    // Profile Picture
    Route::post('/update-profile-picture', [volunteer_controller::class, 'updateProfilePicture']);
});


/*
|--------------------------------------------------------------------------
| VOLUNTEER / EVENTS PUBLIC PAGE
|--------------------------------------------------------------------------
*/

Route::get('/volunteer-page', [event_controller::class, 'volunteerPage'])
    ->name('volunteer.page');


/*
|--------------------------------------------------------------------------
| SERVICE MANAGEMENT
|--------------------------------------------------------------------------
*/

// Service Management main page
Route::get('/service-management', [service_management_controller::class, 'volunteers']);

// Volunteers page
Route::get('/volunteers', [service_management_controller::class, 'volunteers']);

// Deactivate volunteer
Route::patch(
    '/volunteers/deactivate/{id}',
    [service_management_controller::class, 'deactivate']
);


/*
|--------------------------------------------------------------------------
| VOLUNTEER APPLICATIONS
|--------------------------------------------------------------------------
*/

// Application form
Route::get(
    '/volunteer-application-form',
    [volunteer_application_controller::class, 'showForm']
);

// Submit application
Route::post(
    '/submit-application',
    [volunteer_application_controller::class, 'submit_application']
);


/*
|--------------------------------------------------------------------------
| APPLICATION MANAGEMENT
|--------------------------------------------------------------------------
*/

Route::get(
    '/applications',
    [application_controller::class, 'applications']
);

Route::patch(
    '/applications/approve/{id}',
    [application_controller::class, 'approveApplication']
);

Route::patch(
    '/applications/reject/{id}',
    [application_controller::class, 'rejectApplication']
);

Route::patch(
    '/applications/restore/{id}',
    [application_controller::class, 'restoreApplication']
);

Route::patch(
    '/applications/archive/{id}',
    [application_controller::class, 'archiveApplication']
);


/*
|--------------------------------------------------------------------------
| EVENTS MANAGEMENT
|--------------------------------------------------------------------------
*/

// Events page
Route::get(
    '/events',
    [event_controller::class, 'index']
);

// Create event
Route::post(
    '/events',
    [event_controller::class, 'store']
);

// Update event
Route::put(
    '/events/{id}',
    [event_controller::class, 'update']
);

// Archive event
Route::put(
    '/events/{id}/archive',
    [event_controller::class, 'archive']
);

// Reactivate event
Route::put(
    '/events/{id}/reactivate',
    [event_controller::class, 'reactivate']
);

// Get activities belonging to an event
Route::get(
    '/events/{id}/activities',
    [event_controller::class, 'getActivities']
);

// Get number of assigned volunteers for an event
Route::get(
    '/events/{id}/assigned-count',
    [event_controller::class, 'assignedCount']
);

// Delete activity
Route::delete(
    '/activities/{id}',
    [event_controller::class, 'deleteActivity']
);


/*
|--------------------------------------------------------------------------
| VOLUNTEER ASSIGNMENTS
|--------------------------------------------------------------------------
*/

// Get volunteers who applied to the selected event
//
// IMPORTANT:
// This is the endpoint used by your JavaScript:
//
// fetch(`/api/volunteers?event_id=${currentEventId}`)
//
// Keep ONLY ONE /api/volunteers route.
Route::get(
    '/api/volunteers',
    [service_management_controller::class, 'getVolunteers']
);

// Assign volunteer to activity
Route::post(
    '/assign-volunteer',
    [service_management_controller::class, 'store']
);

// Remove volunteer assignment
Route::delete(
    '/remove-assignment/{id}',
    [service_management_controller::class, 'destroy']
);


/*
|--------------------------------------------------------------------------
| ASSIGNMENTS PAGE
|--------------------------------------------------------------------------
*/

// Event/service management assignments page
Route::get(
    '/assignments',
    [event_controller::class, 'assignments']
);


/*
|--------------------------------------------------------------------------
| VOLUNTEER MANAGER
|--------------------------------------------------------------------------
*/

Route::get(
    '/volunteer-manager/dashboard',
    function () {
        return view('VolunteerManager.dashboard');
    }
);


/*
|--------------------------------------------------------------------------
| TRACK ACTIVITY
|--------------------------------------------------------------------------
*/

Route::get(
    '/track-activity',
    [track_activity_controller::class, 'index']
);


/*
|--------------------------------------------------------------------------
| NGO MANAGEMENT
|--------------------------------------------------------------------------
*/

// NGO profiles
Route::get(
    '/sm-ngos',
    [ngo_controller::class, 'profile']
);

// Update NGO
Route::post(
    '/update-ngo',
    [ngo_controller::class, 'update']
);

// NGO members
Route::get(
    '/ngo-members',
    function () {
        return view('ngo_members');
    }
);

// Add NGO account
Route::post(
    '/sm-ngos/accounts',
    [ngo_controller::class, 'addAccount']
);

// Update NGO account
Route::patch(
    '/sm-ngos/accounts/{id}',
    [ngo_controller::class, 'updateAccount']
);

// Delete NGO account
Route::delete(
    '/sm-ngos/accounts/{id}',
    [ngo_controller::class, 'deleteAccount']
);


/*
|--------------------------------------------------------------------------
| INVENTORY
|--------------------------------------------------------------------------
*/

Route::get(
    '/inventory-master-list',
    [inventory_controller::class, 'index']
)->name('inventory.master');

Route::get(
    '/inventory',
    [inventory_controller::class, 'index']
);

Route::post(
    '/inventory/update/{id}',
    [inventory_controller::class, 'update']
);

