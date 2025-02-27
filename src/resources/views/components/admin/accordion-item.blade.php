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
                <img style="margin-bottom: 2rem" src="/media/{{ $object->covers()->first()?->path }}" alt="">
                <p><b>Русское название:</b> {{ $object->ru_name }}</p>
                <p><b>Английское название:</b> {{ $object->eng_name }}</p>
                <p><b>Другие названия:</b> {{ $object->other_names }}</p>
                <p><b>Категория:</b> {{ $object->category->category }}</p>
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
                    <div class="swiper">
                        <div class="swiper-wrapper">
                            @foreach ($_obj->images as $image)
                                @foreach (\App\Services\ImageStringService::parseImages($image->extensions) as $subImage)
                                    <div class="swiper-slide">
                                        <img src="/media/{{ $_obj?->path }}{{ $subImage }}" alt=""
                                            srcset="">
                                    </div>
                                @endforeach
                            @endforeach
                        </div>
                        <div class="swiper-button-prev"></div>
                        <div class="swiper-button-next"></div>

                        <!-- If we need scrollbar -->
                        <div class="swiper-scrollbar"></div>
                    </div>
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
