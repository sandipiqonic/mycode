<?php

namespace Modules\User\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Entertainment\Transformers\WatchlistResource;
use Modules\Entertainment\Transformers\ContinueWatchResource;

class UserProfileResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray($request) 
    {
        return [
            'id' => $this->id,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'full_name' => $this->first_name.' '.$this->last_name,
            'email' => $this->email,
            'mobile' => $this->mobile,
            'gender' => $this->gender,
            'date_of_birth' => $this->date_of_birth,
            'login_type' => $this->login_type,
            'email_verified_at' => $this->email_verified_at,
            'is_banned' => $this->is_banned,
            'is_subscribe' => $this->is_subscribe,
            'status' => $this->status,
            'last_notification_seen' => $this->last_notification_seen,
            'is_user_exist' => true,
            'profile_image' => $this->media->pluck('original_url')->first(),
            'media' => $this->media,
            'plan_details' => $this->plan_details ?? null,
            'watchlists' => WatchlistResource::collection($this->watchList),
            'continue_watch' => ContinueWatchResource::collection($this->continueWatch),
        ];
    }
}
