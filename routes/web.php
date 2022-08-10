<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\NotificationController;

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

Route::get('/', function () {
    return  redirect()->route('login');
});
Route::get('/test', [NotificationController::class, 'test']);
Route::get('/send', [NotificationController::class, 'sendMailNotification']);
Auth::routes();
Route::group(['middleware' => 'auth'], function () {
    Route::get('/update-password', [UserController::class, 'update_password'])->name('update-password-logon');
    Route::post('/update-password', [UserController::class, 'update_password_handle'])->name('update-password-on-logon');

    Route::group(['middleware' => 'userMustChangePassword'], function () {
        Route::view('/home', 'home')->name('home');
        Route::view('/requisitions', 'requisitions')->name('requisitions');
        Route::view('/returns', 'returns')->name('returns');
        Route::post('/download-requisition-pdf', [ReportController::class, 'requisitionReport'])->name('download-requisition-pdf');
        Route::get('/profile', [UserController::class, 'index'])->name('profile');
        Route::post('/update-profile', [UserController::class, 'updatePassword'])->name('update-password');
        Route::view('/not-allowed', 'not-allowed')->name('not-allowed');

        Route::group(['middleware' => 'isApprover'], function () {
            Route::view('/requisitions-approval', 'requisitions-approval')->name('requisitions-approval');
        });
        Route::group(['middleware' => 'isAdmin'], function () {
            Route::view('/user-management', 'users')->name('users');
            Route::view('/tools-management', 'tools')->name('tools');
            Route::view('/tools-issue', 'tool-issue')->name('tool-issue');
        });
    });
});
