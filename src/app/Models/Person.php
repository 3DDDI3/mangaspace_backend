<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Person extends Model
{
    use HasFactory;

    protected $connection = "temp";

    protected $table = "persons";

    protected $fillable = [
        'name',
        'alt_name',
        'slug',
        'description',
        'person_type_id',
    ];
}
