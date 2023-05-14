<!DOCTYPE html>
<style>
    .dvh-full {
        min-block-size: 100vh;
        min-block-size: 100dvh;
    }
</style>

<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-theme="compos" class="h-full">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title inertia>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @routes
    @vite(['resources/js/app.js', "resources/js/Pages/{$page['component']}.vue"])
    @inertiaHead
</head>

<body class="font-sans antialiased dvh-full h-full">
    @inertia
</body>

</html>