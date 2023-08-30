<?php

use Illuminate\Http\Request;
use App\Models\ApiJsonResponse;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\PhilGeoController;
use App\Http\Controllers\API\SxDataController;
use App\Http\Controllers\API\ImageController;
use App\Http\Controllers\API\GalleryController;

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

Route::fallback(fn (Request $request) => ApiJsonResponse::sendErrors(['message' => ['Server Error Found!'], 'url' => $request->fullUrl()], 500));

Route::any("/*{any}", fn () => ApiJsonResponse::sendErrors(['message' => ['Page Not Found']], 404));

Route::middleware(['cors', 'json.response'])->group(function () {
    Route::prefix('v1')->group(function () {
        Route::prefix('geo')->group(function () {
            Route::get('/', [PhilGeoController::class, 'index'])->name('geo.index');
            Route::get('/nearest', [PhilGeoController::class, 'nearest'])->name('geo.nearest');
            Route::post('/', [PhilGeoController::class, 'store'])->name('geo.store');
            Route::post('/upload', [PhilGeoController::class, 'storeAsJSONFile'])->name('geo.storeAsJSONFile');
        });

        Route::get('sx-data', [SxDataController::class, 'index'])->name('sx_data.index');

        Route::get('galleries', [GalleryController::class, 'index'])->name('gallery.index');
        Route::get('images', [ImageController::class, 'index'])->name('image.index');

    });
});
