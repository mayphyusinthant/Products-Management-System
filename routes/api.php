<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ItemApiController;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::get('items/filter', [ItemApiController::class, 'filter'])->name('items.filter');;
Route::get('items/search', [ItemApiController::class, 'search'])->name('items.search');;
Route::apiResource('items', ItemApiController::class)->except(['show']);
Route::get('items/show/{id}', [ItemApiController::class, 'show'])->name('items.show');;
