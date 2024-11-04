<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Title extends Model
{
    use HasFactory;

    protected $connection = "temp";

    protected $table = "titles";

    protected $fillable = [
        'category_id',
        'ru_name',
        'eng_name',
        'other_names',
        'description',
        'title_status_id',
        'translate_status_id',
        'release_year',
    ];
}
