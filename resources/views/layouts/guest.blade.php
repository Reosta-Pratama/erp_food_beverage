<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">

        <meta name="Description" content="{{ config('app.name') }} - A modern web panel template">
        <meta name="Author" content="REOSTA BAYU PRATAMA PANE">
        <meta name="keywords" content="{{ config('app.name') }}, ERP, Manufacture, Laravel, Dashboard, Admin, Admin Panel">

        <title>{{ config('app.name') }} - {{ $title }}</title>

        <!-- Favicon -->
        <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('assets/images/logo/favicon_io/apple-touch-icon.png') }}">
        <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('assets/images/logo/favicon_io/favicon-32x32.png') }}">
        <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('assets/images/logo/favicon_io/favicon-16x16.png') }}">
        <link rel="manifest" href="{{ asset('assets/images/logo/favicon_io/site.webmanifest') }}">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link
            rel="preconnect"
            href="https://fonts.gstatic.com"
            crossorigin="crossorigin">
        <link
            href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&display=swap"
            rel="stylesheet">

        <!-- Icon -->
        @vite(['resources/assets/icon-fonts/icons.css'])

        <!-- Main Styles -->
        @vite(['resources/sass/app.scss'])

        <!-- Additional Style -->
        @yield('styles')
    </head>
    <body class="{{ $bodyClass ?? '' }}">

        <div class="page">

            <!-- Page Content -->
            @yield('content')

        </div>

        <!-- Main Scripts -->
        @vite([
            'resources/js/app.js', 
        ])

        <!-- Additional Script -->
        @yield('scripts')
    </body>
</html>