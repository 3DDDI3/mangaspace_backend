<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PersonPhoto extends Model
{
    use HasFactory;

    protected $connection = "temp";

    protected $table = "person_photos";

    protected $fillable = [
        'path',
        'person_id',
    ];
}
