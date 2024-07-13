<?php

use Illuminate\Support\Facades\Route;
use Modules\Setting\Http\Controllers\Backend\SettingsController;



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
     *  Backend Settings Routes
     *
     * ---------------------------------------------------------------------
     */

    Route::group(['prefix' => 'setting', 'as' => 'settings.'],function () {
      Route::get("index_list", [SettingsController::class, 'index_list'])->name("index_list");
      Route::get("index_data", [SettingsController::class, 'index_data'])->name("index_data");
      Route::get('export', [SettingsController::class, 'export'])->name('export');
      Route::get('settings/{' . 'settings' . '}/edit', [SettingsController::class, 'edit'])->name('edit');
      Route::post('bulk-action', [SettingsController::class, 'bulk_action'])->name('bulk_action');
      Route::post('restore/{id}', [SettingsController::class, 'restore'])->name('restore');
      Route::delete('force-delete/{id}', [SettingsController::class, 'forceDelete'])->name('force_delete');
      Route::get('clear-cache', [SettingsController::class, 'clear_cache'])->name('clear-cache');
      Route::get('module-setting', [SettingsController::class, 'moduleSetting'])->name('module');
      Route::get('custom-code', [SettingsController::class, 'customCode'])->name('custom-code');

      Route::get('general-setting', [SettingsController::class, 'generalSetting'])->name('general');
      Route::get('invoice-setting', [SettingsController::class, 'invoiceSetting'])->name('invoice-setting');
      Route::get('customization', [SettingsController::class, 'customization'])->name('customization');
      Route::get('mail', [SettingsController::class, 'mail'])->name('mail');
      Route::get('notificationsetting', [SettingsController::class, 'notificationSetting'])->name('notificationsetting');
      Route::get('integration', [SettingsController::class, 'integration'])->name('integration');
      Route::get('custom-fields', [SettingsController::class, 'customFields'])->name('custom-fields');
      Route::get('currency-settings', [SettingsController::class, 'currencySettings'])->name('currency-settings');
      Route::get('commission', [SettingsController::class, 'commission'])->name('commission');
      Route::get('payment-method', [SettingsController::class, 'paymentMethod'])->name('payment-method');
      Route::get('language-settings', [SettingsController::class, 'languageSettings'])->name('language-settings');
      Route::get('telemedservice-settings', [SettingsController::class, 'telemedserviceSettings'])->name('telemedservice-settings');
      Route::get('misc-setting', [SettingsController::class, 'miscSetting'])->name('misc'); // Define the missing route for 'misc' setting
      Route::get('other-settings', [SettingsController::class, 'otherSettings'])->name('other-settings');
      Route::get('notification-configuration', [SettingsController::class, 'notificationConfiguration'])->name('notification-configuration');
      

    });

    Route::group(['prefix' => 'profile', 'as' => 'profile.'],function () {
        Route::get('change-password', [SettingsController::class, 'change_password'])->name('change-password');
        Route::get('information', [SettingsController::class, 'information'])->name('information');
        Route::post('information-update', [SettingsController::class, 'userProfileUpdate'])->name('information-update');
        Route::post('change-password', [SettingsController::class, 'changePassword'])->name('change_password');
    });
    Route::resource("setting", SettingsController::class);

    route::get('appconfig',[SettingsController::class,'appConfig'])->name('AppConfig.index');

});



