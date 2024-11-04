<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TitlePerson extends Model
{
    use HasFactory;

    protected $connection = "temp";

    protected $table = "title_persons";

    protected $fillable = [
        'title_id',
        'person_id'
    ];
}
