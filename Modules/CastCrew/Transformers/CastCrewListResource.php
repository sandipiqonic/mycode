<?php

namespace Modules\CastCrew\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;

class CastCrewListResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray($request) 
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'type' => $this->type,
            'bio' => $this->bio,
            'place_of_birth' => $this->place_of_birth,
            'dob' => $this->dob,
            'designation' => $this->designation,
            'profile_image' => $this->file_url,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
            'deleted_by' => $this->deleted_by,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'deleted_at' => $this->deleted_at,
        ];
    }
}
