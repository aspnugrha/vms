<?php

use App\Http\Controllers\Backend\AuthController;
use App\Http\Controllers\Backend\DashboardController;
use App\Http\Controllers\Backend\GuestBookController;
use App\Http\Controllers\Backend\HomeController;
use App\Http\Controllers\Backend\MasterUserController;
use App\Http\Controllers\Backend\MasterVisitorController;
use App\Http\Controllers\Backend\ProfileController;
use App\Http\Controllers\Backend\WorkScheduleController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::post('scan-qr', [HomeController::class, 'scanQRProcess'])->name('scan-qr.process');

Route::group(['prefix' => 'paneladmin'], function(){
    Route::get('login', [AuthController::class, 'login'])->name('login');
    Route::post('login', [AuthController::class, 'loginProcess'])->name('login.process');
    // Route::get('register', [AuthController::class, 'register'])->name('register');
    // Route::post('register', [AuthController::class, 'registerProcess'])->name('register.process');
    Route::get('register/activation/{email}/{code}', [AuthController::class, 'registerActivationProcess'])->name('register.activation');
    Route::get('activation', [AuthController::class, 'activation'])->name('activation');
    Route::post('activation-process', [AuthController::class, 'activationProcess'])->name('activation.process');
    Route::get('forgot-password', [AuthController::class, 'forgotPassword'])->name('forgot-password');
    Route::post('forgot-password', [AuthController::class, 'forgotPasswordProcess'])->name('forgot-password.process');
    Route::get('set-new-password/{email}/{code}', [AuthController::class, 'setNewPassword'])->name('set-new-password');
    Route::post('set-new-password', [AuthController::class, 'setNewPasswordProcess'])->name('set-new-password.process');

    Route::group(['middleware' => ['auth:web']], function(){
        Route::get('/', function(){
            return redirect()->route('dashboard');
        });
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        Route::post('api/dashboard/load-data', [DashboardController::class, 'loadData'])->name('api.dashboard.load-data');
        
        Route::get('/guestbook', [GuestBookController::class, 'index'])->name('guestbook');
        Route::get('guestbook/{code}', [GuestBookController::class, 'show'])->name('guestbook.show');
        Route::delete('guestbook/{code}', [GuestBookController::class, 'destroy'])->name('guestbook.destroy');
        Route::post('api/guestbook/load-data', [GuestBookController::class, 'loadData'])->name('api.guestbook.load-data');
        Route::post('api/guestbook/export', [GuestBookController::class, 'export'])->name('api.guestbook.export');

        // master data
        Route::resource('master-user', MasterUserController::class);
        Route::post('api/master-user/load-data', [MasterUserController::class, 'loadData'])->name('api.master-user.load-data');
        Route::post('api/master-user/export', [MasterUserController::class, 'export'])->name('api.master-user.export');
        
        Route::resource('master-visitor', MasterVisitorController::class);
        Route::post('api/master-visitor/load-data', [MasterVisitorController::class, 'loadData'])->name('api.master-visitor.load-data');
        Route::post('api/master-visitor/export', [MasterVisitorController::class, 'export'])->name('api.master-visitor.export');
        Route::get('api/master-visitor/print-card/{code}', [MasterVisitorController::class, 'printCard'])->name('api.master-visitor.print-card');
        
        Route::get('/profile', [ProfileController::class, 'index'])->name('profile');
        Route::post('logout', [AuthController::class, 'logout'])->name('logout');
    });
});

