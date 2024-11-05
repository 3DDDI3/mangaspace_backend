<?php

namespace App\Http\Controllers;

use App\Enums\AgeLimiter;
use App\Enums\PersonType;
use App\Enums\TitleStatus as EnumsTitleStatus;
use App\Enums\TranslateStatus as EnumsTranslateStatus;
use App\Http\Requests\TitleRequest;
use App\Models\Category;
use App\Models\Genre;
use App\Models\Person;
use App\Models\Title;
use App\Models\TitleStatus;
use App\Models\TranslateStatus;
use Illuminate\Http\Request;

class TitleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(TitleRequest $request)
    {
        $json = $request->json()->all();
        dd($json);
        dd($request->validated());

        $json = json_decode($request->obj);

        if (Category::query()->where(['category' => $json->type])->count() == 0)
            Category::create(['category' => $json->type]);

        if (TitleStatus::query()->where(['status' => EnumsTitleStatus::from($json->titleStatus)])->count() == 0)
            TitleStatus::query()->create(['status' => EnumsTitleStatus::from($json->titleStatus)]);

        if (TranslateStatus::query()->where(['status' => EnumsTranslateStatus::from($json->translateStatus)])->count() == 0);
        TranslateStatus::query()->create(['status' => EnumsTranslateStatus::from($json->translateStatus)]);

        foreach ($json->genres as $genre) {
            if (Genre::query()->where(['genre' => $genre])->count() == 0)
                Genre::create(['genre' => $genre]);
        }

        foreach ($json->persons as $person) {
            if (Person::query()->where(['name' => $person->name])->count() == 0)
                Person::query()->create([
                    'name' => $person->name,
                    'description' => $person->description,
                    'person_type_id' => PersonType::from($person->type),
                ]);
        }

        if (Title::query()->where(['ru_name' => $json->name, 'eng_name' => $json->altName])->count() == 0)
            Title::query()->create([
                'category_id' => Category::query()->where(['category' => $json->type])->first('id')->id,
                'ru_name' => $json->name,
                'eng_name' => $json->altName,
                'other_names'  => $json->otherNames,
                'description' => $json->description,
                'title_status_id' => EnumsTitleStatus::from($json->titleStatus),
                'translate_status_id' => EnumsTranslateStatus::from($json->translateStatus),
                'release_year' => $json->releaseYear,
                'country' => $json->country,
            ]);

        dd($json, AgeLimiter::from(2)->name);
        // Category::query()->where(['category', ])
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
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
