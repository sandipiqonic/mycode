<?php

namespace Modules\Subscriptions\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;

class PlanlimitationMappingResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray($request): array
    {
        
        return [

            'id' => $this->id,
            'planlimitation_id'=>$this->planlimitation_id,
            'limitation_title'=>optional($this->limitation_data)->title,
            'limitation_value' => $this->limitation_value,
            'limit' => $this->limit,
            'status' => optional($this->limitation_data)->status,

        ];
    }
}
