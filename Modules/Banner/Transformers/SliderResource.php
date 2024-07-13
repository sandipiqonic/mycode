<?php

namespace Modules\Banner\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Entertainment\Models\Entertainment;
use Modules\LiveTV\Models\LiveTvChannel;
use Modules\Entertainment\Transformers\MoviesResource;
use Modules\Entertainment\Transformers\TvshowResource;
use Modules\LiveTV\Transformers\LiveTvChannelResource;
use Modules\Entertainment\Models\Watchlist;

class SliderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function __construct($resource, $userId = null)
    {
        parent::__construct($resource);
        $this->userId = $userId;
    }

    public function toArray($request): array
    {
        if($this->type == 'movie'){
            $entertainment = Entertainment::where('id', $this->type_id)->first();
            $entertainment['is_watch_list'] = WatchList::where('entertainment_id', $this->type_id)->where('user_id', $this->userId)->exists();
            $data = new MoviesResource($entertainment);
        }
        else if($this->type == 'tvshow'){
            $entertainment = Entertainment::where('id', $this->type_id)->first();
            $entertainment['is_watch_list'] = WatchList::where('entertainment_id', $this->type_id)->where('user_id', $this->userId)->exists();
            $data = new TvshowResource($entertainment);
        }
        else if($this->type == 'livetv'){
            $livetv = LiveTvChannel::where('id', $this->type_id)->first();
            $data = new LiveTvChannelResource($livetv);
        }
        return [
            'id' => $this->id,
            'title' => $this->title,
            'file_url' => $this->file_url,
            'type' => $this->type,
            'data' => $data
        ];
    }
}
