<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TitleCover extends Model
{
    use HasFactory;

    protected $connection = "temp";

    protected $table = "title_covers";

    protected $fillable = [
        'path',
        'title_id',
    ];
}
