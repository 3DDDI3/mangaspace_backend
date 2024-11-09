<?php

namespace App\Http\Controllers;

use App\Enums\PersonType;
use Illuminate\Support\Str;
use App\Http\Requests\Person\StorePersonRequest;
use App\Http\Requests\Person\UpdatePersonRequest;
use App\Http\Resources\PersonResource;
use App\Models\Person;
use App\Models\Title;
use App\Models\TitlePerson;
use Illuminate\Support\Facades\DB;

class PersonController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index() {}

    /**
     * Создание персоны
     *
     * @param StorePersonRequest $request
     * @param string $slug
     * @return void
     */
    public function store(StorePersonRequest $request, string $slug)
    {
        $data = $request->validated()["persons"];

        foreach ($data as $item) {
            DB::transaction(function () use ($item, $slug) {
                if (Person::query()->where(['name' => $item['name']])->count() == 0) {
                    $person_id = Person::query()
                        ->create([
                            'name' => $item['name'],
                            'slug' => Str::slug($item['name']),
                            'alt_name' => $item['altName'],
                            'description' => $item['description'],
                            'person_type_id' => PersonType::from($item['type']),
                        ])->id;

                    $title_id = Title::query()
                        ->where(['slug' => $slug])
                        ->value('id');

                    if (TitlePerson::query()->where(['title_id' => $title_id, 'person_id' => $person_id])->count() == 0)
                        TitlePerson::query()->create(['title_id' => $title_id, 'person_id' => $person_id]);
                } else {
                    $person_id = Person::query()
                        ->where(['name' => $item['name']])
                        ->value('id');

                    $title_id = Title::query()
                        ->where(['slug' => $slug])
                        ->value('id');

                    if (TitlePerson::query()->where(['title_id' => $title_id, 'person_id' => $person_id])->count() == 0)
                        TitlePerson::query()->create(['title_id' => $title_id, 'person_id' => $person_id]);
                }
            });
        }

        return response(null, 204);
    }

    /**
     * Возврат конкретной персоны
     *
     * @param string $title_slug
     * @param string $person_slug
     * @return void
     */
    public function show(string $title_slug, string $person_slug)
    {
        $person = Person::query()
            ->where(['slug' => $person_slug])
            ->first();

        return new PersonResource($person);
    }

    /**
     * Обновление персоны
     *
     * @param UpdatePersonRequest $request
     * @param string $title_slug
     * @param string $person_slug
     * @return void
     */
    public function update(UpdatePersonRequest $request, string $title_slug, string $person_slug)
    {
        $persons = $request->validated()['persons'];

        foreach ($persons as $person) {
            if (Person::query()->where(['slug' => $person['slug']])->count() == 0)
                return response()->json(['error' => 'Person not found'], 400);

            DB::transaction(function () use ($person) {
                Person::query()
                    ->where(['slug' => $person['slug']])
                    ->first()
                    ->fill([
                        'name' => $person['name'],
                        'slug' => Str::slug($person['slug']),
                        'type' => $person['type'],
                        'alt_ame' => $person['altName'],
                        'description' => $person['description'],
                    ])
                    ->save();
            });
        }
    }

    /**
     * Удаление персоны
     *
     * @param string $title_slug
     * @param string $person_slug
     * @return void
     */
    public function destroy(string $title_slug, string $person_slug)
    {
        if (Person::query()->where(['slug' => $person_slug])->count() == 0)
            return response()->json(['error' => 'Запись не найдена'], 400);

        Person::query()
            ->where(['slug' => $person_slug])
            ->first()
            ->title()
            ->detach();

        return response(null, 204);
    }
}
