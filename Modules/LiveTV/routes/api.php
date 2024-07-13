<?php
use Illuminate\Support\Facades\Route;
use Modules\LiveTV\Http\Controllers\API\LiveTVsController;

Route::get('livetv-category-list', [LiveTVsController::class, 'liveTvCategoryList']);
Route::get('livetv-dashboard', [LiveTVsController::class, 'liveTvDashboard']);
Route::get('livetv-details', [LiveTVsController::class, 'liveTvDetails']);

Route::group(['middleware' => 'auth:sanctum'], function () {

});
?>


