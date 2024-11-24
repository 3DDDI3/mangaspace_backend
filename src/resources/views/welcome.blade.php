<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    {{-- <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Laravel</title>

    <input type="button" name="login" value="Войти">
    <input type="button" name="logout" value="Выйти">
    @csrf

    <input type="button" name="parse" value="Начать парсинг"> --}}

    <x-admin::accordion id="accordionFlushExample">
        <x-admin::accordion-item id="headingOne" accordion-id="accordionFlushExample" header=" Accordion Item #1"
            body-id="flush-collapseOne">
            Placeholder content for this
            accordion, which is intended to demonstrate the
            <code>.accordion-flush</code> class. This is the first
            item's accordion body.
        </x-admin::accordion-item>
    </x-admin::accordion>

</head>

</html>
