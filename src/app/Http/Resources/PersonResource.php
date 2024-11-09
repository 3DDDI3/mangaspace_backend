<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PersonResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'name' => $this->name,
            'slug' => $this->slug,
            'altName' => $this->alt_name,
            'description' => $this->description,
            'personType' => $this->type?->type,
            'photos' => PersonPhotoResource::collection($this->photos),
        ];
    }
}
