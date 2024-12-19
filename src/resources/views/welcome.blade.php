<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>

    <x-admin::item id="1" value="1" />

    {{-- <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Laravel</title>

    <input type="button" name="login" value="Войти">
    <input type="button" name="logout" value="Выйти">
    @csrf

    <input type="button" name="parse" value="Начать парсинг"> --}}

    {{-- @php
        $titles = \App\Models\Title::all();
        $chapters = \App\Models\Chapter::all();
    @endphp

    @foreach ($titles as $title)
        <x-admin::accordion id="accordionFlushExample">
            <x-admin::accordion-item object-type="title" :object="$title" :isOnlyChapter=false
                accordion-id="accordionFlushExample">
                <x-admin::accordion id="accordionFlushExample1">
                    <x-admin::accordion-item object-type="chapter" :object="$title->chapters" :isOnlyChapter=false
                        accordion-id="accordionFlushExample1" />
                </x-admin::accordion>
            </x-admin::accordion-item>
        </x-admin::accordion>
    @endforeach --}}

</head>

</html>
