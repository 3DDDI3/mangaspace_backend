<?php

namespace App\Models;

use App\Observers\ChapterObserver;
use App\Traits\HasFilter;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[ObservedBy(ChapterObserver::class)]
class Chapter extends Model
{
    use HasFactory, HasFilter;

    protected $connection = "temp";

    protected $table = "chapters";

    protected $fillable = [
        'path',
        'volume',
        'number',
        'name',
        'title_id'
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
