<?php

namespace App\Http\Controllers\Api\v1_0;

use App\Filters\PersonFilter;
use App\Http\Controllers\Controller;
use App\Http\Requests\Person\PersonUpdateRequest;
use Illuminate\Support\Str;
use App\Http\Requests\Person\StorePersonRequest;
use App\Http\Requests\TitlePerson\TitlePersonStoreRequest;
use App\Http\Resources\PersonResource;
use App\Models\Person;
use App\Models\PersonPhoto;
use App\Models\Title;
use Illuminate\Support\Facades\DB;

class TitlePersonController extends Controller
{
    public function index(PersonFilter $filter, string $title_slug)
    {
        $persons =  Title::query()
            ->where(['slug' => $title_slug])
            ->first()
            ->persons()
            ->filter($filter)
            ->get();

        return PersonResource::collection($persons);
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
        $persons = $request->validated();

        DB::transaction(function () use ($persons, $title_slug) {
            foreach ($persons as $person) {
                $person_id = Person::query()
                    ->where(['name' => $person['name']])
                    ->value('id');

                if (empty($person_id))
                    $person_id = Person::query()
                        ->create([
                            'name' => $person['name'],
                            'slug' => empty($person['altName']) ? Str::slug($person['name']) : $person['altName'],
                            'alt_name' => $person['altName'],
                            'description' => $person['description'],
                            'person_type_id' => $person['type'],
                        ])
                        ->id;

                $title = Title::query()
                    ->where(['slug' => $title_slug])
                    ->first();

                $title->persons()
                    ->syncWithoutDetaching([$person_id => ['updated_at' => now()]]);

                foreach ($person['images'] as $image) {
                    if (empty($image['path']) || empty($image['extension']))
                        continue;

                    $person = Person::query()->where(['name' => $person['name']])->first();
                    if (PersonPhoto::query()->where(['person_id' => $person->id, 'path' => "{$image['path']}.{$image['extension']}"])->count() == 0)
                        PersonPhoto::query()->create(['path' => "{$image['path']}.{$image['extension']}", 'person_id' => $person->id]);
                }
            }
        });


        return response(null, 204);
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
