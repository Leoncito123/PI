<x-layout2>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Administrador/Sensores/Crear Tipo') }}
        </h2>
    </x-slot>

    <div class="justify-center">
        @if ($errors->any())
            <div class="mb-4">
                <ul class="list-disc list-inside text-red-600">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <form class="max-w-sm mx-auto" action="{{ route('admin.creates.types') }}" enctype="multipart/form-data"
            method="POST">
            @csrf
            @method('POST')
            <div class="mb-5">
                <label for="email" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Nombre de la
                    variable</label>
                <input type="text" id="name" name="name"
                    class="shadow-sm bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 dark:shadow-sm-light"
                    placeholder="Nombre de la variable" required />
            </div>
            <div class="mb-5">
                <label for="identifier"
                    class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Identificador de la
                    variable</label>
                <input type="identifier" id="identifier" name="identifier"
                    class="shadow-sm bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 dark:shadow-sm-light"
                    required />
            </div>
            <div class="mb-5">
                <label for="unit" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Unidad de la
                    variable</label>
                <input type="text" id="unit" name="unit"
                    class="shadow-sm bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 dark:shadow-sm-light"
                    required />
            </div>
            <div class="mb-5">
                <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white" for="large_size">Cargue el
                    icono de la variable</label>
                <input
                    class="block w-full text-lg text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400"
                    id="large_size" name="icon" type="file">
            </div>
            <div class="mb-5">
                <label for="min_value" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Valor
                    minimo</label>
                <input type="number" id="min_value" name="min_value"
                    class="shadow-sm bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 dark:shadow-sm-light"
                    required />
            </div>
            <div class="mb-5">
                <label for="max_value" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Valor
                    maximo</label>
                <input type="number" id="max_value" name="max_value"
                    class="shadow-sm bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 dark:shadow-sm-light"
                    required />
            </div>
            <div class="mb-5">
                <label for="segment" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Cantidad de
                    segementos para el mapa</label>
                <input type="number" id="segment" name="segment"
                    class="shadow-sm bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 dark:shadow-sm-light"
                    required />
            </div>
            <div class="mb-5">
                <label for="interval" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Valor
                    maximo</label>
                <input type="number" id="interval" name="interval"
                    class="shadow-sm bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 dark:shadow-sm-light"
                    required />
            </div>
            <button type="submit"
                class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Crear
                nueva variable</button>
        </form>

    </div>

</x-layout2>
