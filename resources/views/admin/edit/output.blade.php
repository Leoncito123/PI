<x-layout2>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Administrador/Sensores/Crear Tarjeta') }}
        </h2>
    </x-slot>

    <div class="justify-center">
        <form class="max-w-sm mx-auto" action="{{ route('admin.edits.output', $output->id) }}" method="POST">
            @csrf
            @method('POST')
            @if ($errors->any())
                <div class="mb-4">
                    <ul class="list-disc list-inside text-red-600">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <div class="mb-5">
                <label for="name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Nombre de la
                    tarjeta</label>
                <input type="text" id="name" name="name"
                    class="shadow-sm bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 dark:shadow-sm-light"
                    placeholder="name@flowbite.com" required value="{{ $output->name }}" />
            </div>
            <input type="number" name="status" value="0" hidden>
            <div class="mb-5">
                <label for="ubications" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Seleccione
                    la tarjeta del motor</label>
                <select id="ubications" name="dev_id"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                    @foreach ($devices as $device)
                        <option value="{{ $device->id }}" @if ($device->id == $output->dev_id) selected @endif>
                            {{ $device->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="mb-5">
                <label for="ubications" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Seleccione
                    la tarjeta del motor</label>
                <select id="ubications" name="type_id"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                    @foreach ($types as $type)
                        <option value="{{ $type->id }}" @if ($type->id == $output->type_id) selected @endif>
                            {{ $type->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <button type="submit"
                class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Crear
                Tarjeta</button>
        </form>

    </div>

</x-layout2>
