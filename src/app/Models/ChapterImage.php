<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChapterImage extends Model
{
    use HasFactory;

    protected $connection = "temp";

    protected $table = "title_chapters";

    protected $fillable = [
        'path',
        'extensions',
        'chapter_id',
        'title_id',
        'person_id',
    ];
}
