<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Modules\Constant\Models\Constant;
use Modules\Service\Models\Service;
use Modules\World\Models\Country;
use Modules\World\Models\State;
use Modules\World\Models\City;
use Modules\LiveTV\Models\LiveTvCategory;
use Modules\Genres\Models\Genres;
use Modules\Entertainment\Models\Entertainment;
use Modules\Season\Models\Season;
use Modules\Episode\Models\Episode;
use Modules\Video\Models\Video;
use Modules\CastCrew\Models\CastCrew;
use Modules\Subscriptions\Models\Plan;
use Modules\Subscriptions\Models\PlanLimitation;
use Modules\Tax\Models\Tax;

class SearchController extends Controller
{
    public function get_search_data(Request $request)
    {
        $is_multiple = isset($request->multiple) ? explode(',', $request->multiple) : null;
        if (isset($is_multiple) && count($is_multiple)) {
            $multiplItems = [];
            foreach ($is_multiple as $key => $value) {
                $multiplItems[$key] = $this->getData(collect($request[$value]));
            }

            return response()->json(['status' => 'true', 'results' => $multiplItems]);
        } else {
            return response()->json(['status' => 'true', 'results' => $this->getData($request->all())]);
        }
    }

    // case 'users':
    // select('id as $key','name as $value')
    // select(\DB::raw("value $key,label $value"))
    // if($keyword != ''){
    //   $items->where('category_name', 'LIKE', $keyword.'%');
    // }
    //   break;
    protected function getData($request)
    {
        $items = [];

        $type = $request['type'];
        $sub_type = $request['sub_type'] ?? null;

        $keyword = $request['q'] ?? null;

        switch ($type) {

          

                case 'country':

                    $items = Country::select('id', 'name as text');

                   
                    if ($keyword != '') {

                        $items->where('name', 'LIKE', '%'.$keyword.'%');
                    }
    
                    $items = $items->get();
                    break;

                case 'state':  
                    
                    $items = State::select('id', 'name as text');

                    if($sub_type != null){

                        $items=State::where('country_id',$sub_type)->select('id', 'name as text');
                    }

                    if ($keyword != '') {

                        $items->where('name', 'LIKE', '%'.$keyword.'%');
                    }
    
                    $items = $items->get();
                    break;  
                    
                case 'city':  
                
                    $items = City::select('id', 'name as text');

                    if($sub_type != null){

                        $items=City::where('state_id',$sub_type)->select('id', 'name as text');
                    }

                    if ($keyword != '') {

                        $items->where('name', 'LIKE', '%'.$keyword.'%');
                    }
    
                    $items = $items->get();
                    break;   

               
            case 'customers':
                $items = User::role('user')->select('id', \DB::raw("CONCAT(first_name,' ',last_name) AS text"));
                if ($keyword != '') {
                    $items->where(\DB::raw("CONCAT(first_name, ' ', last_name)"), 'LIKE', '%'.$keyword.'%');
                }
                $items = $items->limit(50)->get();
                break;
          
            case 'earning_payment_method':
                $query = Constant::getAllConstant()
                    ->where('type', 'EARNING_PAYMENT_TYPE');
                foreach ($query as $key => $data) {
                    if ($keyword != '') {
                        if (strpos($data->name, str_replace(' ', '_', strtolower($keyword))) !== false) {
                            $items[] = [
                                'id' => $data->name,
                                'text' => $data->value,
                            ];
                        }
                    } else {
                        $items[] = [
                            'id' => $data->name,
                            'text' => $data->value,
                        ];
                    }
                }
                break;

           

            case 'time_zone':
                $items = timeZoneList();

                // foreach ($items as $k => $v) {

                //    if($value !=''){
                //         if (strpos($v, $value) !== false) {

                //         }else{
                //              unset($items[$k]);
                //         }
                //    }
                // }

                $data = [];
                $i = 0;
                foreach ($items as $key => $row) {
                    $data[$i] = [
                        'id' => $key,
                        'text' => $row,
                    ];

                    $i++;
                }

                $items = $data;

                break;

            case 'additional_permissions':
                $query = Constant::getAllConstant()
                    ->where('type', 'additional_permissions');
                foreach ($query as $key => $data) {
                    if ($keyword != '') {
                        if (strpos($data->name, str_replace(' ', '_', strtolower($keyword))) !== false) {
                            $items[] = [
                                'id' => $data->name,
                                'text' => $data->value,
                            ];
                        }
                    } else {
                        $items[] = [
                            'id' => $data->name,
                            'text' => $data->value,
                        ];
                    }
                }

                break;

            case 'constant':
                $query = Constant::getAllConstant()->where('type', $sub_type);
                foreach ($query as $key => $data) {
                    if ($keyword != '') {
                        if (strpos($data->name, str_replace(' ', '_', strtolower($keyword))) !== false) {
                            $items[] = [
                                'id' => $data->name,
                                'text' => $data->value,
                            ];
                        }
                    } else {
                        $items[] = [
                            'id' => $data->name,
                            'text' => $data->value,
                        ];
                    }
                }

                break;

            case 'role':
                $query = Role::all();
                foreach ($query as $key => $data) {
                    if ($keyword != '') {
                        if (strpos($data->name, str_replace(' ', '_', strtolower($keyword))) !== false) {
                            $items[] = [
                                'id' => $data->id,
                                'text' => $data->name,
                            ];
                        }
                    } else {
                        $items[] = [
                            'id' => $data->id,
                            'text' => $data->name,
                        ];
                    }
                }

                break;
            
           
            case 'tv-category':
                $items = LiveTvCategory::select('id', 'name as text');
                if ($keyword != '') {
                    $items->where('name', 'LIKE', '%'.$keyword.'%');
                }
    
                $items = $items->limit(50)->get();
                break;

        }

        return $items;
    }
    public function check_in_trash(Request $request)
    {
        $ids = $request->ids;
        $type = $request->datatype;

        switch($type){
            case 'tvcategory': 
                $InTrash = LiveTvCategory::withTrashed()->whereIn('id', $ids)->whereNotNull('deleted_at')->get();
            break;
            case 'genres': 
                $InTrash = Genres::withTrashed()->whereIn('id', $ids)->whereNotNull('deleted_at')->get();
            break;
            case 'entertainment': 
                $InTrash = Entertainment::withTrashed()->whereIn('id', $ids)->whereNotNull('deleted_at')->get();
            break;
            case 'season': 
                $InTrash = Season::withTrashed()->whereIn('id', $ids)->whereNotNull('deleted_at')->get();
            break;
            case 'episode': 
                $InTrash = Episode::withTrashed()->whereIn('id', $ids)->whereNotNull('deleted_at')->get();
            break;
            case 'video': 
                $InTrash = Video::withTrashed()->whereIn('id', $ids)->whereNotNull('deleted_at')->get();
            break;
            case 'cast-crew': 
                $InTrash = CastCrew::withTrashed()->whereIn('id', $ids)->whereNotNull('deleted_at')->get();
            break;
            case 'plan': 
                $InTrash = Plan::withTrashed()->whereIn('id', $ids)->whereNotNull('deleted_at')->get();
            break;
            case 'plan-limitation': 
                $InTrash = PlanLimitation::withTrashed()->whereIn('id', $ids)->whereNotNull('deleted_at')->get();
            break;
            case 'users': 
                $InTrash = User::withTrashed()->whereIn('id', $ids)->whereNotNull('deleted_at')->get();
            break;
            case 'taxes': 
                $InTrash = Tax::withTrashed()->whereIn('id', $ids)->whereNotNull('deleted_at')->get();
            break;
            default:
            break;
        }
        
        if (count($InTrash) === count($ids)) {
            return response()->json(['all_in_trash' => true]);
        }

        return response()->json(['all_in_trash' => false]);
    }
}
