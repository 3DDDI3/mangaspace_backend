<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Title extends Model
{
    use HasFactory;

    protected $connection = "temp";

    protected $table = "titles";

    protected $fillable = [
        'category_id',
        'ru_name',
        'eng_name',
        'other_names',
        'description',
        'title_status_id',
        'translate_status_id',
        'release_year',
        'slug',
    ];

    /**
     * Категория
     *
     * @return BelongsTo
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Статус тайтла
     *
     * @return BelongsTo
     */
    public function titleStatus(): BelongsTo
    {
        return $this->belongsTo(TitleStatus::class);
    }

    /**
     * Формат релиза
     *
     * @return BelongsTo
     */
    public function releaseFormat(): BelongsTo
    {
        return $this->belongsTo(ReleaseFormat::class);
    }

    /**
     * Статус перевода
     *
     * @return BelongsTo
     */
    public function translateStatus(): BelongsTo
    {
        return $this->belongsTo(TranslateStatus::class);
    }

    /**
     * Персоны
     *
     * @return BelongsToMany
     */
    public function persons(): BelongsToMany
    {
        return $this->belongsToMany(Person::class, TitlePerson::class);
    }

    public function chapters(): HasMany
    {
        return $this->hasMany(Chapter::class);
    }

    /**
     * Жанры
     *
     * @return BelongsToMany
     */
    public function genres(): BelongsToMany
    {
        return $this->belongsToMany(Genre::class, TitleGenre::class);
    }
}
