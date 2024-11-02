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

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>

<body class="font-sans text-gray-900 antialiased bg-gray-100 dark:bg-gray-900">
    <div class="min-h-screen flex flex-col md:flex-row items-center relative overflow-hidden">
        <!-- Decorative Elements -->
        <div
            class="absolute inset-0 bg-gradient-to-br from-blue-50 to-blue-100 dark:from-gray-800 dark:to-gray-900 -z-10">
        </div>
        <div class="absolute inset-0 opacity-30 dark:opacity-40">
            <div class="absolute inset-0 bg-[radial-gradient(circle_500px_at_50%_200px,#3b82f6,transparent)]"></div>
        </div>

        <div class="w-full md:w-1/2 flex flex-col items-center justify-center p-6 relative" x-data="{ isVisible: false }"
            x-init="setTimeout(() => isVisible = true, 100)"
            :class="{ 'opacity-0 translate-y-4': !isVisible, 'opacity-100 translate-y-0': isVisible }"
            class="transition-all duration-1000">

            <a href="/" class="mb-8 transform hover:scale-105 transition-transform">
                <img src="{{ asset('images/logo.jpg') }}" class="w-32 rounded-full" alt="AquaLogic Logo">
            </a>

            <!-- Login Card -->
            <div class="w-full max-w-md relative">
                <div
                    class="absolute inset-0 bg-white dark:bg-gray-800 shadow-2xl rounded-2xl backdrop-blur-xl bg-opacity-80 dark:bg-opacity-80">
                </div>

                <div class="relative px-8 py-8 rounded-2xl">
                    <div class="text-center mb-8">
                        <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">Bienvenido de nuevo</h2>
                        <p class="text-gray-600 dark:text-gray-400">Ingresa a tu cuenta para continuar</p>
                    </div>

                    <div class="space-y-6">
                        {{ $slot }}
                    </div>
                </div>
            </div>
        </div>


        <div class="w-full md:w-1/2 hidden md:block h-screen relative overflow-hidden">
            <div class="absolute inset-0 bg-gradient-to-r from-blue-600/20 to-blue-800/40 mix-blend-multiply"></div>
            <img src="{{ asset('images/agua.jpg') }}"
                class="w-full h-full object-cover transform scale-105 hover:scale-100 transition-transform duration-3000"
                alt="Water background">

            <div class="absolute inset-0 flex items-center justify-center p-12 bg-opacity-5 bg-black">
                <div class="text-white text-center bg-opacity-90 bg-gray-800 rounded-lg p-10">
                    <h2 class="text-4xl font-bold mb-4 text-blue-500 text-pretty">AquaLogic</h2>
                    <p class="text-xl text-gray-100 max-w-md">
                        Monitoreo inteligente y control de calidad del agua para tu tranquilidad
                    </p>
                </div>
            </div>
        </div>
    </div>


</body>

</html>
