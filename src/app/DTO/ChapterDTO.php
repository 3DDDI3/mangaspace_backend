<?php

namespace App\DTO;

class ChapterDTO
{
    public string $url;
    public ?string $name;
    public ?string $number;
    public ?string $translator;

    /**
     * true, если нужно отобравзить информацию о тайтле
     *
     * @var boolean
     */
    public bool $isFirst;
    /**
     * true, если возвращается последняя глава
     *
     * @var boolean
     */
    public bool $isLast;

    /**
     *
     * @param string $url Ссылка
     * @param string|null $number номер
     * @param string|null $name имя
     * @param boolean $isFirst true, если  нужно отобравзить информацию о тайтле
     * @param boolean $isLast true, если возвращается последняя глава
     */
    public function __construct(
        string $url,
        ?string $number = null,
        ?string $translator = null,
        ?string $name = null,
        bool $isFirst = false,
        bool $isLast = false
    ) {
        $this->url = $url;
        $this->number = $number;
        $this->translator = $translator;
        $this->name = $name;
        $this->isLast = $isLast;
        $this->isFirst = $isFirst;
    }
}
