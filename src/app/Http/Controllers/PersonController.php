<?php

namespace App\Http\Controllers;

use App\Enums\PersonType;
use Illuminate\Support\Str;
use App\Http\Requests\Person\StorePersonRequest;
use App\Models\Person;
use App\Models\Title;
use App\Models\TitlePerson;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PersonController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        
    }

    /**
     * Store a newly created resource in storage.
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
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $title_slug, string $person_person)
    {
        $data = $request->validated();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $title_slug, string $person_slug)
    {
        $title_id = Title::query()
            ->where(['slug' => $title_slug])
            ->value('id');

        $person_id = Person::query()
            ->where(['slug' => $person_slug])
            ->value('id');

        TitlePerson::query()
            ->where(['title_id' => $title_id, 'person_id' => $person_id])
            ->delete();

        return response(null, 204);
    }
}
