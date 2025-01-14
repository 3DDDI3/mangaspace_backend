<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ChapterImage extends Model
{
    use HasFactory;

    protected $connection = "temp";

    protected $table = "chapter_images";

    protected $fillable = [
        'path',
        'extensions',
        'chapter_id',
        'person_id',
    ];

    public function chapter(): BelongsTo
    {
        return $this->belongsTo(Chapter::class);
    }

    public function translator(): BelongsTo
    {
        return $this->belongsTo(Person::class, 'person_id', 'id');
    }
}
