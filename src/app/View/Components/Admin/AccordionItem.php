<?php

namespace App\View\Components\Admin;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\View\Component;

class AccordionItem extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct(
        public ?array $id = null,
        public ?string $accordionId = null,
        public ?string $header = null,
        public ?string $bodyId = null,
        public ?string $slot = null,
        public ?string $objectType = null,
        public bool $isOnlyChapter = false,
        public Collection|Model|null $object = null,
    ) {}

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.admin.accordion-item')->with(['']);
    }
}
