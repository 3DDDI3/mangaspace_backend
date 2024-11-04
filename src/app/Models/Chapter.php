<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chapter extends Model
{
    use HasFactory;

    protected $connection = "temp";

    protected $table = "chapters";

    protected $fillable = [
        'path',
        'volume',
        'number',
        'name',
        'title_id',
    ];
}
