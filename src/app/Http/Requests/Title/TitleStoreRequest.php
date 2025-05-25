<?php

namespace App\Http\Requests\Title;

use App\Enums\AgeLimiter;
use App\Enums\TitleStatus;
use App\Enums\TranslateStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

/**
 * @OA\Schema(
 *      title="Store Title request",
 *      description="Store Title request body data",
 *      type="object",
 *      required={"name"}
 * )
 */
class TitleStoreRequest extends FormRequest
{
    /**
     * @OA\Property(
     *      title="name",
     *      description="Русское назавние тайтла",
     *      example="Поднятие уровня в одиночку"
     * )
     *
     * @var string
     */
    public $name;

    /**
     * @OA\Property(
     *      title="altName",
     *      description="Английское назавние тайтла",
     *      example="Solo Leveling"
     * )
     *
     * @var string
     */
    public $altName;

    /**
     * @OA\Property(
     *      title="path",
     *      description="Путь",
     *      example="Podnyatie urovnya v odinochku"
     * )
     *
     * @var string
     */
    public $path;

    /**
     * @OA\Property(
     *      title="slug",
     *      description="Ссылка",
     *      example="solo-leveling"
     * )
     *
     * @var string
     */
    public $slug;

    /**
     * @OA\Property(
     *      title="description",
     *      description="Описание",
     *      example="10 лет назад раскрылись врата в другой мир, где людям дозволено убивать монстров. Так появились охотники, преследующие и уничтожающие тварей. Но не каждому из них суждено повысить свой уровень и стать сильнее. Сон Джин Ву был охотником низшего E-ранга, у которого не было ни единого шанса продвинуться по ранговой лестнице, пока однажды он случайно не очутился в подземелье D-ранга. Чуть не погибнув от рук сильнейших чудовищ, Джин Ву открывает секрет повышения уровня, известный только ему одному. Теперь даже самый слабый может стать сильнейшим."
     * )
     *
     * @var string
     */
    public $description;

    /**
     * @OA\Property(
     *      title="type",
     *      description="Тип",
     *      example="1"
     * )
     *
     * @var string
     */
    public $type;

    /**
     * @OA\Property(
     *      title="titleStatus",
     *      description="Статус тайтла",
     *      example="2"
     * )
     *
     * @var string
     */
    public $titleStatus;

    /**
     * @OA\Property(
     *      title="transalteStatus",
     *      description="Статус перевода",
     *      example="2"
     * )
     *
     * @var string
     */
    public $transalteStatus;

    /**
     * @OA\Property(
     *      title="releaseFormat",
     *      description="Формат релиза",
     *      example=""
     * )
     *
     * @var string
     */
    public $releaseFormat;

    /**
     * @OA\Property(
     *      title="releaseYear",
     *      description="Год релиза",
     *      example="2019"
     * )
     *
     * @var string
     */
    public $releaseYear;

    /**
     * @OA\Property(
     *      title="ageLimiter",
     *      description="Возрастное ограничение",
     *      example="16+"
     * )
     *
     * @var string
     */
    public $ageLimiter;

    /**
     * @OA\Property(
     *      title="otherNames",
     *      description="Альтернативные названия",
     *      example="I Alone Level-Up,I Level Up Alone,I alone level up,Na Honjaman Level Up,Na Honjaman Rebereop,Only I Level Up,Ore Dake Level Up na Ken,Соло Левелинг,Я один повышаю свой уровень,俺だけレベルアップな件,我独自升级,나 혼자만 레벨업"
     * )
     *
     * @var string
     */
    public $otherNames;

    /**
     * @OA\Property(
     *      property="contacts",
     *      type="array",
     *      description="Контакты",
     *      @OA\Items(
     *          type="string",
     *          example=""
     *      ),
     *      example=""
     * )
     *
     * @var array
     */
    public $contacts;

    /**
     * @OA\Property(
     *      title="country",
     *      description="Страна",
     *      example=""
     * )
     *
     * @var string
     */
    public $country;

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(Request $request): bool
    {
        return $request->user() != null ? true : false;
    }

    protected function prepareForValidation()
    {
        $this->merge($this->json()->all());
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'unique:temp.titles,ru_name'],
            'altName' => ['nullable', 'string'],
            // 'cover' => [
            //     'path' => ['required', 'url:http,https'],
            //     'extension' => ['required', 'string']
            // ],
            'path' => ['nullable', 'string'],
            'slug' => ['nullable', 'string'],
            'description' => ['nullable', 'string'],
            'type' => ['nullable', 'string'],
            'titleStatus' => ['nullable', 'integer', Rule::enum(TitleStatus::class)],
            'translateStatus' => ['nullable', 'integer', Rule::enum(TranslateStatus::class)],
            'releaseFormat' => ['nullable', 'string'],
            'releaseYear' => ['nullable', 'integer', 'digits:4', 'between:1900,' . date('Y')],
            'ageLimiter' => ['nullable', 'integer', Rule::enum(AgeLimiter::class)],
            'otherNames' => ['nullable', 'string'],
            'contacts' => ['nullable', 'array'],
            'country' => ['nullable', 'string'],
        ];
    }
}
