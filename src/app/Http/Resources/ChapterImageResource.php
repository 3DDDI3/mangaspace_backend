<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ChapterImageResource extends JsonResource
{
    private function parseImages(string $extensions)
    {
        $final_array = [];
        $arr = explode('|', $extensions);
        for ($i = 0; $i < count($arr) - 1; $i++) {
            if (!empty($arr[$i])) {
                $elems = explode(',', $arr[$i]);
                foreach ($elems as $sub_item) {
                    switch ($i) {
                        case 0:
                            $final_array[] = "{$sub_item}.jpeg";
                            break;

                        case 1:
                            $final_array[] = "{$sub_item}.jpg";
                            break;

                        case 2:
                            $final_array[] = "{$sub_item}.webp";
                            break;

                        case 3:
                            $final_array[] = "{$sub_item}.png";
                            break;
                    }
                }
            }
        }

        natsort($final_array);
        return $final_array;
    }

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'translator' => new PersonResource($this->translator),
            'images' => $this->parseImages($this->extensions),
        ];
    }
}
