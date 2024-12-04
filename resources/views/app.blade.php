<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title inertia>{{ config('app.name', 'Laravel') }}</title>

        <link rel="icon" type="image/png" href="/images/favicons/favicon-20x20.png">

        <meta property="og:type" content="website">
        <meta property="og:site_name" content="TaskApp">
        <meta property="og:title" content="TaskApp - задачи">
        <meta property="og:description" content="Легкий и удобный способ управлять задачами и проектами!">
        <meta property="og:locale" content="ru">
        <meta property="og:image" content="/images/og_image.png">
        <meta property="og:image:width" content="1384">
        <meta property="og:image:height" content="504">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @routes
        @vite(['resources/js/app.js', "resources/js/Pages/{$page['component']}.vue"])
        @inertiaHead
    </head>
    <body class="font-sans antialiased">
        @inertia
    </body>
</html>
