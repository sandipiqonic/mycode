<?php

namespace Modules\Subscriptions\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;

class PlanResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {

        $limitation_data = $this->planLimitation;
    
        $limits = [];

        foreach ($limitation_data as $limitation) {
            $key = str_replace('-', '_', $limitation->limitation_slug);
            
            $value = $limitation->limit ?? $limitation->limitation_value;

            $decodedValue = json_decode($value, true);
            if (json_last_error() === JSON_ERROR_NONE) {
                $limits[$key] = $decodedValue;
            } else {
                $limits[$key] = $value;
            }
        }

        return [
            'id' => $this->id,
            'name' => $this->name,
            'identifier' => $this->identifier,
            'price' => $this->price,
            'level' => $this->level,
            'duration' => $this->duration,
            'duration_value' => $this->duration_value,
            'description' => $this->description,
            'limit' => empty($limits) ? null :$limits,
            'status' => $this->status,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
            'deleted_by' => $this->deleted_by,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'deleted_at' => $this->deleted_at,
        ];
    }
}
