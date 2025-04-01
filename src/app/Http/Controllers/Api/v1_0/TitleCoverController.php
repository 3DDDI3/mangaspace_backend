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
     */
    public function store(Request $request, $slug)
    {
        Storage::disk('shared')->putFile('titles/Podnyatie urovnya v odinochku/', $request->file('title'));

        return response(null, 200);

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
