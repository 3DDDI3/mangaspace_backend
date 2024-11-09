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
        return [
            'id' => $this->id,
            'ru_name' => $this->ru_name,
            'eng_name' => $this->eng_name,
            'category_id' => $this->category->category,
            'release_format' => $this->releaseFormat?->format,
            'translate_status' => $this->translateStatus?->status,
            'title_stasus' => $this->titleStatus?->status,
            'description' => $this->description,
            'country' => $this->country,
            'release_year' => $this->release_year,
            'other_names' => $this->other_names,
            'genres' => GenreResource::collection($this->genres),
            'persons' => PersonResource::collection($this->persons),
        ];
    }
}
