<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Services\ApiStatusService;

Route::get('/', function (ApiStatusService $apiStatusService) {
    return response()->json($apiStatusService->getApiStatus());
});

Route::get('/test_mongo', function () {
    $connection = DB::connection('mongodb');
    $msg= 'ok';
    try {
        $connection->command(['ping' => 1]);
    } catch (Exception $error) {
        $msg = $error->getMessage();
    }

    return $msg;
});

Route::get('/products', [ProductController::class, 'index']);
Route::get('/products/{code}', [ProductController::class, 'show']);
Route::put('/products/{code}', [ProductController::class, 'update']);
Route::delete('/products/{code}', [ProductController::class, 'destroy']);

