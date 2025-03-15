<?php

namespace App\Http\Resources;

use App\Models\Person;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FullTitleChapterResource extends JsonResource
{
    private function getImages(string $extensions, object $chapter, object $chapterImage)
    {
        $final_array = [];
        $arr = explode('|', $extensions);
        for ($i = 0; $i < count($arr) - 1; $i++) {
            if (!empty($arr[$i])) {
                $elems = explode(',', $arr[$i]);
                foreach ($elems as $sub_item) {
                    switch ($i) {
                        case 0:
                            $final_array[] = "{$chapter->path}{$chapterImage->translator->slug}/{$sub_item}.jpeg";
                            break;

                        case 1:
                            $final_array[] = "{$chapter->path}{$chapterImage->translator->slug}/{$sub_item}.jpg";
                            break;

                        case 2:
                            $final_array[] = "{$chapter->path}{$chapterImage->translator->slug}/{$sub_item}.webp";
                            break;

                        case 3:
                            $final_array[] = "{$chapter->path}/{$chapterImage->translator->slug}/{$sub_item}.png";
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
        // dd($this->chapter);
        return [
            'translator' => new PersonResource(Person::query()->find($this->person_id)),
            'number' => $this->chapter->number,
            'volume' => $this->chapter->volume,
            'name' => $this->chapter->name,
            'images' => $this->getImages($this->extensions, $this->chapter, $this)
        ];
    }
}
