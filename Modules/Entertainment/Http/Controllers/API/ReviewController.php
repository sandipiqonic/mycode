<?php

namespace Modules\Entertainment\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Entertainment\Models\Review;
use Modules\Entertainment\Transformers\ReviewResource;
use Modules\Entertainment\Models\Like;

class ReviewController extends Controller
{
    public function getRating(Request $request)
    {
        $perPage = $request->input('per_page', 10);

        $reviews = Review::query();

        if($request->has('entertainment_id')){
            $reviews = $reviews->where('entertainment_id', $request->entertainment_id);
        }

        $reviews = $reviews->orderBy('updated_at', 'desc')->paginate($perPage);
        $review =   ReviewResource::collection($reviews);

        return response()->json([
            'status' => true,
            'data' => $review,
            'message' => __('movie.review_list'),
        ], 200);
    }

    public function saveRating(Request $request)
    {
        $user = auth()->user();
        $rating_data = $request->all();
        $rating_data['user_id'] = $user->id;
        $result = Review::updateOrCreate(['id' => $request->id], $rating_data);

        $message = __('movie.rating_update');
        if ($result->wasRecentlyCreated) {
            $message = __('movie.rating_add');
        }

        return response()->json(['status' => true, 'message' => $message]);
    }

    public function deleteRating(Request $request)
    {
        $user = auth()->user();
        $rating = Review::where('id', $request->id)->where('user_id', $user->id)->first();
        if ($rating == null) {
            $message = __('movie.rating_notfound');

            return response()->json(['status' => false, 'message' => $message]);

        }
        $message = __('movie.rating_delete');
        $rating->delete();

        return response()->json(['status' => true, 'message' => $message]);
    }

    public function saveLikes(Request $request)
    {
        $user = auth()->user();
        $likes_data = $request->all();
        $likes_data['user_id'] = $user->id;

        $likes = Like::updateOrCreate(
            ['entertainment_id' => $request->entertainment_id, 'user_id' => $user->id],
            $likes_data
        );
        
        $message = $likes->wasRecentlyCreated ? __('movie.likes_add') : __('movie.likes_update');
        $result = $likes;
        

        return response()->json(['status' => true, 'message' => $message]);
    }
}
