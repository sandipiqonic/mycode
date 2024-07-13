<?php

namespace Modules\User\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\User\Transformers\UserProfileResource;
use App\Models\User;
use App\Models\Device;
use Modules\Subscriptions\Models\Subscription;
use Modules\Entertainment\Models\ContinueWatch;
use Modules\Entertainment\Models\Watchlist;
use Modules\Entertainment\Models\EntertainmentDownload;
use Modules\Entertainment\Models\UserReminder;
use Modules\User\Transformers\AccountSettingResource;
use App\Models\Role;

class UserController extends Controller
{
    public function profileDetails(Request $request){
        $userId = $request->user_id ? $request->user_id : auth()->user()->id;

        $user = User::with('subscriptionPackage', 'watchList', 'continueWatch')->where('id', $userId)->first();

        if($user->is_subscribe == 1){
            $user['plan_details'] = $user->subscriptionPackage;
        }
        
        $responseData = new UserProfileResource($user);

        return response()->json([
            'status' => true,
            'data' => $responseData,
            'message' => __('users.user_details'),
        ], 200);
    }

    public function accountSetting(Request $request){
        $userId = auth()->user()->id;

        $user = User::with('subscriptionPackage')->where('id', $userId)->first();

        $yourDevice = Device::where('user_id', $userId)->where('device_id', $request->device_id)->first();
        $otherDevice = Device::where('user_id', $userId)->where('device_id', '!=', $request->device_id)->get();
        
        $user['your_device'] = $yourDevice;
        $user['other_device'] = $otherDevice;

        if($user->is_subscribe == 1){
            $user['plan_details'] = $user->subscriptionPackage;
        }
        
        $responseData = new AccountSettingResource($user);

        return response()->json([
            'status' => true,
            'data' => $responseData,
            'message' => __('users.account_setting'),
        ], 200);
    }

    public function deviceLogout(Request $request){
        $userId = auth()->user()->id;

        $device = Device::where('user_id', $userId)->where('id', $request->device_id)->first();
        $device->delete();

        return response()->json([
            'status' => true,
            'message' => __('users.device_logout'),
        ], 200);
    }

    public function deleteAccount(Request $request){
        $userId = auth()->user()->id;

        User::where('id', $userId)->forceDelete();
        Device::where('user_id', $userId)->delete();
        Subscription::where('user_id', $userId)->delete();
        ContinueWatch::where('user_id', $userId)->delete();
        Watchlist::where('user_id', $userId)->delete();
        EntertainmentDownload::where('user_id', $userId)->delete();
        UserReminder::where('user_id', $userId)->delete();

        return response()->json([
            'status' => true,
            'message' => __('users.delete_account'),
        ], 200);
    }

    public function logoutAll(Request $request){
        $userId = auth()->user()->id;

        $device = Device::where('user_id', $userId)->delete();

        return response()->json([
            'status' => true,
            'message' => __('users.device_logout'),
        ], 200);
    }
}
