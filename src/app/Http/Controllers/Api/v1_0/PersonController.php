<?php

namespace App\Http\Controllers\Api\v1_0;

use App\Enums\PersonType;
use App\Http\Controllers\Controller;
use App\Http\Requests\Person\PersonStoreRequest;
use App\Http\Requests\Person\PersonUpdateRequest;
use App\Http\Resources\PersonResource;
use App\Models\Person;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class PersonController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return PersonResource::collection(Person::all());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(PersonStoreRequest $request)
    {
        $persons = $request->validated()['persons'];

        foreach ($persons as $person) {
            Person::query()
                ->create([
                    'name' => $person['name'],
                    'slug' => Str::slug($person['name']),
                    'alt_name' => $person['altName'],
                    'description' => $person['description'],
                    'person_type_id' => PersonType::from($person['type']),
                ]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $person_slug)
    {
        return new PersonResource(Person::query()->where(['slug' => $person_slug])->first());
    }

    /**
     * Обновление персоны
     *
     * @param PersonUpdateRequest $request
     * @param string $person_slug
     * @return void
     */
    public function update(PersonUpdateRequest $request, string $person_slug)
    {
        $persons = $request->validated()['persons'];

        foreach ($persons as $person) {
            if (Person::query()->where(['slug' => $person_slug])->count() == 0)
                return response()->json(['error' => 'Запись не найдена'], 400);

            Person::query()
                ->where(['slug' => $person_slug])
                ->first()
                ->fill([
                    'name' => $person['name'],
                    'slug' => Str::slug($person['name']),
                    'alt_name' => $person['altName'],
                    'description' => $person['description'],
                    'person_type_id' => PersonType::from($person['type']),
                ])
                ->save();
        }

        return response(null, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $person_slug)
    {
        if (Person::query()->where(['slug' => $person_slug])->count() == 0)
            return response()->json(['error' => 'Запись не найдена'], 400);

        Person::query()
            ->where(['slug' => $person_slug])
            ->first()
            ->delete();

        return response(null, 200);
    }
}
