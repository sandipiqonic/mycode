<?php

use Illuminate\Support\Facades\Route;
use Modules\User\Http\Controllers\API\UserController;

Route::group(['middleware' => 'auth:sanctum'], function () {

    Route::get('profile-details', [UserController::class, 'profileDetails']);
    Route::get('account-setting', [UserController::class, 'accountSetting']);
    Route::get('device-logout', [UserController::class, 'deviceLogout']);
    Route::get('logout-all', [UserController::class, 'logoutAll']);
    Route::get('delete-account', [UserController::class, 'deleteAccount']);

});
?>