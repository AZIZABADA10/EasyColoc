<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ColocationController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\InvitationController;

Route::get('/', function () {
    return view('auth.register');
});

Route::get('/invitation/{token}', [InvitationController::class, 'handle'])
    ->name('invitations.accept');


Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');

    Route::resource('colocations', ColocationController::class);

    Route::get('colocations/{colocation}/categories/create', [CategoryController::class, 'create'])
        ->name('categories.create');
    Route::post('colocations/{colocation}/categories', [CategoryController::class, 'store'])
        ->name('categories.store');
    Route::delete('categories/{categorie}', [CategoryController::class, 'destroy'])
        ->name('categories.destroy');

    // Route::get('colocations/{colocation}/expenses', [ExpenseController::class, 'index'])
    //     ->name('expenses.index');
    // Route::get('colocations/{colocation}/expenses/create', [ExpenseController::class, 'create'])
    //     ->name('expenses.create');
    // Route::post('colocations/{colocation}/expenses', [ExpenseController::class, 'store'])
    //     ->name('expenses.store');
    // Route::delete('expenses/{depense}', [ExpenseController::class, 'destroy'])
    //     ->name('expenses.destroy');

    Route::get('colocations/{colocation}/invite', [InvitationController::class, 'create'])
        ->name('invitations.create');

    Route::post('colocations/{colocation}/invite', [InvitationController::class, 'store'])
        ->name('invitations.store');



    Route::post('/invitation/{token}/refuse', [InvitationController::class, 'refuse'])
        ->name('invitations.refuse');

 
    Route::post('/invitation/{token}/accept', [InvitationController::class, 'accept'])
        ->name('invitations.confirm');

    Route::delete('/colocations/{colocation}/leave', [ColocationController::class, 'leave'])
        ->name('colocations.leave');

    Route::delete('/colocations/{colocation}/remove/{user}', [ColocationController::class, 'removeMember'])
        ->name('colocations.removeMember');
 

    Route::middleware('admin')->group(function () {
        Route::get('/admin', function () {
            return view('admin.dashboard');
        })->name('admin.dashboard');
    });
   

});
