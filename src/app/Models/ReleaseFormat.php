<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReleaseFormat extends Model
{
    use HasFactory;

    protected $connection = "temp";

    protected $table = "release_formats";
}
