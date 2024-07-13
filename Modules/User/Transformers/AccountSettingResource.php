<?php

namespace Modules\User\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;

class AccountSettingResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray($request): array
    {
        return [
            'plan_details' => $this->plan_details ?? null,
            'register_mobile_number' => $this->mobile,
            'your_device' => $this->your_device ?? null,
            'other_device' => $this->other_device ?? null,
        ];
    }
}
