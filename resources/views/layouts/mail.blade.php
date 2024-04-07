<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite('resources/css/mail.css')
    <title>@yield('title')</title>
</head>

<body>
    <div class="container mx-auto flex bg-slate-200">
        @yield('content')
    </div>
</body>

</html>
