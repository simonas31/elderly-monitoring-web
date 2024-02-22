<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>ESP32-CAM Stream</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />
    @vite('resources/css/app.css')
</head>

<body class="antialiased bg-slate-900" style="height: 100vh">
    <div class="container flex items-center justify-center mx-auto py-2 h-full">
        <div class="middle-of-the-screen bg-white">
            <img id="stream" alt="image" class="w-full h-full">
        </div>
    </div>
</body>
@vite('resources/js/app.js')

</html>
