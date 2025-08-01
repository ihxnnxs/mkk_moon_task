<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property int $id
 * @property int $building_id
 * @property string $name
 */
class OrganizationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'building' => new BuildingResource($this->whenLoaded('building')),
            'phones' => OrganizationPhoneResource::collection($this->whenLoaded('phones')),
            'activities' => ActivityResource::collection($this->whenLoaded('activities')),
        ];
    }
}
