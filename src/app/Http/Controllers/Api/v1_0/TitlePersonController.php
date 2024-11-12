<?php

namespace App\Http\Controllers\Api\v1_0;

use App\Http\Controllers\Controller;
use App\Http\Requests\Person\PersonUpdateRequest;
use Illuminate\Support\Str;
use App\Http\Requests\Person\StorePersonRequest;
use App\Http\Requests\TitlePerson\TitlePersonStoreRequest;
use App\Http\Resources\PersonResource;
use App\Models\Person;
use App\Models\Title;
use Illuminate\Support\Facades\DB;

class TitlePersonController extends Controller
{
    /**
     * Получение всех персон
     *
     * @param string $title_slug
     * @return void
     */
    public function index(string $title_slug)
    {
        return PersonResource::collection(Title::query()->where(['slug' => $title_slug])->first()->persons);
    }

    /**
     * Создание персоны
     *
     * @param StorePersonRequest $request
     * @param string $slug
     * @return void
     */
    public function store(TitlePersonStoreRequest $request, string $title_slug)
    {
        $persons = $request->validated()["persons"];


        DB::transaction(function () use ($persons, $title_slug) {
            foreach ($persons as $person) {
                $person_id = Person::query()->where(['slug' => $person])->value('id');
                if (empty($person_id)) continue;
                $title = Title::query()->where(['slug' => $title_slug])->first();

                $title->persons()->attach($title->id, ['person_id' => $person_id, 'updated_at' => now()]);
            }
        });


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
        $person_id = Person::query()->where(['slug' => $person_slug])->value('id');
        return new PersonResource(Title::query()->where(['slug' => $title_slug])->first()->persons()->wherePivot('person_id', '=', $person_id)->first());
    }

    /**
     * Обновление персоны
     *
     * @param UpdatePersonRequest $request
     * @param string $title_slug
     * @param string $person_slug
     * @return void
     */
    public function update(PersonUpdateRequest $request, string $title_slug, string $person_slug)
    {
        $persons = $request->validated()['persons'];

        foreach ($persons as $person) {
            $person_id = Person::query()
                ->where(['slug' => $person])
                ->value('id');

            $title = Title::query()
                ->where(['slug' => $title_slug])
                ->first();

            $title->persons()->sync([$title->id => ['person_id' => $person_id]], false);
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
