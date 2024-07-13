<?php

namespace Modules\Entertainment\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Modules\Entertainment\Models\Entertainment;
use Modules\Entertainment\Transformers\MoviesResource;
use Modules\Entertainment\Transformers\MovieDetailResource;
use Modules\Entertainment\Transformers\TvshowResource;
use Modules\Entertainment\Transformers\TvshowDetailResource;
use Modules\Entertainment\Models\Watchlist;
use Modules\Entertainment\Models\Like;
use Modules\Entertainment\Models\EntertainmentDownload;
use Modules\Episode\Models\Episode;
use Modules\Entertainment\Transformers\EpisodeResource;
use Modules\Entertainment\Transformers\EpisodeDetailResource;
use Modules\Entertainment\Transformers\SearchResource;
use Modules\Entertainment\Transformers\ComingSoonResource;
use Carbon\Carbon;
use Modules\Entertainment\Models\UserReminder;
use Modules\Entertainment\Models\EntertainmentView;
use Modules\Entertainment\Models\ContinueWatch;
use Illuminate\Support\Facades\Cache;
use Modules\Genres\Models\Genres;

class EntertainmentsController extends Controller
{
    public function movieList(Request $request){
        $perPage = $request->input('per_page', 10);
        
        $movieList = Entertainment::where('type', 'movie')->where('status',1)->with('entertainmentGenerMappings','plan','entertainmentReviews','entertainmentTalentMappings','entertainmentStreamContentMappings','entertainmentDownloadMappings');
      
        if ($request->has('search')) {
            $searchTerm = $request->search;
            $movieList->where(function ($query) use ($searchTerm) {
                $query->where('name', 'like', "%{$searchTerm}%");
            });
        }  
        if($request->filled('genre_id')){
            $genreId = $request->genre_id;
            $movieList->whereHas('entertainmentGenerMappings', function ($query) use ($genreId) {
                $query->where('genre_id',$genreId);
            });
        }
        if($request->filled('actor_id')){
            $actorId = $request->actor_id;
            $movieList->whereHas('entertainmentTalentMappings', function ($query) use ($actorId) {
                $query->where('talent_id',$actorId);
            });
        }

        $movies = $movieList->orderBy('updated_at', 'desc')->paginate($perPage);

        $responseData = MoviesResource::collection($movies); 
        
        return response()->json([
            'status' => true,
            'data' => $responseData,
            'message' => __('movie.movie_list'),
        ], 200);
    }

    public function movieDetails(Request $request){

        $movieId=$request->movie_id;

        $cacheKey = 'move_details_'.$movieId;
 
        $responseData = Cache::get($cacheKey);

        if(!$responseData) {

            $movie = Entertainment::where('id', $movieId)->with('entertainmentGenerMappings','plan','entertainmentReviews','entertainmentTalentMappings','entertainmentStreamContentMappings','entertainmentDownloadMappings')->first();
            $movie['reviews'] = $movie->entertainmentReviews ?? null;

           if($request->has('user_id')){
            
               $user_id = $request->user_id;
               $movie['is_watch_list'] = WatchList::where('entertainment_id', $movieId)->where('user_id', $user_id)->exists();
               $movie['is_likes'] = Like::where('entertainment_id', $movieId)->where('user_id', $user_id)->where('is_like', 1)->exists();
               $movie['is_download'] = EntertainmentDownload::where('entertainment_id',$movieId)->where('user_id', $user_id)->where('entertainment_type', 'movie')->where('is_download', 1)->exists();
               $movie['your_review'] = $movie->entertainmentReviews->where('user_id', $user_id)->first();
   
               if($movie['your_review']){
                   $movie['reviews'] = $movie['reviews']->where('user_id', '!=', $user_id);
               }
   
               $continueWatch = ContinueWatch::where('entertainment_id', $movie->id)->where('user_id', $user_id)->where('entertainment_type', 'movie')->first();
               $movie['continue_watch'] = $continueWatch;
           }
            $responseData = new MovieDetailResource($movie);
            Cache::put($cacheKey, $responseData);
        }

        return response()->json([
            'status' => true,
            'data' => $responseData,
            'message' => __('movie.movie_details'),
        ], 200);
    }

