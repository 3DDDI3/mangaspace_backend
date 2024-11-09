<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

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

    public function type()
    {
        return $this->belongsTo(PersonType::class, 'person_type_id');
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

    public function photos(): HasMany
    {
        return $this->hasMany(PersonPhoto::class);
    }
}
