<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PersonType extends Model
{
    use HasFactory;

    protected $connection = 'temp';

    protected $table = "person_types";

    protected $fillable = [
        'type',
    ];
}
