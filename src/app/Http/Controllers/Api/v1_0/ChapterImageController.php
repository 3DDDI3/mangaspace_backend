<?php

namespace App\Http\Controllers\Api\v1_0;

use App\Enums\PersonType;
use App\Http\Controllers\Controller;
use App\Http\Requests\ChapterImage\ChapterImageStoreRequest;
use App\Models\Chapter;
use App\Models\ChapterImage;
use App\Models\Person;
use App\Models\Title;
use Illuminate\Http\Request;

class ChapterImageController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(ChapterImageStoreRequest $request, string $title_slug, string $chapter_number)
    {
        $chapter_images = $request->validated();

        $person = Person::query()
            ->firstOrCreate([
                'name' => $chapter_images['translator']['name'],
                'slug' => $chapter_images['translator']['name'],
                'person_type_id' => PersonType::Translator,
            ]);

        $chapter_id = Chapter::query()
            ->whereHas('title', function ($query) use ($title_slug) {
                $query->where(['slug' => $title_slug]);
            })
            ->where(['number' => $chapter_number])
            ->first()
            ->id;

        if (ChapterImage::query()->where(['person_id' => $person->id, 'chapter_id' => $chapter_id])->count() == 0)
            ChapterImage::query()
                ->create([
                    'extensions' => $chapter_images['extensions'],
                    'chapter_id' => $chapter_id,
                    'person_id' => $person->id,
                ]);

        return response(null, 201);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
