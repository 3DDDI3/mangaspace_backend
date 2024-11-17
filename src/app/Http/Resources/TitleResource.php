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
            'name' => $this->ru_name,
            'altName' => $this->eng_name,
            'slug' => $this->slug,
            'country' => $this->country,
            'description' => $this->description,
            'otherNames' => $this->otherNames,
            'releaseYear' => $this->release_year,
            'titleStatus' => $this->title_status_id,
            'translateStatus' => $this->translate_status_id,
            'type' => $this->category->category,
            'releaseFormat' => $this->release_format_id,
        ];
    }
}
