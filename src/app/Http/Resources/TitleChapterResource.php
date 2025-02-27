<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TitleChapterResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'volume' => $this->volume,
            'name' => $this->name,
            'path' => $this->path,
            'number' => $this->number,
            'created_at' => $this->created_at->format('h:i d.m.Y'),
            'updated_at' => $this->updated_at->format('h:i d.m.Y'),
            'translator_branch' => ChapterImageResource::collection($this->images),
        ];
    }
}
