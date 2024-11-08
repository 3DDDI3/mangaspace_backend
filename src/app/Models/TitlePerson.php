<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TitlePerson extends Model
{
    use HasFactory;

    protected $connection = "temp";

    protected $table = "title_persons";

    protected $fillable = [
        'title_id',
        'person_id'
    ];

    public function title(): BelongsTo
    {
        return $this->belongsTo(Title::class);
    }

    public function person(): BelongsTo
    {
        return $this->belongsTo(Person::class);
    }
}
