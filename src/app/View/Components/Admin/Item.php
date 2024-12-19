<?php

namespace App\View\Components\Admin;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Item extends Component
{
    public $_data = '';
    /**
     * Create a new component instance.
     */
    public function __construct(
        public ?string $id = null,
        public ?string $ariaLabel = null,
        public ?string $value = null,
        public ?array $data = [],
    ) {
    }


    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.admin.item');
    }
}
