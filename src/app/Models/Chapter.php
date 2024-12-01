<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

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
    ];

    public function title()
    {
        return $this->belongsTo(Title::class);
    }

    public function images(): HasMany
    {
        return $this->hasMany(ChapterImage::class);
    }
}
