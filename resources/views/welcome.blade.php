<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>AquaLogic</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />

    <!-- Styles -->
    <link rel="stylesheet" href="{{ asset('path/to/flowbite/dist/flowbite.min.css') }}">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-gray-100">
    <nav class="bg-white border-gray-200 dark:bg-gray-900 shadow-md">
        <div class="max-w-screen-xl flex flex-wrap items-center justify-between mx-auto p-4">
            <a href="{{ route('welcome') }}" class="flex items-center space-x-3 rtl:space-x-reverse">
                <img src="https://flowbite.com/docs/images/logo.svg" class="h-8" alt="Flowbite Logo" />
                <span class="self-center text-2xl font-semibold whitespace-nowrap dark:text-white">AquaLogic</span>
            </a>
            <button data-collapse-toggle="navbar-default" type="button"
                class="inline-flex items-center p-2 w-10 h-10 justify-center text-sm text-gray-500 rounded-lg md:hidden hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200 dark:text-gray-400 dark:hover:bg-gray-700 dark:focus:ring-gray-600"
                aria-controls="navbar-default" aria-expanded="false">
                <span class="sr-only">Open main menu</span>
                <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                    viewBox="0 0 17 14">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M1 1h15M1 7h15M1 13h15" />
                </svg>
            </button>
            <div class="hidden w-full md:block md:w-auto" id="navbar-default">
                <ul
                    class="font-medium flex flex-col p-4 md:p-0 mt-4 border border-gray-100 rounded-lg bg-gray-50 md:flex-row md:space-x-8 rtl:space-x-reverse md:mt-0 md:border-0 md:bg-white dark:bg-gray-800 md:dark:bg-gray-900 dark:border-gray-700">
                    @if (Route::has('login'))
                        @auth
                            <li>
                                <a href="{{ url('/dashboard') }}"
                                    class="block py-2 px-3 text-white bg-blue-700 rounded md:bg-transparent md:text-blue-700 md:p-0 dark:text-white md:dark:text-blue-500"
                                    aria-current="page">Home</a>
                            </li>
                        @else
                            <li>
                                <a href="{{ route('login') }}"
                                class="block py-2 px-3 text-gray-900 rounded hover:bg-gray-100 md:hover:bg-blue-700  md:border-0 md:hover:text-white p-5   dark:text-white md:dark:hover:text-blue-500 dark:hover:bg-gray-700 dark:hover:text-white md:dark:hover:bg-transparent">Iniciar
                                    Sesión</a>
                            </li>
                            @if (Route::has('register'))
                                <li>
                                    <a href="{{ route('register') }}"
                                        class="block py-2 px-3 text-gray-900 rounded hover:bg-gray-100 md:hover:bg-blue-700  md:border-0 md:hover:text-white p-5   dark:text-white md:dark:hover:text-blue-500 dark:hover:bg-gray-700 dark:hover:text-white md:dark:hover:bg-transparent">Registrarse</a>
                                </li>
                            @endif
                        @endauth
                    @endif
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mx-auto my-8">
        <div class="relative w-full h-96 bg-cover bg-center" style="background-image: url('{{ asset('images/agua.jpg') }}')">
            <div class="flex flex-col items-center justify-center w-full h-full bg-gray-900 bg-opacity-50">
                <h1 class="text-4xl font-bold text-white">AquaLogic</h1>
                <p class="text-lg text-white">Tu mejor opción para el control de calidad del agua</p>
                <a href="{{ route('login') }}"
                class="btn inline-block rounded border-2 hover:bg-white hover:text-black border-neutral-50 px-6 pb-[6px] pt-2 text-xs font-medium uppercase leading-normal text-neutral-50 transition duration-150 ease-in-out hover:border-neutral-300 focus:border-neutral-300 focus:text-neutral-200 focus:outline-none focus:ring-0 active:border-neutral-300 active:text-neutral-200 dark:hover:bg-neutral-600 dark:focus:bg-neutral-600">
                EMPEZAR ->
            </a>
            </div>
        </div>
    </div>
    

    <footer class="bg-white dark:bg-gray-900 py-4 shadow-md">
        <div class="max-w-screen-xl mx-auto text-center">
            <p class="text-gray-500 dark:text-gray-400">© 2023 AquaLogic. Todos los derechos reservados.</p>
        </div>
    </footer>

    <script src="{{ asset('path/to/flowbite/dist/flowbite.min.js') }}"></script>
</body>

</html>