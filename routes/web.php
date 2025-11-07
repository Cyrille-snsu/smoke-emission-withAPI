<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Mail;
use App\Mail\ScheduleConfirmation;
use App\Models\Schedule;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ScheduleController;

// Simple test email route
Route::get('/test-email-simple', function () {
    try {
        // Log the email sending attempt
        \Log::info('Attempting to send test email...');
        
        // Create a simple test email without any database dependencies
        Mail::send('emails.test', [], function($message) {
            $message->to('cyrillekim112@gmail.com')
                    ->subject('Test Email from Laravel at ' . now());
        });
        
        \Log::info('Test email sent successfully');
        return 'Test email sent to cyrillekim112@gmail.com. Check your Mailtrap inbox.';
    } catch (\Exception $e) {
        \Log::error('Failed to send test email: ' . $e->getMessage());
        return 'Error: ' . $e->getMessage() . ' in ' . $e->getFile() . ' on line ' . $e->getLine();
    }
});

Route::get('/', function () {
    return view('home');
});

// Test email route (temporary - remove in production)
Route::get('/test-email', function () {
    try {
        $schedule = \App\Models\Schedule::with(['user', 'vehicle', 'test_center'])->first();
        
        if (!$schedule) {
            return 'No schedule found. Please create a schedule first.';
        }
        
        \Illuminate\Support\Facades\Mail::to($schedule->user->email)
            ->send(new \App\Mail\ScheduleConfirmation($schedule));
            
        return 'Test email sent successfully to ' . $schedule->user->email;
    } catch (\Exception $e) {
        return 'Error sending email: ' . $e->getMessage();
    }
});

// Registration
Route::get('/register', [RegisterController::class, 'create'])->name('register');
Route::post('/register', [RegisterController::class, 'store']);

// Login
Route::get('/login', [LoginController::class, 'create'])->name('login');
Route::post('/login', [LoginController::class, 'store']);
Route::post('/logout', [LoginController::class, 'destroy'])->name('logout');

// Profile routes (auth required)
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::post('/profile', [ProfileController::class, 'update'])->name('profile.update');
    
    // Vehicle routes (separate page)
    Route::get('/vehicles', [ProfileController::class, 'vehicles'])->name('vehicles.index');
    Route::post('/vehicles', [ProfileController::class, 'storeVehicle'])->name('vehicles.store');
    Route::delete('/vehicles/{vehicle}', [ProfileController::class, 'destroyVehicle'])->name('vehicles.destroy');
    
    // Schedule routes
    Route::resource('schedules', ScheduleController::class);
    Route::post('schedules/{schedule}/downpayment', [ScheduleController::class, 'processDownpayment'])->name('schedules.downpayment');
    Route::get('/schedules/{schedule}/pay-downpayment', [App\Http\Controllers\ScheduleController::class, 'payDownpayment'])->name('schedules.payDownpayment');
    Route::post('/schedules/{schedule}/pay-downpayment', [App\Http\Controllers\ScheduleController::class, 'submitDownpayment'])->name('schedules.submitDownpayment');
});

// Admin panel (basic) - protect with auth for now
Route::middleware('auth')->get('/admin', [\App\Http\Controllers\AdminController::class, 'index'])->name('admin.index');
Route::middleware('auth')->get('/admin/schedules/{schedule}', [\App\Http\Controllers\AdminController::class, 'show'])->name('admin.schedules.show');
Route::middleware('auth')->post('/admin/schedules/{schedule}/confirm', [\App\Http\Controllers\AdminController::class, 'confirm'])->name('admin.schedules.confirm');
Route::middleware('auth')->post('/admin/schedules/{schedule}/reject', [\App\Http\Controllers\AdminController::class, 'reject'])->name('admin.schedules.reject');
Route::middleware('auth')->post('/admin/schedules/{schedule}/complete', [\App\Http\Controllers\AdminController::class, 'complete'])->name('admin.schedules.complete');
Route::middleware('auth')->delete('/admin/schedules/{schedule}', [\App\Http\Controllers\AdminController::class, 'destroy'])->name('admin.schedules.destroy');

// Payment confirmation routes
Route::middleware('auth')->post('/admin/schedules/{schedule}/confirm-payment', [ScheduleController::class, 'confirmPayment'])->name('admin.schedules.confirm-payment');
Route::middleware('auth')->post('/admin/schedules/{schedule}/reject-payment', [ScheduleController::class, 'rejectPayment'])->name('admin.schedules.reject-payment');
