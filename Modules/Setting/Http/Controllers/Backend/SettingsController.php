<?php

namespace Modules\Setting\Http\Controllers\Backend;

use App\Authorizable;
use App\Http\Controllers\Controller;
use Modules\Setting\Models\Setting;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Modules\Setting\Http\Requests\SettingRequest;
use App\Trait\ModuleTrait;
use Illuminate\Support\Facades\Config;
use Modules\NotificationTemplate\Models\NotificationTemplate;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class SettingsController extends Controller
{
    protected string $exportClass = '\App\Exports\SettingExport';

    use ModuleTrait {
        initializeModuleTrait as private traitInitializeModuleTrait;
    }

    public function __construct()
    {
        $this->traitInitializeModuleTrait(
            'setting.title', // module title
            'settings', // module name
            'fa-solid fa-clipboard-list' // module icon
        );
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */

    public function index(Request $request)
    {
        $filter = [
            'status' => $request->status,
        ];

        $module_action = 'List';

        $export_import = true;
        $export_columns = [
            [
                'value' => 'name',
                'text' => ' Name',
            ]
        ];
        $export_url = route('backend.settings.export');

        return view('setting::backend.setting.index', compact('module_action', 'filter', 'export_import', 'export_columns', 'export_url'));
    }


    public function generalSetting()
    {
        $fields = ['app_name', 'user_app_name', 'helpline_number', 'inquriy_email', 'short_description', 'logo', 'mini_logo', 'dark_logo', 'dark_mini_logo', 'favicon'];
        $data = $this->fieldsData($fields);

        return view('setting::backend.setting.section-pages.general', compact('data'));
    }
    public function moduleSetting()
    {
        $fields = [''];
        $data = $this->fieldsData($fields);
        return view('setting::backend.setting.section-pages.module-setting', compact('data'));
    }
    public function customCode()
    {
        $fields = ['custom_css_block', 'custom_js_block'];
        $data = $this->fieldsData($fields);
        return view('setting::backend.setting.section-pages.custom-code', compact('data'));
    }

    public function invoiceSetting()
    {
        $fields = ['inv_prefix', 'order_code_start', 'spacial_note'];
        $data = $this->fieldsData($fields);

        return view('setting::backend.setting.section-pages.invoice-setting', compact('data'));
    }

    public function customization()
    {
        $fields = ['customization_setting_1', 'customization_setting_2'];

        $data = $this->fieldsData($fields);
        return view('setting::backend.setting.section-pages.customization', compact('data'));
    }

    public function mail()
    {
        $fields = ['email', 'mail_driver', 'mail_host', 'mail_port', 'mail_encryption', 'mail_username', 'mail_from', 'from_name', 'mail_password'];
        $data = $this->fieldsData($fields);

        return view('setting::backend.setting.section-pages.mail-setting', compact('data'));
    }

    public function notificationSetting()
    {

        $query_data = NotificationTemplate::with('defaultNotificationTemplateMap', 'constant')->get();
        $notificationTemplates = [];
        $notificationKeyChannels = array_keys(config('notificationtemplate.channels'));
        $arr = [];
        // For Channel Map Or Update Channel Value
        foreach ($notificationKeyChannels as $key => $value) {
            $arr[$value] = 0;
        }
        foreach ($query_data as $key => $value) {
            $notificationTemplates[$key] = [
                'id' => $value->id,
                'type' => $value->type,
                'template' => $value->defaultNotificationTemplateMap->subject,
                'is_default' => false,
            ];
            if (isset($value->channels)) {
                $notificationTemplates[$key]['channels'] = $value->channels;
            } else {
                $notificationTemplates[$key]['channels'] = $arr;
            }
        }

        $channels = config('notificationtemplate.channels');
        return view('setting::backend.setting.section-pages.notification-setting', compact('channels', 'notificationTemplates'));
    }

    public function integration()
    {
        $fields = [
            'is_google_login',
            'is_one_signal_notification',
            'is_mobile_notification',
            'is_map_key',
            'isForceUpdate',
            'is_application_link',
            'is_custom_webhook_notification',
            'onesignal_app_id',
            'onesignal_rest_api_key',
            'onesignal_channel_id',
            'custom_webhook_content_key',
            'custom_webhook_url'
            ,
            'customer_app_play_store',
            'customer_app_app_store',
            'google_maps_key',
            'version_code',
            'google_secretkey',
            'google_publickey',
        ];
        $data = $this->fieldsData($fields);
        return view('setting::backend.setting.section-pages.integration', compact('data'));
    }

    public function otherSettings()
    {
        $fields = ['is_event', 'is_blog', 'is_user_push_notification', 'is_provider_push_notification', 'enable_chat_gpt', 'test_without_key', 'chatgpt_key', 'firebase_notification', 'firebase_key',];
        $settings = $this->fieldsData($fields);

        return view('setting::backend.setting.section-pages.other-settings', compact('settings'));
    }

    public function paymentMethod()
    {
        $fields = ['razor_payment_method', 'razorpay_secretkey', 'razorpay_publickey', 'str_payment_method', 'stripe_secretkey', 'stripe_publickey', 'paystack_payment_method', 'paystack_secretkey', 'paystack_publickey', 'paypal_payment_method', 'paypal_secretkey', 'paypal_clientid', 'flutterwave_payment_method', 'flutterwave_secretkey', 'flutterwave_publickey'];
        $settings = $this->fieldsData($fields);
        return view('setting::backend.setting.section-pages.payment-method', compact('settings'));
    }

    public function languageSettings()
    {
        $query_data = Config::get('app.available_locales');
        $languages = [];
        foreach ($query_data as $key => $value) {
            $languages[] = [
                'id' => $key,
                'name' => $value,
            ];
        }
        $fields = ['language_setting_1', 'language_setting_2'];
        $data = $this->fieldsData($fields);
        return view('setting::backend.setting.section-pages.language-settings', compact('data', 'languages'));
    }

    public function notificationConfiguration()
    {
        $fields = ['expiry_plan', 'upcoming', 'continue_watch'];
        $settings = $this->fieldsData($fields);
        return view('setting::backend.setting.section-pages.notification-configuration', compact('settings'));
    }



    public function miscSetting()
    {
        $query_data = Config::get('app.available_locales');
        $languages = [];

        foreach ($query_data as $key => $value) {
            $languages[] = [
                'id' => $key,
                'name' => $value,
            ];
        }
        $timezones = timeZoneList();
        $data = [];
        $i = 0;
        foreach ($timezones as $key => $row) {
            $data[$i] = [
                'id' => $key,
                'text' => $row,
            ];
            $i++;
        }
        $timezones = $data;

        $fields = ['google_analytics','default_language','default_time_zone','data_table_limit','default_currency','AWS_ACCESS_KEY_ID','AWS_SECRET_ACCESS_KEY','AWS_DEFAULT_REGION','AWS_BUCKET','TMDB_API_KEY','AWS_USE_PATH_STYLE_ENDPOINT'];
        $settings = $this->fieldsData($fields);
        return view('setting::backend.setting.section-pages.misc-settings', compact('settings', 'languages', 'timezones'));
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return Response
     */


     public function store(Request $request)
     {
         $rules = Setting::getSelectedValidationRules(array_keys($request->all()));
         $data = $this->validate($request, $rules);
     
         $validSettings = array_keys($rules);
     
         foreach ($data as $key => $val) {
             if (in_array($key, $validSettings)) {
                 $mimeTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/gif', 'image/vnd.microsoft.icon'];
                 if (gettype($val) == 'object') {
                     if ($val->getType() == 'file' && in_array($val->getmimeType(), $mimeTypes)) {
                         $setting = Setting::add($key, '', Setting::getDataType($key), Setting::getType($key));
                         $mediaItems = $setting->addMedia($val)->toMediaCollection($key);
                         $setting->update(['val' => $mediaItems->getUrl()]);
                     }
                 } else {
                     $setting = Setting::add($key, $val, Setting::getDataType($key), Setting::getType($key));
                 }
             }
         }
     
         if ($request->wantsJson()) {
             $message = __('settings.save_setting');
             return response()->json(['message' => $message, 'status' => true], 200);
         } else {
             return redirect()->back()->with('status', __('messages.setting_save'));
         }
     }
     

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        $data = Setting::findOrFail($id);
        return view('setting::backend.setting.edit', compact('data'));

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  int  $id
     * @return Response
     */
    public function update(SettingRequest $request, Setting $setting)
    {
        $requestData = $request->all();
        $setting->update($requestData);

        return redirect()->route('backend.settings.index', $setting->id)->with('success', 'Setting Updated Successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */

    public function destroy($id)
    {
        $data = Setting::findOrFail($id);
        $data->delete();
        $message = __('Taxes Deleted Successfully');
        return response()->json(['message' => $message, 'type' => 'DELETE_FORM']);
    }

    public function restore($id)
    {
        $data = Setting::withTrashed()->findOrFail($id);
        $data->restore();
        return response()->json(['message' => 'Tax entry restored successfully']);
    }

    public function forceDelete($id)
    {
        $data = Setting::withTrashed()->findOrFail($id);
        $data->forceDelete();
        return response()->json(['message' => 'Tax entry permanently deleted']);
    }

    public function clear_cache()
    {
        Setting::flushCache();

        $message = __('messages.cache_cleard');

        return response()->json(['message' => $message, 'status' => true], 200);
    }

    public function fieldsData($fields)
    {
        $responseData = Setting::whereIn('name', $fields)->get();
        $data = [];
        foreach ($responseData as $setting) {
            $field = $setting->name;
            $value = $setting->val;

            // Process specific fields, like asset URLs
            if (in_array($field, ['logo', 'mini_logo', 'dark_logo', 'dark_mini_logo', 'favicon'])) {
                $value = asset($value);
            }

            $data[$field] = $value;

        }

        return $data;
    }

    public function change_password()
    {

        return view('setting::backend.profile.section-pages.change-password');

    }

    public function information()
    {
        $user = Auth::user();

        return view('setting::backend.profile.section-pages.information-page', compact('user'));
    }

    public function userProfileUpdate(Request $request)
    {
        // dd($request->all());
        $user = Auth::user();
        $data = User::findOrFail($user->id);
        $request_data = $request->except('profile_image');
        $data->update($request_data);

        if ($request->custom_fields_data) {
            $data->updateCustomFieldData(json_decode($request->custom_fields_data));
        }

        if ($request->file('profile_image')) {
            storeMediaFile($data, $request->file('profile_image'), 'profile_image');
        }

        $message = __('messages.update_form', ['form' => __('customer.singular_title')]);

        return redirect()->back()->with('success', 'profile updated');
    }

    public function changePassword(Request $request)
    {
        if (env('IS_DEMO')) {
            return redirect()->back()->with('error', __('messages.permission_denied'));
        }

        $user = Auth::user(); // Get the currently authenticated user
        $user_id = $user->id; // Retrieve the user's ID
        $data = User::findOrFail($user_id);
        $request_data = $request->only('old_password', 'new_password', 'confirm_password');

        if (!Hash::check($request->old_password, $data->password)) {
            return redirect()->back()->with('error', __('messages.old_password_mismatch'));
        }

        if ($request_data['new_password'] === $request_data['old_password']) {
            return redirect()->back()->with('error', __('messages.new_password_mismatch'));
        }

        if ($request_data['new_password'] !== $request_data['confirm_password']) {
            return redirect()->back()->with('error', __('messages.password_mismatch'));
        }

        $request_data['password'] = Hash::make($request_data['new_password']);
        $data->update(['password' => $request_data['password']]);

        $message = __('messages.password_update');
        return redirect()->back()->with('success', $message);
    }


    // App config
    public function appConfig(Request $request)
    {
        $module_title = __('settings.add_title');
        $module_action = 'List';
        $fields = ['is_social_login', 'is_google_login', 'is_otp_login', 'is_apple_login', 'is_firebase_notification', 'firebase_key', 'is_user_push_notification', 'is_application_link', 'ios_url', 'android_url', 'force_update', 'enter_app_version', 'app_version', 'message_text'];
        $data = $this->fieldsData($fields);
        return view('setting::backend.appconfig.index', compact('module_action', 'data','module_title'));
    }

}