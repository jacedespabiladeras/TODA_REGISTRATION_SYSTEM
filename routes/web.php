<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DriverController;
use App\Http\Controllers\OperatorController;
use App\Http\Controllers\VehicleController;
use App\Http\Controllers\FranchiseController;
use App\Http\Controllers\MemberController;

use Illuminate\Support\Facades\Route;


/*
|--------------------------------------------------------------------------
| PUBLIC ROUTES
|--------------------------------------------------------------------------
*/

// When visiting the website, go directly to Login
Route::get('/', function () {
    return redirect()->route('login');
});


/*
|--------------------------------------------------------------------------
| DASHBOARD REDIRECTION
|--------------------------------------------------------------------------
|
| After login:
| Admin -> /admin
| Staff -> /staff
|
*/

Route::get('/dashboard', function () {

    $user = auth()->user();

    if (!$user->role) {
        abort(403, 'No role has been assigned to this account.');
    }

    if ($user->role->name === 'admin') {
        return redirect()->route('admin.dashboard');
    }

    if ($user->role->name === 'staff') {
        return redirect()->route('staff.dashboard');
    }

    abort(403, 'Unauthorized role.');

})->middleware(['auth', 'verified'])->name('dashboard');


/*
|--------------------------------------------------------------------------
| PROFILE
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {

    Route::get('/profile', [ProfileController::class, 'edit'])
        ->name('profile.edit');

    Route::patch('/profile', [ProfileController::class, 'update'])
        ->name('profile.update');

    Route::delete('/profile', [ProfileController::class, 'destroy'])
        ->name('profile.destroy');

});


/*
|--------------------------------------------------------------------------
| AUTHENTICATION
|--------------------------------------------------------------------------
*/

require __DIR__ . '/auth.php';


/*
|--------------------------------------------------------------------------
| ADMIN + STAFF
|--------------------------------------------------------------------------
|
| Both Admin and Staff can access these features:
|
| - Add New Driver
| - Add New Operator
| - Vehicle Registration
| - Franchise Registration
| - Franchise Renewal
| - Tracking & Expiration Alerts
| - Reports
|
*/

Route::middleware(['auth', 'role:admin,staff'])->group(function () {

    /*
    |--------------------------------------------------------------
    | MANAGEMENT
    |--------------------------------------------------------------
    */

    Route::get('/management', function () {
        return view('management');
    })->name('management');


    /*
    |--------------------------------------------------------------
    | DRIVERS
    |--------------------------------------------------------------
    */

    Route::resource(
        'drivers',
        DriverController::class
    );


    /*
    |--------------------------------------------------------------
    | OPERATORS
    |--------------------------------------------------------------
    */

    Route::resource(
        'operators',
        OperatorController::class
    );


    /*
    |--------------------------------------------------------------
    | VEHICLES
    |--------------------------------------------------------------
    */

    Route::resource(
        'vehicles',
        VehicleController::class
    );


    /*
    |--------------------------------------------------------------
    | FRANCHISES
    |--------------------------------------------------------------
    */

    Route::resource(
        'franchises',
        FranchiseController::class
    );


    /*
    |--------------------------------------------------------------
    | FRANCHISE RENEWAL
    |--------------------------------------------------------------
    */

    Route::get('/renewals', function () {
        return view('renewals.index');
    })->name('renewals.index');


    /*
    |--------------------------------------------------------------
    | TRACKING & EXPIRATION ALERTS
    |--------------------------------------------------------------
    */

    Route::get('/tracking', function () {
        return view('tracking.index');
    })->name('tracking.index');


    /*
    |--------------------------------------------------------------
    | REPORTS
    |--------------------------------------------------------------
    */

    Route::get('/reports', function () {
        return view('reports.index');
    })->name('reports.index');

});


/*
|--------------------------------------------------------------------------
| ADMIN ONLY
|--------------------------------------------------------------------------
|
| Only Admin can access these.
|
| - Admin Dashboard
| - Member Registration
| - Reports Output
|
*/

Route::middleware(['auth', 'role:admin'])->group(function () {

    /*
    |--------------------------------------------------------------
    | ADMIN DASHBOARD
    |--------------------------------------------------------------
    */

    Route::get('/admin', function () {
        return view('admin.dashboard');
    })->name('admin.dashboard');


    /*
    |--------------------------------------------------------------
    | MEMBER REGISTRATION
    |--------------------------------------------------------------
    */

    Route::resource(
        'members',
        MemberController::class
    );


    /*
    |--------------------------------------------------------------
    | REPORTS OUTPUT
    |--------------------------------------------------------------
    */

    Route::get('/reports/output', function () {
        return view('admin.reports-output');
    })->name('reports.output');

});


/*
|--------------------------------------------------------------------------
| STAFF ONLY
|--------------------------------------------------------------------------
|
| Only Staff can access this dashboard.
|
*/

Route::middleware(['auth', 'role:staff'])->group(function () {

    /*
    |--------------------------------------------------------------
    | STAFF DASHBOARD
    |--------------------------------------------------------------
    */

    Route::get('/staff', function () {
        return view('staff.dashboard');
    })->name('staff.dashboard');

});