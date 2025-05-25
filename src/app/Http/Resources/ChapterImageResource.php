<?php

namespace App\Http\Resources;

use App\Services\ImageStringService;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ChapterImageResource extends JsonResource
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
            'translator' => new PersonResource($this->translator),
            'images' => ImageStringService::parseImages($this->extensions),
        ];
    }
}
