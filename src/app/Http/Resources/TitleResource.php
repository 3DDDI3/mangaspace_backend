<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TitleResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $isScraperRequest = preg_match("/MangaSpaceScraper/", $request->userAgent());

        return [
            'name' => $this->ru_name,
            'altName' => $this->eng_name,
            'slug' => $this->slug,
            'path' => $this->path,
            'country' => $this->country,
            'description' => $this->description,
            'otherNames' => $this->otherNames,
            'releaseYear' => $this->release_year,
            'titleStatus' => !$isScraperRequest ? $this->titleStatus->status : $this->titleStatus->id,
            'translateStatus' => !$isScraperRequest ? $this->translateStatus?->status : $this->translateStatus?->id,
            'type' => $this->category->category,
            'releaseFormat' => !$isScraperRequest ? $this->releaseFormat?->format : $this->releaseFormat?->id,
            'covers' =>  TitleCoverResource::collection($this->covers),
            // 'chapters' => new ChapterResource($this->chapter)
        ];
    }
}
