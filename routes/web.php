<?php

use App\Http\Controllers\FileUploadController;
use App\Http\Controllers\GuaranteeController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// Default route
Route::get('/', function () {
    return redirect()->route('home');
});

// Authentication routes (only once)
Auth::routes();

// Routes that require authentication
Route::middleware(['auth'])->group(function () {
    // Home route
    Route::get('/home', [HomeController::class, 'index'])->name('home');
    
    // Guarantee routes
    Route::resource('guarantees', GuaranteeController::class);
    
    // Additional guarantee actions
    Route::post('/guarantees/{guarantee}/submit-for-review', [GuaranteeController::class, 'submitForReview'])
        ->name('guarantees.submit-for-review');
    Route::post('/guarantees/{guarantee}/apply', [GuaranteeController::class, 'applyForGuarantee'])
        ->name('guarantees.apply');
    Route::post('/guarantees/{guarantee}/issue', [GuaranteeController::class, 'issueGuarantee'])
        ->name('guarantees.issue');
    Route::post('/guarantees/{guarantee}/reject', [GuaranteeController::class, 'rejectGuarantee'])
        ->name('guarantees.reject');
    
    // File upload routes - renamed to match your view files
    Route::get('/files', [FileUploadController::class, 'index'])->name('files.index');
    Route::get('/files/upload', [FileUploadController::class, 'create'])->name('files.create');
    Route::post('/files', [FileUploadController::class, 'store'])->name('files.store');
    Route::post('/files/{file}/process', [FileUploadController::class, 'process'])->name('files.process');
    Route::get('/files/{file}/download', [FileUploadController::class, 'download'])->name('files.download');
    Route::delete('/files/{file}', [FileUploadController::class, 'destroy'])->name('files.destroy');
});
