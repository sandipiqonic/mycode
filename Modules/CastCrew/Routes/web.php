<?php

use Illuminate\Support\Facades\Route;
use Modules\CastCrew\Http\Controllers\CastCrewController;

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

// Route::group([], function () {
//     Route::resource('castcrew', CastCrewController::class)->names('castcrew');
// });


Route::get('app/castcrew/{type}', [CastCrewController::class, 'index'])->name('backend.castcrew.index');
Route::get('app/castcrew/create/{type}', [CastCrewController::class, 'create'])->name('backend.castcrew.create');
Route::post('app/castcrew/store', [CastCrewController::class, 'store'])->name('backend.castcrew.store');
Route::get('app/castcrew/{id}/edit', [CastCrewController::class, 'edit'])->name('backend.castcrew.edit');
Route::delete('app/castcrew/{id}', [CastCrewController::class, 'destroy'])->name('backend.castcrew.destroy');
Route::put('app/castcrew/update/{id}', [CastCrewController::class, 'update'])->name('backend.castcrew.update');

Route::group(['prefix' => 'app', 'as' => 'backend.', 'middleware' => ['auth']], function () {


       Route::group(['prefix' => '/castcrew', 'as' => 'castcrew.'], function () {
           Route::get('/index_list', [CastCrewController::class, 'index_list'])->name('index_list');
           Route::get('/index_data/{type}', [CastCrewController::class, 'index_data'])->name('index_data');
           Route::get('export', [CastCrewController::class, 'export'])->name('export');
           Route::get('/trashed', [CastCrewController::class, 'trashed'])->name('trashed');
           Route::get('/trashed/{id}', [CastCrewController::class, 'restore'])->name('restore');
           Route::post('bulk-action', [CastCrewController::class, 'bulk_action'])->name('bulk_action');
           Route::post('update-status/{id}', [CastCrewController::class, 'update_status'])->name('update_status');
           Route::delete('force-delete/{id}', [CastCrewController::class, 'forceDelete'])->name('force_delete');
           Route::post('restore/{id}', [CastCrewController::class, 'restore'])->name('restore');
            
       });


    //    Route::get('{type}', [CastCrewController::class, 'index'])->name('backend.castcrew.index');
    //    Route::get('{type}', [CastCrewController::class, 'create'])->name('castcrew.create');
    //    Route::post('castcrew/store', [CastCrewController::class, 'store'])->name('store');
    //    Route::resource('castcrew', CastCrewController::class)->names('castcrew');


});
