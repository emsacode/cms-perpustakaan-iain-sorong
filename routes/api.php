<?php

use App\Http\Controllers\Api\v1\CatalogController;
use App\Http\Controllers\Api\v1\ContentController;
use App\Http\Controllers\Api\v1\TransactionController;
use Illuminate\Support\Facades\Route;

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

Route::prefix('v1')->group(function () {
    // Read-only APIs (cached & rate limited to 100 requests/minute)
    Route::middleware('throttle:api')->group(function () {
        // ETL Catalog Records
        Route::get('/catalog', [CatalogController::class, 'index']);

        // CMS Content Retrieval
        Route::get('/posts', [ContentController::class, 'posts']);
        Route::get('/posts/{slug}', [ContentController::class, 'postDetail']);
        Route::get('/epapers', [ContentController::class, 'epapers']);
        Route::get('/newspapers', [ContentController::class, 'newspapers']);
        Route::get('/faqs', [ContentController::class, 'faqs']);
        Route::get('/galleries', [ContentController::class, 'galleries']);
        Route::get('/service-hours', [ContentController::class, 'serviceHours']);
        Route::get('/pages', [ContentController::class, 'pages']);
        Route::get('/pages/{slug}', [ContentController::class, 'pageDetail']);
        Route::get('/podcasts', [ContentController::class, 'podcasts']);
    });

    // Transaction & Feedback submissions (throttled to 5 requests/minute)
    Route::middleware('throttle:form-submissions')->group(function () {
        Route::post('/reservations', [TransactionController::class, 'reserveRoom']);
        Route::post('/desiderata', [TransactionController::class, 'submitDesiderata']);
        Route::post('/surveys', [TransactionController::class, 'submitSurvey']);
        Route::post('/clearances', [TransactionController::class, 'submitClearance']);
    });
});
