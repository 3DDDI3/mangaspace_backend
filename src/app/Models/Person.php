<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Person extends Model
{
    use HasFactory;

    protected $connection = "temp";

    protected $table = "persons";

    protected $fillable = [
        'name',
        'alt_name',
        'slug',
        'description',
        'person_type_id',
    ];

    /**
     * Тип пользователя
     *
     * @return BelongsTo
     */
    public function type(): BelongsTo
    {
        return $this->belongsTo(PersonType::class);
    }

    /**
     * Тайтлы персоны
     *
     * @return BelongsToMany
     */
    public function title(): BelongsToMany
    {
        return $this->belongsToMany(Title::class, TitlePerson::class);
    }
}
