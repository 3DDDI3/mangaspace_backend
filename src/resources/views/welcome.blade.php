<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Laravel</title>



    {{-- <script type="module">
        window.Echo.channel('test_channel').listen("TestEvent", (event) => {
            console.log(event);
        });
    </script> --}}

    <input type="button" name="login" value="Войти">
    <input type="button" name="logout" value="Выйти">
    @csrf
    <input type="text" name="name" placeholder="название тайтла">
    @vite(['resources/js/jquery-3.7.1.js', 'resources/js/app.js'])

</head>

</html>
