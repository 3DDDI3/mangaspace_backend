<?php

namespace App\Http\Controllers\Api\v1_0;

use App\Http\Controllers\Controller;
use App\Http\Requests\ChapterImage\ChapterImageStoreRequest;
use App\Models\ChapterImage;
use Illuminate\Http\Request;

class ChapterImageController extends Controller
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
    public function store(ChapterImageStoreRequest $request, string $title_slug, string $chapter_number)
    {
        $chapter_images = $request->validated();
        dd($chapter_images, $title_slug, $chapter_number);

        ChapterImage::query();
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
