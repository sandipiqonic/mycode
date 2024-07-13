<?php

use Illuminate\Support\Facades\Route;
use Modules\Entertainment\Http\Controllers\Backend\EntertainmentsController;
use Modules\Entertainment\Http\Controllers\Backend\MovieController;
use Modules\Entertainment\Http\Controllers\Backend\TVshowController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
/*
*
* Backend Routes
*
* --------------------------------------------------------------------
*/
Route::group(['prefix' => 'app', 'as' => 'backend.', 'middleware' => ['auth']], function () {
    /*
    * These routes need view-backend permission
    * (good if you want to allow more than one group in the backend,
    * then limit the backend features by different roles or permissions)
    *
    * Note: Administrator has all permissions so you do not have to specify the administrator role everywhere.
    */

    /*
     *
     *  Backend Entertainments Routes
     *
     * ---------------------------------------------------------------------
     */

    Route::group(['prefix' => 'entertainments', 'as' => 'entertainments.'],function () {
      Route::get("index_list", [EntertainmentsController::class, 'index_list'])->name("index_list");
      Route::get("index_data", [EntertainmentsController::class, 'index_data'])->name("index_data");
      Route::get('export', [EntertainmentsController::class, 'export'])->name('export');
      Route::post('bulk-action', [EntertainmentsController::class, 'bulk_action'])->name('bulk_action');
      Route::post('restore/{id}', [EntertainmentsController::class, 'restore'])->name('restore');
      Route::post('update-status/{id}', [EntertainmentsController::class, 'update_status'])->name('update_status');
      Route::delete('force-delete/{id}', [EntertainmentsController::class, 'forceDelete'])->name('force_delete');
      Route::get('download-option/{id}', [EntertainmentsController::class, 'downloadOption'])->name('download-option');
      Route::Post('store-downloads/{id}', [EntertainmentsController::class, 'storeDownloads'])->name('store-downloads');
      Route::get('details/{id}', [EntertainmentsController::class, 'details'])->name("details");

    });
    Route::resource("entertainments", EntertainmentsController::class);

    
    Route::group(['prefix' => 'movies', 'as' => 'movies.'],function () {
      Route::get("index_list", [MovieController::class, 'index_list'])->name("index_list");
      Route::get('export', [MovieController::class, 'export'])->name('export');
      Route::get("index_data", [MovieController::class, 'index_data'])->name("index_data");
      Route::get('/import-movie/{id}', [MovieController::class, 'ImportMovie'])->name('import-movie');

    });
    Route::resource("movies", MovieController::class);

    Route::group(['prefix' => 'tvshows', 'as' => 'tvshows.'],function () {
      Route::get("index_list", [TVshowController::class, 'index_list'])->name("index_list");
      Route::get('export', [TVshowController::class, 'export'])->name('export');
      Route::get("index_data", [TVshowController::class, 'index_data'])->name("index_data");
      Route::post('bulk-action', [TVshowController::class, 'bulk_action'])->name('bulk_action');
      Route::get('/import-tvshow/{id}', [TVshowController::class, 'ImportTVshow'])->name('import-tvshow');
    });
    Route::resource("tvshows", TVshowController::class);
});



