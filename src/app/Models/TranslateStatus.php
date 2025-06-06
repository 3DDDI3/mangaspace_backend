<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TranslateStatus extends Model
{
    use HasFactory;

    protected $connection = "temp";

    protected $table = "translate_statuses";

    protected $fillable = [
        'status',
    ];
}
