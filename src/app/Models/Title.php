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

/**
 * @OA\Schema(
 *      title="Title",
 *      description="Title model",
 *      @OA\Xml(
 *          name="Title"
 *      )
 * )
 */
#[ObservedBy(TitleObserver::class)]
class Title extends Model
{
    use HasFactory, HasFilter;

    /**
     * @OA\Property(
     *      title="category_id",
     *      description="ID категории",
     *      type="integer",
     *      example=1
     * )
     *
     * @var category_id
     */
    private $category_id;

    /**
     * @OA\Property(
     *      title="ru_name",
     *      description="Русское название",
     *      type="string",
     *      example="Поднятие уровня в одиночку"
     * )
     *
     * @var ru_name
     */
    private $ru_name;

    /**
     * @OA\Property(
     *      title="eng_name",
     *      description="Английское название",
     *      type="string",
     *      example="Solo Leveling"
     * )
     *
     * @var eng_name
     */
    private $eng_name;

    /**
     * @OA\Property(
     *      title="slug",
     *      description="Ссылка",
     *      type="string",
     *      example="solo-leveling"
     * )
     *
     * @var slug
     */
    private $slug;

    /**
     * @OA\Property(
     *      title="path",
     *      description="Путь до папки",
     *      type="string",
     *      example="titles/Podnyatie urovnya v odinochku"
     * )
     *
     * @var path
     */
    private $path;

    /**
     * @OA\Property(
     *      title="other_names",
     *      description="Альтернативные названия",
     *      type="string",
     *      example="I Alone Level-Up,I Level Up Alone,I alone level up,Na Honjaman Level Up,Na Honjaman Rebereop,Only I Level Up,Ore Dake Level Up na Ken,Соло Левелинг,Я один повышаю свой уровень,俺だけレベルアップな件,我独自升级,나 혼자만 레벨업"
     * )
     *
     * @var other_names
     */
    private $other_names;

    /**
     * @OA\Property(
     *      title="release_format",
     *      description="Формат релиза",
     *      type="integer",
     *      example="2"
     * )
     *
     * @var release_format
     */
    private $release_format;

    /**
     * @OA\Property(
     *      title="description",
     *      description="Описание",
     *      type="string",
     *      example=""
     * )
     *
     * @var description
     */
    private $description;

    /**
     * @OA\Property(
     *      title="title_status",
     *      description="Статус тайтла",
     *      type="integer",
     *      example="10 лет назад раскрылись врата в другой мир, где людям дозволено убивать монстров. Так появились охотники, преследующие и уничтожающие тварей. Но не каждому из них суждено повысить свой уровень и стать сильнее. Сон Джин Ву был охотником низшего E-ранга, у которого не было ни единого шанса продвинуться по ранговой лестнице, пока однажды он случайно не очутился в подземелье D-ранга. Чуть не погибнув от рук сильнейших чудовищ, Джин Ву открывает секрет повышения уровня, известный только ему одному. Теперь даже самый слабый может стать сильнейшим."
     * )
     *
     * @var title_status
     */
    private $title_status;

    /**
     * @OA\Property(
     *      title="translate_status",
     *      description="Статус перевода",
     *      type="integer",
     *      example="3"
     * )
     *
     * @var translate_status
     */
    private $translate_status;

    /**
     * @OA\Property(
     *     title="release_year",
     *     description="Год релиза",
     *     type="integer",
     *     example="2"
     * )
     *
     * @var release_year
     */
    private $release_year;

    /**
     * @OA\Property(
     *      title="country",
     *      description="Страна",
     *      type="integer",
     *      example="2018"
     * )
     *
     * @var country
     */
    private $country;

    /**
     * @OA\Property(
     *      title="is_hide",
     *      description="Флаг скрытия",
     *      type="boolean",
     *      example="0"
     * )
     *
     * @var is_hide
     */
    private $is_hide;

    /**
     * @OA\Property(
     *      title="rating",
     *      description="Ранжирование в выдаче",
     *      type="boolean",
     *      example="5"
     * )
     *
     * @var rating
     */
    private $rating;

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
        'is_hide'
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
