<?php

namespace App\Http\Controllers\Api\v1_0;

use Illuminate\Support\Str;
use App\Enums\TitleStatus as EnumsTitleStatus;
use App\Enums\TranslateStatus as EnumsTranslateStatus;
use App\Filters\TitleFilter;
use App\Http\Controllers\Controller;
use App\Http\Requests\Title\TitleStoreRequest;
use App\Http\Requests\Title\TitleUpdateRequest;
use App\Http\Requests\TitleShowRequest;
use App\Http\Resources\TitleResource;
use App\Models\Category;
use App\Models\Title;
use Illuminate\Support\Facades\DB;

class TitleController extends Controller
{
    /**
     * Display a listing of the resource.
     * 
     * @OA\Get(
     *      path="/v1.0/titles",
     *      operationId="getTitlesList",
     *      tags={"Titles"},
     *      summary="Список тайтлов",
     *      description="Получение списка тайтлов",
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(ref="#/components/schemas/TitleResource")
     *       ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      ),
     *      @OA\Response(
     *          response=422,
     *          description="Unprocessable Content"
     *      )
     * )
     * 
     */
    public function index(TitleShowRequest $request, TitleFilter $filter)
    {
        $offset = !empty($request->offset) ? $request->offset : 10;
        $titles = Title::query()
            ->filter($filter)
            ->paginate($offset);

        if ($titles->count() == 0)
            return response([], 204);

        return TitleResource::collection($titles);
    }

    /**
     * Store a newly created resource in storage.
     * 
     * @OA\Post(
     *      path="/v1.0/titles",
     *      operationId="storeTitle",
     *      tags={"Titles"},
     *      summary="Добавление нового тайтла",
     *      description="Возвращает пустой массив",
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(ref="#/components/schemas/TitleStoreRequest")
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *       ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      ),
     *      @OA\Response(
     *          response=422,
     *          description="Unprocessable Content"
     *      )
     * )
     */
    public function store(TitleStoreRequest $request)
    {
        $data = $request->validated();

        DB::transaction(function () use ($data) {
            Title::query()
                ->create([
                    'category_id' => Category::query()->where(['category' => $data['type']])->first() == null
                        ? Category::query()->create(['category' => $data['type']])->id
                        : Category::query()->where(['category' => $data['type']])->first('id')->id,
                    'ru_name' => $data['name'],
                    'slug' => empty($data['altName']) ? Str::slug($data['name']) : Str::slug($data['altName']),
                    'path' => preg_replace('#\\\\+#', '/', $data['path']),
                    'eng_name' => $data['altName'],
                    'other_names'  => $data['otherNames'],
                    'description' => $data['description'],
                    'title_status_id' => EnumsTitleStatus::from($data['titleStatus']),
                    'translate_status_id' => EnumsTranslateStatus::from($data['translateStatus']),
                    'release_year' => $data['releaseYear'],
                    'country' => $data['country'],
                ]);

            return response(null, 201);
        });
    }

    /**
     * Display the specified resource.
     * 
     * @OA\Get(
     *      path="/v1.0/titles/{slug}",
     *      operationId="getTitle",
     *      tags={"Titles"},
     *      summary="Получение тайтла",
     *      description="Возвращает пустой массив",
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(ref="#/components/schemas/TitleResource")
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      ),
     *      @OA\Response(
     *          response=422,
     *          description="Unprocessable Content"
     *      )
     * )
     */
    public function show(string $slug)
    {
        return new TitleResource(Title::query()->where(['slug' => $slug])->first());
    }

    /**
     * Update the specified resource in storage.
     * 
     * @OA\Put(
     *      path="/v1.0/titles/{slug}",
     *      operationId="putTitle",
     *      tags={"Titles"},
     *      summary="Изменение информации о тайтле",
     *      description="Возвращает пустой массив",
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(ref="#/components/schemas/TitleUpdateRequest")
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *       ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      ),
     *      @OA\Response(
     *          response=422,
     *          description="Unprocessable Content"
     *      )
     * )
     * 
     * @OA\Patch(
     *      path="/v1.0/titles/{slug}",
     *      operationId="patchTitle",
     *      tags={"Titles"},
     *      summary="Изменение информации о тайтле",
     *      description="Возвращает пустой массив",
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(ref="#/components/schemas/TitleUpdateRequest")
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *       ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      ),
     *      @OA\Response(
     *          response=422,
     *          description="Unprocessable Content"
     *      )
     * )
     */
    public function update(TitleUpdateRequest $request, string $slug)
    {
        $data = $request->validated();

        $title = Title::query()->where(['slug' => $slug])->first();

        $title->fill([
            'category_id' => Category::query()->where(['id' => $data['type']])->first('id')->id,
            'ru_name' => $data['name'],
            'slug' => empty($data['altName']) ? Str::slug($data['name']) : Str::slug($data['altName']),
            'eng_name' => $data['altName'],
            'other_names'  => $data['otherNames'],
            'description' => $data['description'],
            'title_status_id' => EnumsTitleStatus::from($data['titleStatus'])->value,
            'translate_status_id' => EnumsTranslateStatus::from($data['translateStatus'])->value,
            'release_year' => $data['releaseYear'],
            'country' => $data['country'] ?? null,
        ])->save();

        return  new TitleResource($title);
    }

    /**
     * Remove the specified resource from storage.
     * 
     * @OA\Delete(
     *      path="/v1.0/titles/{slug}",
     *      operationId="deleteTitle",
     *      tags={"Titles"},
     *      summary="Добавление нового тайтла",
     *      description="Возвращает пустой массив",
     *      @OA\Parameter(
     *          name="slug",
     *          in="path",
     *          required=true,
     *          description="Ссылка на тайтл",
     *          @OA\Schema(type="string")
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *       ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      ),
     *      @OA\Response(
     *          response=422,
     *          description="Unprocessable Content"
     *      )
     * )
     */
    public function destroy(string $slug)
    {
        $title = Title::query()
            ->where(['slug' => $slug])
            ->first();

        Title::destroy($title->id);

        return response(null, 200);
    }
}
