<?php

namespace App\Http\Controllers\Api\v1_0;

use App\Http\Controllers\Controller;
use App\Http\Requests\Genre\GenreStoreRequest;
use App\Http\Requests\Genre\GenreUpdateRequest;
use App\Http\Resources\GenreResource;
use App\Models\Genre;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class GenreController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return GenreResource::collection(Genre::all());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(GenreStoreRequest $request)
    {
        $genres = $request->validated()['genres'];

        foreach ($genres as $genre) {
            Genre::query()->create(['genre' => $genre, 'slug' => Str::slug($genre)]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $genre_slug)
    {
        return new GenreResource(Genre::query()->where(['slug' => $genre_slug])->first());
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(GenreUpdateRequest $request, string $id)
    {
        $genre = $request->validated()['genre'];

        Genre::query()
            ->where(['slug' => Str::slug($genre)])
            ->first()
            ->fill(['genre' => $genre, 'slug' => Str::slug($genre)])
            ->save();

        return response(null, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $genre_slug)
    {
        if (Genre::query()->where(['slug' => $genre_slug])->count() == 0)
            return response()->json(['error' => 'Запись не найдена'], 400);

        Genre::query()
            ->where(['slug' => $genre_slug])
            ->first()
            ->delete();
    }
}
