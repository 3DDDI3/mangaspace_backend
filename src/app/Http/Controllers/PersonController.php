<?php

namespace App\Http\Controllers;

use App\Enums\PersonType;
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
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePersonRequest $request, string $title)
    {
        $data = $request->validated()['persons'];

        foreach ($data as $item) {
            DB::transaction(function () use ($item, $title) {
                if (Person::query()->where(['name' => $item['name']])->count() == 0)
                    Person::query()->create([
                        'name' => $item['name'],
                        'alt_name' => $item['altName'],
                        'description' => $item['description'],
                        'person_type_id' => PersonType::from($item['type']),
                    ]);
                else {
                    $id = Person::query()->where(['name' => $item['name']])->value('id');
                    if (TitlePerson::query()->where(['title_id' => $title, 'person_id' => $id])->count() == 0)
                        TitlePerson::query()->create(['title_id' => $title, 'person_id' => $id]);
                }
            });
        }

        return response(null, 201);
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
