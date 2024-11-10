<?php

namespace App\Http\Controllers\Api\v1_0;

use App\Http\Controllers\Controller;
use App\Http\Requests\Genre\StoreGenreRequest;
use App\Http\Requests\Genre\UpdateGenreRequest;
use App\Http\Requests\TitleGenre\TitleGenreRequest;
use App\Http\Requests\TitleGenre\TitleGenreStoreRequest;
use App\Http\Resources\GenreResource;
use App\Models\Genre;
use App\Models\Title;
use Illuminate\Support\Facades\DB;

class TitleGenreController extends Controller
{
    /**
     * Получение всех жанров тайтла
     *
     * @param string $title_slug
     * @return void
     */
    public function index(string $title_slug)
    {
        return GenreResource::collection(Title::query()->where(['slug' => $title_slug])->first()->genres);
    }

    /**
     * Добавление жанров
     *
     * @param StoreGenreRequest $request
     * @param string $title_slug
     * @return void
     */
    public function store(TitleGenreStoreRequest $request, string $title_slug): void
    {
        $genres = $request->validated()['genres'];

        foreach ($genres as $genre) {
            DB::transaction(function () use ($genre, $title_slug) {
                $genre_id = Genre::query()->where(['genre' => $genre])?->value('id');

                if (empty($genre_id))
                    return response()->json(['error' => 'Запись не найдена', 400]);

                Title::query()
                    ->where(['slug' => $title_slug])
                    ->first()
                    ->genres()
                    ->attach($genre_id, ['updated_at' => now()]);
            });
        }
    }

    /**
     * Получение конкретного жанра тайтла
     *
     * @param string $title_slug
     * @param string $genre_slug
     * @return void
     */
    public function show(string $title_slug, string $genre_slug)
    {
        $genre_id = Genre::query()
            ->where(['slug' => $genre_slug])
            ->value('id');

        return new GenreResource(Title::query()->where(['slug' => $title_slug])->first()->genres()->where(['genre_id' => $genre_id])->first());
    }

    /**
     * Обновление жанра
     *
     * @param UpdateGenreRequest $request
     * @param string $title_slug
     * @param string $genre_slug
     * @return void
     */
    public function update(TitleGenreRequest $request, string $title_slug, string $genre_slug)
    {
        $genre = $request->validated()['genre'];

        $title_id = Title::query()->where(['slug' => $title_slug])?->value('id');
        $genre = Genre::query()->where(['slug' => $genre_slug])?->first();
        $genre_id = Genre::query()->where(['genre' => $genre])?->value('id');

        if (empty($title_id) || empty($genre) || empty($genre_id))
            return response()->json(['error' => 'Запись не найдена']);

        Title::query()
            ->where(['slug' => $title_slug])
            ->first()
            ->genres()
            ->syncWithoutDetaching([$title_id => ['genre_id' => $genre_id, 'updated_at' => now()]]);

        return response(null, 204);
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
        if (Genre::query()->where(['slug' => $genre_slug])->count() == 0)
            return response(['error' => 'Запись не найдена'], 400);

        Genre::query()
            ->where(['slug' => $genre_slug])
            ->first()
            ->title()
            ->detach();

        response(null, 201);
    }
}
