<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PersonPhotoResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'path' => $this->path,
            'created_at' => $this->created_at->format('h:i d.m.Y'),
            'upadated_at' => $this->updated_at->format('h:i d.m.Y'),
        ];
    }
}
