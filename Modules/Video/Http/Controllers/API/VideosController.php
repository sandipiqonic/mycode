<?php

namespace Modules\Video\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Video\Models\Video;
use Modules\Video\Transformers\VideoResource;
use Modules\Video\Transformers\VideoDetailResource;
use Modules\Entertainment\Models\ContinueWatch;

class VideosController extends Controller
{
  public function videoList(Request $request){
      $perPage = $request->input('per_page', 10);
      $videoList = Video::query()->with('VideoStreamContentMappings','plan');

      $videoList = $videoList->where('status',1);

      $videoData = $videoList->orderBy('updated_at', 'desc');
      $videoData = $videoData->paginate($perPage);

      $responseData = VideoResource::collection($videoData);
      
      return response()->json([
          'status' => true,
          'data' => $responseData,
          'message' => __('video.video_list'),
      ], 200);
  }
  public function videoDetails(Request $request){
      $video = Video::with('VideoStreamContentMappings','plan')->where('id', $request->video_id)->first();
          
      if($request->has('user_id')){
          $user_id = $request->user_id;
          $continueWatch = ContinueWatch::where('entertainment_id', $video->id)->where('user_id', $user_id)->where('entertainment_type', 'video')->first();
          $video['continue_watch'] = $continueWatch;
      }

      $responseData = new VideoDetailResource($video);

      return response()->json([
          'status' => true,
          'data' => $responseData,
          'message' => __('video.video_details'),
      ], 200);
  }
}
