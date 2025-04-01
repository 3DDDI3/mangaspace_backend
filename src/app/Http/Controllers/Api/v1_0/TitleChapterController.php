<?php

namespace App\Http\Controllers\Api\v1_0;

use App\Filters\TitleChapterFilter;
use App\Http\Controllers\Controller;
use App\Http\Requests\TitleChapter\TitleChapterStoreRequest;
use App\Http\Requests\TitleChapter\TitleChapterUpdateRequest;
use App\Http\Resources\ChapterResource;
use App\Http\Resources\TitleChapterResource;
use App\Models\Chapter;
use App\Models\Title;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class TitleChapterController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(TitleChapterFilter $filter, string $slug, Request $request)
    {
        $chapters = Chapter::query();

        $offset = empty($request->offset) ? 10 : $request->offset;

        $titles = Title::query()
            ->where(['slug' => $slug]);

        if ($titles->count() == 0)
            return response([], 204);

        $chapters = $titles
            ->first()
            ->chapters()
            ->filter($filter)
            ->paginate($offset);

        return TitleChapterResource::collection($chapters);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(TitleChapterStoreRequest $request, string $title_slug)
    {
        $chapter = $request->validated();

        $title = Title::query()
            ->where(['slug' => $title_slug])
            ->first();

        Chapter::query()
            ->whereHas('title', function ($query) use ($title_slug) {
                $query->where(['slug' => $title_slug]);
            })
            ->where(['number' => $chapter['number']])
            ->firstOrCreate([
                'path' => preg_replace('#\\\\+#', '/', $chapter['url']),
                'number' => $chapter['number'],
                'volume' => $chapter['volume'],
                'title_id' => $title->id,
                'name' => $chapter['name']
            ]);

        return response([], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $title_slug, string $chapter_number)
    {
        return new TitleChapterResource(Title::query()->where(['slug' => $title_slug])->first()->chapters()->where(['number' => $chapter_number])->first());
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function update(TitleChapterUpdateRequest $request, string $title_slug, string $chapter_number)
    {
        $_chapter = $request->validated();

        $chapter = Title::query()
            ->where(['slug' => $title_slug])
            ->first()
            ?->chapters()
            ->where(['number' => $chapter_number])
            ->first();

        if (empty($chapter))
            return response(["error" => "Глава не найдена"], 404);

        $chapter->fill([
            'volume' => $_chapter['volume'] ?? $chapter->volume,
            'number' => $_chapter['number'] ?? $chapter->number,
            'name' => $_chapter['name'] ?? $chapter->name
        ])->save();

        return new ChapterResource($chapter);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $title_slug, string $chapter_number)
    {
        $title = Title::query()
            ->where(['slug' => $title_slug])
            ->first();

        $chapter = $title
            ->chapters()
            ->where(['number' => $chapter_number])
            ->first();

        Chapter::destroy($chapter->id);
    }
}
