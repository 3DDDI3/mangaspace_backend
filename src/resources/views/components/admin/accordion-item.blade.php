@if ($objectType == 'title' && !$isOnlyChapter)
    <div class="accordion-item">
        <h2 class="accordion-header" id="flush-heading{{ $object->id }}">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                data-bs-target="#flush-collapse{{ $object->id }}" aria-expanded="false"
                aria-controls="flush-collapse{{ $object->id }}">
                {{ $object->ru_name }}
            </button>
        </h2>
        <div id="flush-collapse{{ $object->id }}" class="accordion-collapse collapse"
            aria-labelledby="flush-collapse{{ $object->id }}" data-bs-parent="#{{ $accordionId }}" style="">
            <div class="accordion-body">
                <p> Русское название: {{ $object->ru_name }}</p>
                <p>Английское название: {{ $object->eng_name }}</p>
                <p>Другие названия: {{ $object->other_names }}</p>
                <p>Категория: {{ $object->category->category }}</p>
                {!! $slot !!}
@endif

@if ($objectType == 'chapter')
    @foreach ($object as $_obj)
        <div class="accordion-item">
            <h2 class="accordion-header" id="flush-heading{{ $_obj->id }}">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                    data-bs-target="#flush-collapse{{ $_obj->id }}" aria-expanded="false"
                    aria-controls="flush-collapse{{ $_obj->id }}">
                    Глава {{ $_obj->number }}
                </button>
            </h2>
            <div id="flush-collapse{{ $_obj->id }}" class="accordion-collapse collapse"
                aria-labelledby="flush-collapse{{ $_obj->id }}" data-bs-parent="#{{ $accordionId }}"
                style="">
                <div class="accordion-body">
                    <ul>
                        @foreach ($_obj->images as $image)
                            @foreach (\App\Services\ImageStringService::parseImages($image->extensions) as $subImage)
                                <li>{{ $subImage }}</li>
                            @endforeach
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    @endforeach
    @if (!$isOnlyChapter)
        </div>
        </div>
        </div>
    @endif
@endif

@if ($isOnlyChapter)
    {!! $slot !!}
@endif
