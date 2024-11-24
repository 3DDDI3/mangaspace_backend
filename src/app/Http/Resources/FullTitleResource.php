<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FullTitleResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'name' => $this->ru_name,
            'altName' => $this->eng_name,
            'slug' => $this->slug,
            'chapters' => TitleChapterResource::collection($this->chapters),
            'country' => $this->country,
            'description' => $this->description,
            'otherNames' => $this->otherNames,
            'releaseYear' => $this->release_year,
            'titleStatus' => $this->titleStatus?->status,
            'translateStatus' => $this->translateStatus?->status,
            'type' => $this->category->category,
            'releaseFormat' => $this->releaseFormat?->fromat,
        ];
    }
}
