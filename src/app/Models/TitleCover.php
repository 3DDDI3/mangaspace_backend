<?php

namespace App\Models;

use App\Observers\TitleCoverObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[ObservedBy(TitleCoverObserver::class)]
class TitleCover extends Model
{
    use HasFactory;

    protected $connection = "temp";

    protected $table = "title_covers";

    protected $fillable = [
        'path',
        'title_id',
    ];

    protected function title(): BelongsTo
    {
        return $this->belongsTo(Title::class);
    }
}
