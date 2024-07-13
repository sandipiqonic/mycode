<?php

namespace Modules\Genres\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Genres\Services\GenreService;
use Modules\Genres\Transformers\GenresResource;
use Illuminate\Support\Facades\Cache;

class GenersController extends Controller
{
    protected $genreService;

    public function __construct(GenreService $genreService)
    {
        $this->genreService = $genreService;
    }

    public function genreList(Request $request)
    {
        $perPage = $request->input('per_page', 10);
        $searchTerm = $request->input('search', null);
    
        $cacheKey = 'genres_list';
    
        $genres = Cache::get($cacheKey);
    
        if(!$genres) {
            $genres = $this->genreService->getGenresList($perPage, $searchTerm);
            Cache::put($cacheKey, $genres);
        }
    
        $responseData = GenresResource::collection($genres);
    
        return response()->json([
            'status' => true,
            'data' => $responseData,
            'message' => __('genres.genres_list'),
        ], 200);
    }
}
