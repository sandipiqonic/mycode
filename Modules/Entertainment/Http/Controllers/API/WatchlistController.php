<?php

namespace Modules\Entertainment\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Entertainment\Models\Watchlist;
use Modules\Entertainment\Transformers\WatchlistResource;
use Modules\Entertainment\Models\ContinueWatch;
use Modules\Entertainment\Transformers\ContinueWatchResource;

class WatchlistController extends Controller
{
    public function watchList(Request $request){
        $user_id = auth()->user()->id;
        
        $perPage = $request->input('per_page', 10);
        $watch_list = Watchlist::query()->with('entertainment');

        $watchList = $watch_list->where('user_id', $user_id);
        $watchList = $watch_list->orderBy('updated_at', 'desc');
        $watchList = $watchList->paginate($perPage);

        $responseData = WatchlistResource::collection($watchList);
        
        return response()->json([
            'status' => true,
            'data' => $responseData,
            'message' => __('movie.watch_list'),
        ], 200);
    }

    public function saveWatchList(Request $request)
    {
        $user = auth()->user();
        $watchlist_data = $request->all();
        $watchlist_data['user_id'] = $user->id;
        $result = Watchlist::create($watchlist_data);

        return response()->json(['status' => true, 'message' => __('movie.watchlist_add')]);
    }

    public function deleteWatchList(Request $request)
    {
        $user = auth()->user();
        $ids = json_decode($request->id);
        $watchlist = Watchlist::whereIn('id', $ids)->where('user_id', $user->id)->delete();
        if ($watchlist == null) {
            $message = __('movie.watchlist_notfound');

            return response()->json(['status' => false, 'message' => $message]);
        }
        $message = __('movie.watchlist_delete');

        return response()->json(['status' => true, 'message' => $message]);
    }

    public function continuewatchList(Request $request){
        $user_id = auth()->user()->id;
        
        $perPage = $request->input('per_page', 10);
        $continuewatchList = ContinueWatch::query()->with('entertainment','episode','video');

        $continuewatch = $continuewatchList->where('user_id', $user_id);
        $continuewatch = $continuewatchList->orderBy('updated_at', 'desc');
        $continuewatch = $continuewatch->paginate($perPage);

        $responseData = ContinueWatchResource::collection($continuewatch);
        
        return response()->json([
            'status' => true,
            'data' => $responseData,
            'message' => __('movie.watch_list'),
        ], 200);
    }

    public function saveContinueWatch(Request $request)
    {
        $user = auth()->user();
        $watch_data = $request->all();
        $watch_data['total_watched_time'] = isset($watch_data['total_watched_time']) && substr_count($watch_data['total_watched_time'], ':') == 1 ? $watch_data['total_watched_time'] . ':00' : $watch_data['total_watched_time'];
        $watch_data['user_id'] = $user->id;

        $result = ContinueWatch::updateOrCreate(['entertainment_id' => $request->entertainment_id, 'user_id' => $user->id, 'entertainment_type' => $request->entertainment_type ], $watch_data);

        return response()->json(['status' => true, 'message' => __('movie.save_msg')]);
    }

    public function deleteContinueWatch(Request $request)
    {
        $continuewatch = ContinueWatch::where('id', $request->id)->delete();
        if ($continuewatch == null) {
            $message = __('movie.continuewatch_notfound');

            return response()->json(['status' => false, 'message' => $message]);
        }
        $message = __('movie.continuewatch_delete');

        return response()->json(['status' => true, 'message' => $message]);
    }
}
