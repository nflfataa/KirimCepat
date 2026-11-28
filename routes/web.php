<?php

use App\Http\Controllers\Courier;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ShipmentController;
use App\Http\Controllers\TrackController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

Route::get('/', HomeController::class)->name('home');

Route::get('/kirim', [ShipmentController::class, 'create'])->name('shipments.create');
Route::post('/kirim', [ShipmentController::class, 'store'])->name('shipments.store');
Route::get('/kirim/{shipment}', [ShipmentController::class, 'show'])
    ->middleware('signed')
    ->name('shipments.show');

Route::get('/lacak', [TrackController::class, 'index'])->name('track.index');
Route::get('/lacak/{resi}', [TrackController::class, 'show'])->name('track.show');

Route::get('/kurir/masuk', [Courier\AuthController::class, 'create'])
    ->middleware('guest')
    ->name('kurir.masuk');
Route::post('/kurir/masuk', [Courier\AuthController::class, 'store'])
    ->middleware(['guest', 'throttle:6,1'])
    ->name('kurir.masuk.store');
Route::post('/kurir/keluar', [Courier\AuthController::class, 'destroy'])
    ->middleware('auth')
    ->name('kurir.keluar');

Route::middleware('auth')->prefix('kurir')->name('kurir.')->group(function () {
    Route::get('/', [Courier\ShipmentController::class, 'index'])->name('dashboard');
    Route::patch('/pengiriman/{shipment}/status', [Courier\ShipmentController::class, 'update'])
        ->name('shipments.status');
});

Route::get('/health', function (Request $request) {
    $dbStatus = 'disconnected';
    $message = null;
    $httpCode = 200;

    try {
        DB::connection()->getPdo();
        $dbStatus = 'connected';
    } catch (Exception $e) {
        $httpCode = 500;
        $message = $e->getMessage();
    }

    $data = [
        'status' => $dbStatus === 'connected' ? 'ok' : 'error',
        'database' => $dbStatus,
        'timestamp' => now()->toIso8601String(),
    ];
    if ($message) {
        $data['message'] = $message;
    }

    if ($request->wantsJson()) {
        return response()->json($data, $httpCode);
    }

    return response()->view('health', ['health' => $data, 'httpCode' => $httpCode], $httpCode);
});
