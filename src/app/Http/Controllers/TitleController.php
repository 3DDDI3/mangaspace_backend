<?php

namespace App\Http\Controllers;

use App\Enums\AgeLimiter;
use App\Enums\PersonType;
use App\Enums\TitleStatus as EnumsTitleStatus;
use App\Enums\TranslateStatus as EnumsTranslateStatus;
use App\Http\Requests\Title\StoreTitleRequest;
use App\Http\Requests\Title\UpdateTitleRequest;
use App\Models\Category;
use App\Models\Genre;
use App\Models\Person;
use App\Models\Title;
use App\Models\TitleStatus;
use App\Models\TranslateStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

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
    public function store(StoreTitleRequest $request)
    {
        $data = $request->validated();

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
                'eng_name' => $data['altName'],
                'other_names'  => $data['otherNames'],
                'description' => $data['description'],
                'title_status_id' => EnumsTitleStatus::from($data['titleStatus']),
                'translate_status_id' => EnumsTranslateStatus::from($data['translateStatus']),
                'release_year' => $data['releaseYear'],
                'country' => $data['country'],
            ]);


        $request = Http::withHeaders([
            'Accept' => 'Application/json',
            'Origin' => 'http://api.mangaspace.ru:83',
            'X-XSRF-TOKEN' => 'eyJpdiI6IllSRCtMcjFMSHJHZjRKSzZ6ZUMrcXc9PSIsInZhbHVlIjoic09FWVhsUm4xdkhKL1dGOWNocklyL3FnL2I4anZQUG9lSFR4YjhTdzFWS1M0Y0YrM0hZTXhLeFE0M1M3VVRMRjhyZytDL05KTmlLWlFmM1hoNGFpZjBudTFuWThjWjR1c3VTQzRoU0VVNHc5N2xwc25vYlBCdlArZmlhb1ptcUUiLCJtYWMiOiI0NzYyMWQ5N2E3ODgwNTRkNmFhZjc2MzZjZTE4ZWQ4YjBjNmM0MjE2YjhjNmNhZmE0Y2JjOTMxOTdjZDdkNzE2IiwidGFnIjoiIn0',
            'Bearer' => "13|sdO6hhsLUiMrxzkqc14WSGAq125mzyUzpir6IwQr8001ea8a",
        ])->post("http://host.docker.internal:83/v1.0/titles/1/genres", [
            $request->json()
        ]);

        dd($request);
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
    public function update(UpdateTitleRequest $request, string $id)
    {
        dd($request->validated());
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
