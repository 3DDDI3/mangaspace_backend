<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeviceToken extends Model
{
    use HasFactory;

    protected $table = "device_token";

    protected $fillable = [
        'personal_access_token_id',
        'device_type_id',
        'client',
        'operation_system',
        'ip_address',
        'model',
        'browser',
        'browser_version',
        'operation_system',
        'country',
        'city',
    ];
}
