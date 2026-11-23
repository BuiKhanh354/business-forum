<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CategoryController;

/*
|--------------------------------------------------------------------------
| API Routes - KẾT NỐI Business Community
|--------------------------------------------------------------------------
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// API Danh mục (Hỗ trợ Tạo, Lấy danh sách, Xóa chuyên mục)
Route::get('/danh-muc', [CategoryController::class, 'index']);
Route::post('/danh-muc', [CategoryController::class, 'store']);
Route::delete('/danh-muc/{id}', [CategoryController::class, 'destroy']);