    public function tvshowList(Request $request){
        $perPage = $request->input('per_page', 10);
        $tvshowList = Entertainment::query()->with('entertainmentGenerMappings','plan','entertainmentReviews','entertainmentTalentMappings','season','episode')->where('type', 'tvshow');

        if ($request->has('search')) {
            $searchTerm = $request->search;
            $tvshowList->where(function ($query) use ($searchTerm) {
                $query->where('name', 'like', "%{$searchTerm}%");
            });
        }  

        $tvshowList =$tvshowList->where('status',1);

        $tvshows = $tvshowList->orderBy('updated_at', 'desc');
        $tvshows = $tvshows->paginate($perPage);

        $responseData = TvshowResource::collection($tvshows);
        
        return response()->json([
            'status' => true,
            'data' => $responseData,
            'message' => __('movie.tvshow_list'),
        ], 200);
    }

    public function tvshowDetails(Request $request){

        $tvshow_id=$request->tvshow_id;

        $cacheKey = 'tvshow_details_'.$tvshow_id;
    
        $responseData = Cache::get($cacheKey);
    
        if(!$responseData) {

            $tvshow = Entertainment::where('id', $tvshow_id)->with('entertainmentGenerMappings','plan','entertainmentReviews','entertainmentTalentMappings','season','episode')->first();
            $tvshow['reviews'] = $tvshow->entertainmentReviews ?? null;
    
            if($request->has('user_id')){
                $user_id = $request->user_id;
                $tvshow['user_id'] = $user_id;
                $tvshow['is_watch_list'] = WatchList::where('entertainment_id', $request->tvshow_id)->where('user_id', $user_id)->exists();
                $tvshow['is_likes'] = Like::where('entertainment_id', $request->tvshow_id)->where('user_id', $user_id)->where('is_like', 1)->exists();
                $tvshow['your_review'] = $tvshow->entertainmentReviews->where('user_id', $user_id)->first();
    
                if($tvshow['your_review']){
                    $tvshow['reviews'] = $tvshow['your_review']->where('user_id', '!=', $user_id);
                }
            }
    
            $responseData = new TvshowDetailResource($tvshow);

        }

        return response()->json([
            'status' => true,
            'data' => $responseData,
            'message' => __('movie.tvshow_details'),
        ], 200);
    }

    public function saveDownload(Request $request)
    {
        $user = auth()->user();
        $download_data = $request->all();
        $download_data['user_id'] = $user->id;

        $download = EntertainmentDownload::where('entertainment_id',$request->entertainment_id)->where('user_id',$user->id)->where('entertainment_type', $request->entertainment_type)->first();

        if(!$download){
            $result = EntertainmentDownload::create($download_data);
            return response()->json(['status' => true, 'message' => __('movie.movie_download')]);
        }
        else{
            return response()->json(['status' => true, 'message' => __('movie.already_download')]);
        }
    }

    public function episodeList(Request $request){
        $perPage = $request->input('per_page', 10);
        $user_id = $request->user_id;
        $episodeList = Episode::where('status',1)->with('entertainmentdata','plan','EpisodeStreamContentMapping','episodeDownloadMappings');

        if($request->has('tvshow_id')){
            $episodeList = $episodeList->where('entertainment_id', $request->tvshow_id);
        }
        if($request->has('season_id')){
            $episodeList = $episodeList->where('season_id', $request->season_id);
        }

        if ($request->has('search')) {
            $searchTerm = $request->search;
            $episodeList->where(function ($query) use ($searchTerm) {
                $query->where('name', 'like', "%{$searchTerm}%");
            });
        }  

        $episodes = $episodeList->orderBy('id', 'asc')->paginate($perPage);
     
        $responseData = EpisodeResource::collection(
                            $episodes->map(function ($episode) use ($user_id){
                                return new EpisodeResource($episode, $user_id);
                            })
                        );
        
        return response()->json([
            'status' => true,
            'data' => $responseData,
            'message' => __('movie.episode_list'),
        ], 200);
    }

