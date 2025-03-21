<?php

namespace App\Http\Controllers\Api\v1_0;

use App\Filters\TitleChapterFilter;
use App\Http\Controllers\Controller;
use App\Http\Requests\TitleChapter\TitleChapterStoreRequest;
use App\Http\Requests\TitleChapter\TitleChapterUpdateRequest;
use App\Http\Resources\TitleChapterResource;
use App\Models\Chapter;
use App\Models\Title;
use Illuminate\Http\Request;

class TitleChapterController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(TitleChapterFilter $filter, string $slug, Request $request)
    {
        $orderBy = ['id', 'number'];
        $title = Chapter::query();
        foreach ($orderBy as $item) {
            $title->orderBy($item, 'desc');
        }

        if (!empty($request->offset))
            $titles = Title::query()
                ->where(['slug' => $slug])
                ->first()
                ->chapters()
                ->paginate($request->offset);
        else
            $titles = Title::query()
                ->where(['slug' => $slug])
                ->first()
                ->chapters;

        dd($titles = Title::query()
            ->where(['slug' => $slug])
            ->first()
            ->chapters()->filter($filter)->get());

        return TitleChapterResource::collection($titles);
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
