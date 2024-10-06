<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeviceToken extends Model
{
    use HasFactory;

    protected $table = "divice_token";

    protected $fillable = [
        'personal_access_token_id',
        'client',
        'name',
        'operation_system',
        'country',
        'city',
    ];
}
