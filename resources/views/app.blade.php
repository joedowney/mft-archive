<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title inertia>{{ config('app.name', 'Musical Family Tree') }}</title>
        <link rel="icon" type="image/x-icon" href="/favicon.ico">

        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Rubik:wght@400;700&display=swap" rel="stylesheet">

        @routes
        @vite(['resources/js/app.js', "resources/js/Pages/{$page['component']}.vue"])
        @inertiaHead
    </head>
    <body class="font-sans antialiased text-gray-200 h-screen relative"
        style="background: rgb(31,41,54); background: linear-gradient(350deg, #162238 32%, rgb(0 0 0) 100%);"
    >
        <div class="h-full overflow-y-scroll">
            @inertia
        </div>
    </body>
</html>
