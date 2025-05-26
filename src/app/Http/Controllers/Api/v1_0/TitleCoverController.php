<?php

namespace App\Http\Controllers\Api\v1_0;

use App\Http\Controllers\Controller;
use App\Http\Requests\TitleCover\TitleCoverStoreRequest;
use App\Models\Title;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TitleCoverController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index() {}

    /**
     * Store a newly created resource in storage.
     * 
     * @OA\Post(
     *      path="/v1.0/titles/{title}/covers",
     *      operationId="storeTitleCover",
     *      tags={"TitleCovers"},
     *      summary="Список обложек",
     *      description="Получение списка обложек тайтла",
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
    public function store(TitleCoverStoreRequest $request, $slug)
    {
        $data = $request->validated();

        $title = Title::query()
            ->where(['slug' => $slug])
            ->first();

        foreach ($data as $cover) {
            $title->covers()
                ->firstOrNew([
                    'title_id' => $title->id,
                    'path' => preg_replace('#\\\\+#', '/', $cover['path']) . "." . $cover['extension']
                ])
                ->save();
        }

        return response(null, 200);
    }

    /**
     * Display the specified resource.
     * 
     * @OA\Get(
     *      path="/v1.0/titles/{title}/covers/{cover}",
     *      operationId="showTitleCover",
     *      tags={"TitleCovers"},
     *      summary="Список обложек",
     *      description="Получение списка обложек тайтла",
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
