<div class="accordion-item">
    <h2 class="accordion-header" id="{{ $id }}">
        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
            data-bs-target="#{{ $bodyId }}" aria-expanded="false" aria-controls="{{ $bodyId }}">
            {{ $header }}
        </button>
    </h2>
    <div id="{{ $bodyId }}" class="accordion-collapse collapse" aria-labelledby="{{ $id }}"
        data-bs-parent="#{{ $accordionId }}" style="">
        <div class="accordion-body">
            {{ $slot }}
        </div>
    </div>
</div>
