<?php

namespace App\Http\Controllers;

use App\Enums\AgeLimiter;
use App\Enums\PersonType;
use App\Models\Category;
use App\Models\Genre;
use App\Models\Person;
use App\Models\Title;
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
    public function store(Request $request)
    {
        $json = json_decode($request->name);

        if (Category::query()->where(['category' => $json->type])->count() == 0)
            Category::create(['category' => $json->type]);

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
