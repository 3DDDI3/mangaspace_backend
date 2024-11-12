<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ChapterResource extends JsonResource
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
            'volume' => $this->volume,
            'number' => $this->number,
            'created_at' => $this->created_at->format('h:i d.m.Y'),
            'updated_at' => $this->updated_at->format('h:i d.m.Y'),
        ];
    }
}
