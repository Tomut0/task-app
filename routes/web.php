<?php

use App\Http\Controllers\TimesheetController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/', [TimesheetController::class, 'index'])->name('dashboard');

    Route::prefix('timesheet')->name('timesheet.')->group(function () {
        Route::get('/', [TimesheetController::class, 'create'])->name('create');
        Route::get('/{task}/join', [TimesheetController::class, 'join'])->name('join');
        Route::get('/{task}/leave', [TimesheetController::class, 'leave'])->name('leave');
        Route::put('/{task}', [TimesheetController::class, 'update'])->name('update');
        Route::delete('/{task}', [TimesheetController::class, 'delete'])->name('delete');
    });
});
