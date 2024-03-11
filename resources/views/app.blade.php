<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Fonts -->
    <link rel="preconnect" href="https://www.fontsquirrel.com">
    <link href="https://www.fontsquirrel.com/fonts/metropolis" rel="stylesheet" />
    @vite('resources/css/app.css')
    @inertiaHead
</head>

<body class="antialiased bg-slate-900">
    @inertia
</body>
@vite('resources/js/app.js')

</html>
