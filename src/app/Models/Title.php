<?php

namespace App\Models;

use App\Observers\TitleObserver;
use App\Traits\HasFilter;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[ObservedBy(TitleObserver::class)]
class Title extends Model
{
    use HasFactory, HasFilter;

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
        'path',
        'rating',
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

    public function chapterPersons()
    {
        return $this->hasManyThrough(ChapterImage::class, Chapter::class);
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

    /**
     * Главы
     *
     * @return HasMany
     */
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

    public function covers(): HasMany
    {
        return $this->hasMany(TitleCover::class);
    }
}
