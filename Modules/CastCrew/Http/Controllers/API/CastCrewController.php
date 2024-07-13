<?php

namespace Modules\CastCrew\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\CastCrew\Models\CastCrew;
use Modules\CastCrew\Transformers\CastCrewListResource;

class CastCrewController extends Controller
{
    public function castCrewList(Request $request){

        $perPage = $request->input('per_page', 10);
        $castcrew_list = CastCrew::query();

        if ($request->has('search')) {
            $searchTerm = $request->search;
            $castcrew_list->where(function ($query) use ($searchTerm) {
                $query->where('name', 'like', "%{$searchTerm}%");
            });
        }

        if($request->has('type')){
            $castcrew_list->where('type', $request->type);
        }

        $castcrew = $castcrew_list->orderBy('updated_at', 'desc');
        $castcrew = $castcrew->paginate($perPage);

        $responseData = CastCrewListResource::collection($castcrew);
        
        return response()->json([
            'status' => true,
            'data' => $responseData,
            'message' => __('castcrew.castcrew_list'),
        ], 200);
    }
}
