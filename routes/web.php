<?php

use App\Http\Controllers\LoanApplicationController;
use Illuminate\Support\Facades\Route;

Route::middleware(['consultant'])->group(function () {
    Route::get('applications', [LoanApplicationController::class, 'index'])
        ->name('applications');
    Route::get('applications/{id}', [LoanApplicationController::class, 'show'])
        ->name('applications.show');
    Route::patch('applications/{id}/status', [LoanApplicationController::class, 'updateStatus'])
        ->name('applications.updateStatus');
    Route::delete('applications/{id}', [LoanApplicationController::class, 'destroy'])
        ->name('applications.destroy');
});

Route::get('/', [LoanApplicationController::class, 'create'])
    ->name('apply');

Route::post('applications', [LoanApplicationController::class, 'store'])
    ->name('applications.store');

Route::post('/toggle-consultant', function () {
    $current = session('consultant_mode', false);
    session(['consultant_mode' => !$current]);

    return response()->json(['consultant_mode' => !$current]);
});
