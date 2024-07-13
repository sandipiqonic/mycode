<?php

namespace Modules\Entertainment\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;

class ReviewResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    // $timezone = Setting::where('name', 'default_time_zone')->value('val') ?? 'UTC';

    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'entertainment_id' => $this->entertainment_id,
            'rating' => $this->rating,
            'review' => $this->review,
            'user_id' => $this->user_id,
            'username' => optional($this->user)->full_name ?? default_user_name(),
            'profile_image' => optional($this->user)->media->pluck('original_url')->first(),
            'created_at' => $this->created_at,
            // 'created_at' => Carbon::parse($this->created_at)->timezone($timezone)->format('Y-m-d H:i:s'),
        ];
    }
}
