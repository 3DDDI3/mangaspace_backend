<?php

namespace App\Http\Controllers\Api\v1_0;

use App\Http\Controllers\Controller;
use App\Http\Requests\ChapterImage\ChapterImageStoreRequest;
use App\Http\Resources\ChapterImageResource;
use App\Http\Resources\ChapterResource;
use App\Models\Chapter;
use App\Models\ChapterImage;
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

        $chapter_id = Chapter::query()
            ->where(['number' => $chapter_number])
            ->value('id');

        if (ChapterImage::query()->where(['person_id' => $chapter_images['translator']['type'], 'chapter_id' => $chapter_id])->count() == 0)
            ChapterImage::query()
                ->create([
                    'extensions' => $chapter_images['extensions'],
                    'chapter_id' => $chapter_id,
                    'person_id' => $chapter_images['translator']['type'],
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
