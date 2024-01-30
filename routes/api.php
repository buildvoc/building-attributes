<?php

use Illuminate\Http\Request;
use App\Models\ApiJsonResponse;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\PhilGeoController;
use App\Http\Controllers\API\SxDataController;
use App\Http\Controllers\API\ImageController;
use App\Http\Controllers\API\GalleryController;
use App\Http\Controllers\API\TopographyLayerSXController;
use App\Http\Controllers\API\BuildingPartController;

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

        Route::prefix('building-part')->group(function () {
            Route::get('/', [BuildingPartController::class, 'index'])->name('building_part.index');
            Route::get('/nearest', [BuildingPartController::class, 'nearest'])->name('building_part.nearest');
        });

        Route::get('sx-data', [SxDataController::class, 'index'])->name('sx_data.index');

        Route::prefix('layer-sx')->group(function () {
            Route::get('/', [TopographyLayerSXController::class, 'index'])->name('layer_sx.index');
            Route::get('/nearest', [TopographyLayerSXController::class, 'nearest'])->name('layer_sx.nearest');
        });

        Route::get('galleries', [GalleryController::class, 'index'])->name('gallery.index');
        Route::get('galleries/to-brick-collections-format', [GalleryController::class, 'toBrickCollections'])->name('gallery.toBrickCollections');
        // Route::get('galleries/sync', [GalleryController::class, 'sync'])->name('gallery.sync');
        Route::get('images', [ImageController::class, 'index'])->name('image.index');
        Route::get('images/to-brick-items-format', [ImageController::class, 'toBrickItems'])->name('image.toBrickItems');
        Route::get('images/sync', [ImageController::class, 'sync'])->name('image.sync');

    });
});
