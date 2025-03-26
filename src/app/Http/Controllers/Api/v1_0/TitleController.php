<?php

namespace App\Http\Controllers\Api\v1_0;

use Illuminate\Support\Str;
use App\Enums\TitleStatus as EnumsTitleStatus;
use App\Enums\TranslateStatus as EnumsTranslateStatus;
use App\Filters\TitleFilter;
use App\Http\Controllers\Controller;
use App\Http\Requests\Title\TitleStoreRequest;
use App\Http\Requests\Title\TitleUpdateRequest;
use App\Http\Requests\TitleShowRequest;
use App\Http\Resources\TitleResource;
use App\Models\Category;
use App\Models\Title;
use Illuminate\Support\Facades\DB;

class TitleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(TitleShowRequest $request, TitleFilter $filter)
    {
        $offset = !empty($request->offset) ? $request->offset : 10;
        $titles = Title::query()
            ->filter($filter)
            ->paginate($offset);

        return TitleResource::collection($titles);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(TitleStoreRequest $request)
    {

        $data = $request->validated();

        DB::transaction(function () use ($data) {
            $title = Title::query()
                ->create([
                    'category_id' => Category::query()->where(['category' => $data['type']])->first() == null
                        ? Category::query()->create(['category' => $data['type']])->id
                        : Category::query()->where(['category' => $data['type']])->first('id')->id,
                    'ru_name' => $data['name'],
                    'slug' => empty($data['altName']) ? Str::slug($data['name']) : Str::slug($data['altName']),
                    'eng_name' => $data['altName'],
                    'other_names'  => $data['otherNames'],
                    'description' => $data['description'],
                    'title_status_id' => EnumsTitleStatus::from($data['titleStatus']),
                    'translate_status_id' => EnumsTranslateStatus::from($data['translateStatus']),
                    'release_year' => $data['releaseYear'],
                    'country' => $data['country'],
                ]);

            return response(null, 201);
        });
    }

    /**
     * Display the specified resource.
     */
    public function show(string $slug)
    {
        return new TitleResource(Title::query()->where(['slug' => $slug])->first());
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(TitleUpdateRequest $request, string $slug)
    {
        $data = $request->validated();

        $title = Title::query()->where(['slug', $slug])->first();

        $title->query()
            ->fill([
                'category_id' => Category::query()->where(['category' => $data['type']])->first('id')->id,
                'ru_name' => $data['name'],
                'slug' => empty($data['altName']) ? Str::slug($data['name']) : Str::slug($data['altName']),
                'eng_name' => $data['altName'],
                'other_names'  => $data['otherNames'],
                'description' => $data['description'],
                'title_status_id' => EnumsTitleStatus::from($data['titleStatus']),
                'translate_status_id' => EnumsTranslateStatus::from($data['translateStatus']),
                'release_year' => $data['releaseYear'],
                'country' => $data['country'],
            ])->save();

        return response(null, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $slug)
    {
        return response(['1'], 200);
        $title = Title::query()
            ->where(['slug' => $slug])
            ->first();

        Title::destroy($title->id);

        return response(null, 200);
    }
}
