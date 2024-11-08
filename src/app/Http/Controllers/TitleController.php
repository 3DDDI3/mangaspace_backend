<?php

namespace App\Http\Controllers;

use Illuminate\Support\Str;
use App\Enums\TitleStatus as EnumsTitleStatus;
use App\Enums\TranslateStatus as EnumsTranslateStatus;
use App\Http\Requests\Genre\StoreGenreRequest;
use App\Http\Requests\Title\StoreTitleRequest;
use App\Http\Requests\Title\UpdateTitleRequest;
use App\Http\Resources\TitleResource;
use App\Models\Category;
use App\Models\Genre;
use App\Models\Person;
use App\Models\Title;
use App\Models\TitleStatus;
use App\Models\TranslateStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

use function React\Promise\all;

class TitleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Title::find(1)->persons;
        dd();
        return new TitleResource(Title::find(1));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTitleRequest $request)
    {
        $data = $request->validated();

        DB::transaction(function () use ($data) {

            if (Category::query()->where(['category' => $data['type']])->count() == 0)
                Category::create(['category' => $data['type']]);

            if (TitleStatus::query()->where(['status' => EnumsTitleStatus::from($data['titleStatus'])])->count() == 0)
                TitleStatus::query()->create(['status' => EnumsTitleStatus::from($data['titleStatus'])]);

            if (TranslateStatus::query()->where(['status' => EnumsTranslateStatus::from($data['translateStatus'])])->count() == 0);
            TranslateStatus::query()->create(['status' => EnumsTranslateStatus::from($data['translateStatus'])]);

            if (Title::query()->where(['ru_name' => $data['name'], 'eng_name' => $data['altName']])->count() == 0)
                Title::query()->create([
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
                ]);

            return response()->json(['message' => 'succesfull'], 200);
        });
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        dd($id);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTitleRequest $request, string $slug)
    {
        $data = $request->validated();

        $title = Title::query()->where(['slug', $slug])->first();

        if (empty($title))
            return response()->json(['error' => 'Не удалось найти тайтл'], 400);

        if (Category::query()->where(['category' => $data['type']])->count() == 0)
            Category::create(['category' => $data['type']]);

        if (TitleStatus::query()->where(['status' => EnumsTitleStatus::from($data['titleStatus'])])->count() == 0)
            TitleStatus::query()->create(['status' => EnumsTitleStatus::from($data['titleStatus'])]);

        if (TranslateStatus::query()->where(['status' => EnumsTranslateStatus::from($data['translateStatus'])])->count() == 0)
            TranslateStatus::query()->create(['status' => EnumsTranslateStatus::from($data['translateStatus'])]);

        $title->query()->fill([
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
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $slug)
    {
        Title::query()
            ->where(['slug' => $slug])
            ->first()
            ->delete();

        return request()->json(['message' => 'succesefull'], 200);
    }
}
