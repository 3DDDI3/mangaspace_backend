<?php

namespace App\Http\Controllers\Api\v1_0;

use App\Http\Controllers\Controller;
use App\Http\Requests\TitleChapter\TitleChapterStoreRequest;
use App\Http\Requests\TitleChapter\TitleChapterUpdateRequest;
use App\Http\Resources\ChapterImageResource;
use App\Http\Resources\ChapterResource;
use App\Http\Resources\TitleChapterResource;
use App\Models\Title;

class TitleChapterController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(string $title_slug)
    {
        return TitleChapterResource::collection(Title::query()->where(['slug' => $title_slug])->first()->chapters);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(TitleChapterStoreRequest $request, string $title_slug)
    {
        $chapters = $request->validated();

        foreach ($chapters as $chapter) {
            $title = Title::query()
                ->where(['slug' => $title_slug])
                ->first();

            $title->chapters()
                ->create([
                    'path' => $chapter['url'],
                    'number' => $chapter['number'],
                    'volume' => $chapter['volume'],
                    'name' => $chapter['name'],
                ]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $title_slug, string $chapter_number)
    {
        return new ChapterResource(Title::query()->where(['slug' => $title_slug])->first()->chapters()->where(['number' => $chapter_number])->first());
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function update(TitleChapterUpdateRequest $request, string $title_slug, string $chapter_number)
    {
        $chapter = $request->validated();

        Title::query()
            ->where(['slug' => $title_slug])
            ->first()
            ->where(['number' => $chapter_number])
            ->first()
            ->fill([
                'path' => $chapter['url'],
                'volume' => $chapter['volume'],
                'number' => $chapter['number'],
                'name' => $chapter['name'],
            ])
            ->save();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $title_slug, string $chapter_number) {}
}
