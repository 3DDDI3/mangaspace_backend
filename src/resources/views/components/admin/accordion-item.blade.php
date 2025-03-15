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
            aria-labelledby="flush-collapse{{ $object->id }}" data-bs-parent="#{{ $accordionId }}">
            <div class="accordion-body">
                <div class="swiper cover-swiper mx-0 mb-4" data-swiper-id="t{{ $object->id }}">
                    <div class="swiper-wrapper">
                        @foreach ($object->covers as $cover)
                            <div class="swiper-slide">
                                <img src="/media/{{ $cover->path }}" alt="">
                            </div>
                        @endforeach
                    </div>
                    <div class="swiper-pagination swiper-pagination_t{{ $object->id }}"></div>
                    <div class="swiper-button-prev swiper-button-prev_t{{ $object->id }}"></div>
                    <div class="swiper-button-next swiper-button-next_t{{ $object->id }}"></div>
                    <div class="swiper-scrollbar swiper-scrollbar_t{{ $object->id }}"></div>
                </div>
                <p><b>Русское название:</b> {{ $object->ru_name }}</p>
                <p><b>Английское название:</b> {{ $object->eng_name }}</p>
                <p><b>Другие названия:</b> {{ $object->other_names }}</p>
                <p><b>Категория:</b> {{ $object->category->category }}</p>
                {!! $slot !!}
@endif

@if ($objectType == 'chapter')
    @foreach ($object as $_obj)
        <div class="accordion-item position-relative">
            <h2 class="accordion-header chapter-accordion-body" id="flush-heading{{ $_obj->id + 1 }}">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                    data-bs-target="#flush-collapse{{ $_obj->id + 1 }}" aria-expanded="false"
                    aria-controls="flush-collapse{{ $_obj->id + 1 }}">
                    Том {{ $_obj->volume }}. Глава {{ $_obj->number }}
                </button>
                <div class="btn-group d-flex column-gap-2" role="group" aria-label="Basic example">
                    <a class="action-btn image-edit-btn btn icon btn-primary"
                        href="/admin/titles/{{ $_obj->title->slug }}/chapters/{{ $_obj->number }}" target="_blank">
                        <i class="bi bi-pencil"></i>
                    </a>
                </div>
            </h2>
            <div id="flush-collapse{{ $_obj->id + 1 }}" class="accordion-collapse accordion-collapse-chapter collapse"
                aria-labelledby="flush-collapse{{ $_obj->id + 1 }}" data-bs-parent="#{{ $accordionId }}">
                <div class="accordion-body accordion-chapter px-0 pt-0">
                    <div class="swiper swiper w-100" data-button-id="{{ $_obj->id }}"
                        data-swiper-id="c{{ $_obj->id }}">
                        <div class="swiper-wrapper">
                            @foreach ($_obj->images as $image)
                                @foreach (\App\Services\ImageStringService::parseImages($image->extensions) as $subImage)
                                    <div class="swiper-slide">
                                        <img loading="lazy"
                                            src="/media/{{ $_obj?->path }}/{{ $image->translator->slug }}/{{ $subImage }}"
                                            alt="" srcset="">
                                        <div class="swiper-lazy-preloader"></div>
                                    </div>
                                @endforeach
                            @endforeach
                        </div>
                        <div class="swiper-scrollbar_t{{ $_obj->id }}"></div>
                    </div>
                    <div class="swiper-button-prev swiper-button-prev_c{{ $_obj->id }}"></div>
                    <div class="swiper-button-next swiper-button-next_c{{ $_obj->id }}"></div>
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
