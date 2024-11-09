<?php

namespace App\Http\Controllers;

use App\Http\Requests\Genre\StoreGenreRequest;
use App\Http\Requests\Genre\UpdateGenreRequest;
use App\Models\Genre;
use App\Models\Title;
use App\Models\TitleGenre;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class TitleGenreController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Добавление жанров
     *
     * @param StoreGenreRequest $request
     * @param string $title_slug
     * @return void
     */
    public function store(StoreGenreRequest $request, string $title_slug): void
    {
        $data = $request->validated();

        foreach ($data['genres'] as $item) {
            DB::transaction(function () use ($item, $title_slug) {
                if (Genre::query()->where(['genre' => $item])->count() == 0) {
                    $genre_id = Genre::query()
                        ->create(['genre' => $item, 'slug' => Str::slug($item)])
                        ->id;

                    $title_id = Title::query()
                        ->where(['slug' => $title_slug])
                        ->value('id');

                    if (empty($title_id))
                        return response()->json(['error' => 'Тайтл не найден'], 400);

                    if (TitleGenre::query()->where(['title_id' => $title_id, 'genre_id' => $genre_id])->count() == 0)
                        TitleGenre::query()->create([
                            'title_id' => $title_id,
                            'genre_id' => $genre_id,
                        ]);

                    return response(null, 201);
                } else {
                    $genre_id = TitleGenre::query()
                        ->where(['genre' => $item])
                        ->value('id');

                    if (empty($genre_id))
                        return response()->json(['error' => 'Жанр не найден'], 400);

                    $title_id  = Title::query()
                        ->where(['slug' => $title_slug])
                        ->value('id');

                    if (empty($title_id))
                        return response()->json(['error' => 'Тайтл не найден'], 400);

                    if (TitleGenre::query()->where(['title_id' => $title_id, 'genre_id' => $genre_id])->count() == 0)
                        TitleGenre::query()->create([
                            'title_id' => $title_id,
                            'genre_id' => $genre_id,
                        ]);
                }
            });
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Обновление жанров
     *
     * @param UpdateGenreRequest $request
     * @param string $title_slug
     * @param string $genre_slug
     * @return void
     */
    public function update(UpdateGenreRequest $request, string $title_slug, string $genre_slug)
    {
        $request->validated();

        dd(Genre::query()->where(['slug' => $genre_slug])->first()->title()->sync([1 => ['title_id' => 1, 'genre_id' => 1, 'updated_at' => now()]]));

        $title_id = Title::query()->where(['slug' => $title_slug])?->value('id');

        if (empty($title_id))
            return response()->json(['error' => 'Тайтл не найден'], 400);

        $genre_id = Genre::query()
            ->where(['slug' => $genre_slug])
            ->value('id');

        /**
         * @todo Доделать реализацию обновления
         */
    }

    /**
     * Удаление жанра
     *
     * @param string $title_slug
     * @param string $genre_slug
     * @return void
     */
    public function destroy(string $title_slug, string $genre_slug)
    {
        if (Genre::query()->where(['slug' => $genre_slug])->count == 0)
            return response(['error' => 'Запись не найдена'], 400);

        Genre::query()
            ->where(['slug' => $genre_slug])
            ->first()
            ->title()
            ->detach();

        response(null, 201);
    }
}
