<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Trait\ModuleTrait;
use App\Models\MobileSetting;
use App\Http\Requests\MobileSettingRequest;
use Modules\Entertainment\Models\Entertainment;
use Modules\Entertainment\Models\EntertainmentView;
use Illuminate\Support\Facades\DB;
use Modules\Constant\Models\Constant;
use Modules\LiveTV\Models\LiveTvChannel;
use Modules\CastCrew\Models\CastCrew;
use Modules\Genres\Models\Genres;
use Illuminate\Support\Str;

class MobileSettingController extends Controller
{
    use ModuleTrait {
        initializeModuleTrait as private traitInitializeModuleTrait;
        }

    public function __construct()
    {
        // Page Title
        $this->module_title = 'settings.mobile_setting';

        // module name
        $this->module_name = 'mobile-setting';

        $this->module_icon = 'fas fa-cogs';

        $this->traitInitializeModuleTrait(
            'settings.mobile_setting', // module title
            'mobile-setting', // module name
            'fas fa-cogs' // module icon
        );
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $module_action = 'List';

        $data = MobileSetting::orderBy('position', 'asc')->get();
        return view('backend.mobile-setting.index', compact('module_action', 'data'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(MobileSettingRequest $request)
    {
        $data = $request->all();

        if($request->dashboard_select){
            $data['value'] = json_encode($request->dashboard_select);

        }

        $result = MobileSetting::updateOrCreate(['id' => $request->id], $data);
        if($result->slug == 'banner' || $result->slug == 'continue-watching'){
            $result->value = 1;
            $result->save();
        }

        if($result->wasRecentlyCreated){
            $result['slug'] = strtolower(Str::slug($result->name, '-'));
            $result->save();

            if($result->slug == 'banner' || $result->slug == 'continue-watching' || $result->slug == 'rate-our-app'){
                $result->value = 1;
                $result->save();
            }

            $message = __('messages.create_form', ['form' => __($this->module_title)]);
        }

        $message = __('messages.update_form', ['form' => __($this->module_title)]);

        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => $message]);
        }

        return redirect()->route('backend.mobile-setting.index')->with('success', $message);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $data = MobileSetting::where('id',$id)->first();
        return response()->json($data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $data = MobileSetting::where('id',$id)->first();

        $data->delete();
        $message = trans('messages.delete_form', ['form' => __($this->module_title)]);

        return response()->json(['message' => $message, 'status' => true], 200);
    }

    public function getDropdownValue(string $id)
    {
        $data = MobileSetting::where('id',$id)->first();
        $slug = $data->slug;

        $selectedIds = json_decode($data->value, true);

        $selected_values = null;

        $value = null;
        switch($slug){
            case 'top-10':
                $topEntertainmentIds = EntertainmentView::groupBy('entertainment_id')
                                        ->select('entertainment_id', DB::raw('count(*) as total'))
                                        ->orderBy('total', 'desc')
                                        ->take(10)
                                        ->pluck('entertainment_id');
                $value = Entertainment::whereIn('id', $topEntertainmentIds)->get();

                if (!empty($selectedIds)) {
                    $selected_values = Entertainment::whereIn('id', $selectedIds)->get();
                }
                break;
            case 'latest-movies':
                $value = Entertainment::where('type', 'movie')
                                        ->whereDate('release_date', '<', now())
                                        ->orderBy('release_date', 'desc')
                                        ->take(10)
                                        ->get();

                if (!empty($selectedIds)) {
                    $selected_values = Entertainment::whereIn('id', $selectedIds)->get();
                }
                break;
            case 'enjoy-in-your-native-tongue':
                $value = Constant::where('type', 'language')->get();

                if (!empty($selectedIds)) {
                    $selected_values = Constant::whereIn('id', $selectedIds)->get();
                }
                break;
            case 'popular-movies':
                $value = Entertainment::where('IMDb_rating', '>', 5)->orderBy('IMDb_rating', 'desc')->take(10)->get();

                if (!empty($selectedIds)) {
                    $selected_values = Entertainment::whereIn('id', $selectedIds)->get();
                }
                break;
            case 'top-channels':
                $value = LiveTvChannel::take(10)->get();

                if (!empty($selectedIds)) {
                    $selected_values = LiveTvChannel::whereIn('id', $selectedIds)->get();
                }
                break;
            case 'your-favorite-personality':
                $value = CastCrew::where('type', 'actor')->take(10)->get();

                if (!empty($selectedIds)) {
                    $selected_values = CastCrew::whereIn('id', $selectedIds)->get();
                }
                break;
            case '500-free-movies':
                $value = Entertainment::where('type', 'movie')->where('movie_access', 'free')->take(10)->get();

                if (!empty($selectedIds)) {
                    $selected_values = Entertainment::whereIn('id', $selectedIds)->get();
                }
                break;
            case 'genre':
                $value = Genres::take(10)->get();

                if (!empty($selectedIds)) {
                    $selected_values = Genres::whereIn('id', $selectedIds)->get();
                }
                break;

        }

        if ($value && !empty($selectedIds)) {
            $value = $value->reject(function ($item) use ($selectedIds) {
                return in_array($item->id, $selectedIds);
            });
        }

        return response()->json(['selected' => $selected_values, 'available' => $value ]);
    }

    public function updatePosition(Request $request)
    {
        $sortedIDs = $request->input('sortedIDs');

        foreach ($sortedIDs as $index => $id) {
            $mobileSetting = MobileSetting::find($id);
            $mobileSetting->position = $index + 1;  
            $mobileSetting->save();
        }

        return response()->json(['success' => true]);
    }
}
