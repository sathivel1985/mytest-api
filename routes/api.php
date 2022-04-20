<?php

use App\Http\Controllers\Api\Entry\EntryController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::prefix('object')->group(function () {
    Route::post('/', [EntryController::class, 'store'])->name('create-entry-object');
    Route::get('/get_all_records', [EntryController::class, 'index'])->name('get-all-entry-objects');
    Route::get('/{name}', [EntryController::class, 'show'])->name('get-entry');
});
