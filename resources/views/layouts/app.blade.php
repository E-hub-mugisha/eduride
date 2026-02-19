<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased">
    <div class="min-h-screen bg-gray-100">
        @include('layouts.navigation')

        <!-- Page Content -->
        <main>
            {{ $slot }}
        </main>
        <footer class="bg-blue-900 text-gray-100 mt-8">
            <div class="container mx-auto px-4 py-6 flex flex-col md:flex-row justify-between items-center">
                <!-- Left: Logo / Site name -->
                <div class="mb-4 md:mb-0">
                    <a href="#" class="text-2xl font-bold text-yellow-400">EduRide</a>
                    <p class="text-sm text-gray-300">© {{ date('Y') }} All rights reserved.</p>
                </div>
            </div>
        </footer>
    </div>
</body>

</html>