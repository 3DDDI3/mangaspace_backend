<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    @vite(['resources/js/jquery-3.7.1.js', 'resources/js/app.js'])

    <script type="module">
        window.Echo.private('chat.2')
            .listen('TestEvent', (e) => {
                alert(e.message);
            });
    </script>
</head>

<body>

</body>

</html>
