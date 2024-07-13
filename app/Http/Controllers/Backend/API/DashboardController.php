<?php

namespace App\Http\Controllers\Backend\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Auth;
use App\Models\MobileSetting;
use Modules\Entertainment\Models\Entertainment;
use Modules\Banner\Models\Banner;
use Modules\Entertainment\Models\ContinueWatch;
use App\Http\Resources\MobileSettingResource;
use Modules\Banner\Transformers\SliderResource;
use Modules\Entertainment\Transformers\ContinueWatchResource;

class DashboardController extends Controller
{

    public function DashboardDetail(Request $request)
    {
        $data = $request->all();

        $continueWatch = null;
        $user_id = null;

        if($request->has('user_id')){
            $user_id = $request->user_id;
            $continueWatchList = ContinueWatch::where('user_id', $user_id)->get();
            $continueWatch = ContinueWatchResource::collection($continueWatchList);
        }

        $sliderList = Banner::where('status', 1)->get();
        $sliders = SliderResource::collection($sliderList->map(function ($slider) use ($user_id) {
                        return new SliderResource($slider, $user_id);
                    }));
       
        $mobileSettings = MobileSetting::where('slug', '!=', 'continue-watching')->get()->filter(fn($item) => json_decode($item->value, true) != 0 && $item->value != 0)->sortBy('position');
        $sectionList = MobileSettingResource::collection($mobileSettings);
        
        $continueWatchValue = null;
        $continueWatchSetting = MobileSetting::where('slug', 'continue-watching')->first();
        if($continueWatchSetting){
            $continueWatchValue = intVal($continueWatchSetting->value);
        }
       
        $responseData = [
            'slider' => $sliders,
            'is_continue_watch' => $continueWatchValue,
            'continue_watch' => $continueWatch,
            'section_list' => $sectionList
        ];

        return response()->json([
            'status' => true,
            'data' => $responseData,
            'message' => __('messages.dashboard_detail'),
        ], 200);
    }
    
}
