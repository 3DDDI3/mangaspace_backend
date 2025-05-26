<?php

namespace App\Http\Controllers\Api\v1_0;

use Illuminate\Support\Str;
use App\Enums\PersonType;
use App\Http\Controllers\Controller;
use App\Http\Requests\ChapterImage\ChapterImageDeleteRequest;
use App\Http\Requests\ChapterImage\ChapterImageStoreRequest;
use App\Http\Requests\ChapterImage\ChapterImageUpdateRequest;
use App\Models\Chapter;
use App\Models\ChapterImage;
use App\Models\Person;
use App\Services\ImageStringService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ChapterImageController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(ChapterImageStoreRequest $request, string $title_slug, string $chapter_number)
    {
        $chapter_images = $request->validated();

        if (!empty($chapter_images['id'])) {
            /**
             * Загрузка изображения
             */
            $chapterImage = ChapterImage::query()->find($request->id);
            preg_match("/\..+$/", $request->name, $extension);
            $name = $request->number . $extension[0];
            $request->file()['file']->storeAs("media/titles/{$chapterImage->chapter->title->path}/{$chapterImage->chapter->path}/{$chapterImage->translator->slug}", $name);
            $chapterImage->extensions = ImageStringService::refreshImages(ChapterImage::query()->first()->extensions, $name);
            $chapterImage->save();

            return response(["/media/titles/{$chapterImage->chapter->title->path}/{$chapterImage->chapter->path}/{$chapterImage->translator->slug}/{$name}"]);
        }

        $person = Person::query()
            ->firstOrCreate([
                'name' => $chapter_images['translator']['name'],
                'slug' => empty($chapter_images['translator']['altName']) ? Str::slug($chapter_images['translator']['name']) : $chapter_images['translator']['altName'],
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
    public function update(ChapterImageUpdateRequest $request, string $title_slug, string $chapter_number)
    {
        $data = $request->validated();

        $fields = ["person" => "person_id", "extensions" => "extensions"];

        $chapter = Chapter::query()->where(['number' => $chapter_number])->first();

        if (!$chapter)
            return response(["message" => "Не удалось найти главу"], 404);

        $chapterImage = $chapter->images()->where(['person_id' => $data['person']])->first();

        if (!$chapterImage)
            return response(["message" => "Не удалось найти главу"], 404);

        foreach ($data as $key => $value) {
            $field = $fields[$key];
            $chapterImage->$field = $value;
        }

        $chapterImage->save();

        return response(null, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ChapterImageDeleteRequest $request, string $titleSlug, string $chapterNumber)
    {
        $data = $request->validated();

        $chapter = Chapter::query()
            ->where(['number' => $chapterNumber])
            ->first();

        $chapterImage = $chapter->images()
            ->where(['person_id' => $data['person']])
            ->first();

        $imageCollection = ImageStringService::deleteImage($chapterImage->extensions, $data['image']);

        if (!$imageCollection->has('extension'))
            return response(['message' => "Не удалось найти изображение"], 400);

        Storage::delete("media/titles/{$chapter->title->path}/{$chapter->path}/{$chapterImage->translator->alt_name}/{$data['image']}{$imageCollection['extension']}");

        $chapterImage->extensions = $imageCollection['images'];
        $chapterImage->save();

        return response(null, 204);
    }
}