    public function episodeDetails(Request $request){
         $user_id = $request->user_id;
        $episode_id=$request->episode_id;

         $episode = Episode::where('id',$episode_id)->with('entertainmentdata', 'plan', 'EpisodeStreamContentMapping', 'episodeDownloadMappings')->first();

        if($request->has('user_id')){
            $continueWatch = ContinueWatch::where('entertainment_id', $episode->id)->where('user_id', $user_id)->where('entertainment_type', 'episode')->first();
            $episode['continue_watch'] = $continueWatch;

            $episode['is_download']= EntertainmentDownload::where('entertainment_id', $episode->id)->where('user_id',  $user_id)->where('entertainment_type', 'episode')->where('is_download', 1)->exists();

            $genre_ids = $episode->entertainmentData->entertainmentGenerMappings->pluck('genre_id');

            $episode['moreItems'] = Entertainment::where('type', 'tvshow')
                                   ->whereHas('entertainmentGenerMappings', function($query) use ($genre_ids) {
                                       $query->whereIn('genre_id', $genre_ids);
                                   })
                                   ->where('id', '!=', $episode->id)
                                   ->orderBy('id', 'desc')
                                   ->get();

            $episode['genre_data'] =Genres::whereIn('id', $genre_ids)->get();

        }

        $responseData = new EpisodeDetailResource($episode);

        return response()->json([
            'status' => true,
            'data' => $responseData,
            'message' => __('movie.episode_details'),
        ], 200);
    }

    public function searchList(Request $request){
        $perPage = $request->input('per_page', 10);
        $movieList = Entertainment::query()->with('entertainmentGenerMappings','plan','entertainmentReviews','entertainmentTalentMappings','entertainmentStreamContentMappings')->where('type', 'movie');

        $movieList = $movieList->where('status',1);

        $movies = $movieList->orderBy('updated_at', 'desc');
        $movies = $movies->paginate($perPage);

        $responseData = new SearchResource($movies);
        
        return response()->json([
            'status' => true,
            'data' => $responseData,
            'message' => __('movie.search_list'),
        ], 200);
    }

    public function getSearch(Request $request){
        $perPage = $request->input('per_page', 10);
        $movieList = Entertainment::query()->with('entertainmentGenerMappings','plan','entertainmentReviews','entertainmentTalentMappings','entertainmentStreamContentMappings')->where('type', 'movie');

        if ($request->has('search')) {
            $searchTerm = $request->search;
            $movieList->where(function ($query) use ($searchTerm) {
                $query->where('name', 'like', "%{$searchTerm}%");
            });
        }  

        $movieList = $movieList->where('status',1);

        $movies = $movieList->orderBy('updated_at', 'desc');
        $movies = $movies->paginate($perPage);

        $responseData = MoviesResource::collection($movies);
        
        return response()->json([
            'status' => true,
            'data' => $responseData,
            'message' => __('movie.search_list'),
        ], 200);
    }

    public function comingSoon(Request $request){
        $perPage = $request->input('per_page', 10);
        $entertainmentList = Entertainment::query()->with('entertainmentGenerMappings','plan','entertainmentReviews','entertainmentTalentMappings','entertainmentStreamContentMappings','season');

        $todayDate = Carbon::today()->format('Y-m-d');
         
        $entertainmentList = $entertainmentList->where('release_date', '>=', $todayDate);
        $entertainmentList = $entertainmentList->where('status',1);

        $entertainment = $entertainmentList->paginate($perPage);
     
        $responseData = ComingSoonResource::collection($entertainment);
        
        return response()->json([
            'status' => true,
            'data' => $responseData,
            'message' => __('movie.coming_soon_list'),
        ], 200);
    }

    public function saveReminder(Request $request)
    {
        $user = auth()->user();
        $reminderData = $request->all();
        $reminderData['user_id'] = $user->id;

        $reminders = UserReminder::updateOrCreate(
            ['entertainment_id' => $request->entertainment_id, 'user_id' => $user->id],
            $reminderData
        );
        
        $message = $reminders->wasRecentlyCreated ? __('movie.reminder_add') : __('movie.reminder_update');
        $result = $reminders;
        
        return response()->json(['status' => true, 'message' => $message]);
    }

    public function saveEntertainmentViews(Request $request)
    {
        $user = auth()->user();
        $data = $request->all();
        $data['user_id'] = $user->id;
        $viewData = EntertainmentView::where('entertainment_id', $request->entertainment_id)->where('user_id', $user->id)->first();

        if(!$viewData){
            $views = EntertainmentView::create($data);
            $message = __('movie.view_add');
        }
        else{
            $message = __('movie.already_added');
        }
        
        return response()->json(['status' => true, 'message' => $message]);
    }
}