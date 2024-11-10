<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Genre extends Model
{
    use HasFactory;

    protected $connection = "temp";

    protected $table = "genres";

    protected $fillable = [
        'genre',
        'slug'
    ];

    public function title(): BelongsToMany
    {
        return $this->belongsToMany(Title::class, TitleGenre::class);
    }
}
