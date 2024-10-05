<x-layout2>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Administrador/Tarjetas') }}
        </h2>
    </x-slot>
    <div class="flex items-center justify-end mb-4">
        <a href="{{ route('admin.create.output') }}"
            class="bg-blue-500 text-white p-2 rounded-lg shadow-md hover:bg-blue-700">Crear Tarjetas</a>
    </div>
    <div class="py-12">
        <table id="search-table">
            <thead>
                <tr>
                    <th><span class="flex items-center">Nombre</span></th>
                    <th><span class="flex items-center">Tarjeta</span></th>
                    <th><span class="flex items-center">Variable</span></th>
                    <th><span class="flex items-center">Estado</span></th>
                    <th><span class="flex items-center">Editar</span></th>
                    <th><span class="flex items-center">Eliminar</span></th>
                </tr>
            </thead>
            <tbody>
                @if ($outputs->count() == 0)
                    <tr>
                        <td colspan="7" class="text-center">No hay Tarjetas registradas</td>
                    </tr>
                @endif
                @foreach ($outputs as $output)
                    <tr>
                        <td class="font-medium text-gray-900 whitespace-nowrap dark:text-white">{{ $output->name }}</td>
                        <td>{{ $output->devices->name }}</td>
                        <td>{{ $output->types->name }}</td>
                        @if ($output->status === 1)
                            <td> <strong class="text-green bg-green-100 dark:text-green dark:bg-green p-2 rounded-md"> Encendido</strong></td>
                        @else
                            <td><strong  class="text-red-900 bg-red-200 dark:text-red-900 dark:bg-red-200 p-2 rounded-md">Apagado</strong></td>
                        @endif
                        <td>
                            <a href="{{ route('admin.edit.output', $output->id) }}">
                                <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true"
                                    xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none"
                                    viewBox="0 0 24 24">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                        stroke-width="2"
                                        d="m14.304 4.844 2.852 2.852M7 7H4a1 1 0 0 0-1 1v10a1 1 0 0 0 1 1h11a1 1 0 0 0 1-1v-4.5m2.409-9.91a2.017 2.017 0 0 1 0 2.853l-6.844 6.844L8 14l.713-3.565 6.844-6.844a2.015 2.015 0 0 1 2.852 0Z" />
                                </svg>
                            </a>
                        </td>
                        <td>
                            <form action="{{ route('admin.delete.output', ['id' => $output->id]) }}" method="POST"
                                onsubmit="return confirm('¿Estás seguro de que deseas eliminar esta ubicación?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    class="font-medium bg-intenso text-white hover:bg-red-500 dark:text-white
                                    focus:ring-4 rounded-lg text-sm px-5 py-2.5 block">
                                    <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true"
                                        xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none"
                                        viewBox="0 0 24 24">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M5 7h14m-9 3v8m4-8v8M10 3h4a1 1 0 0 1 1 1v3H9V4a1 1 0 0 1 1-1ZM6 7h12v13a1 1 0 0 1-1 1H7a1 1 0 0 1-1-1V7Z" />
                                    </svg>
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Modal para el mapa -->

        <!-- Leaflet CSS -->
        <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
        <!-- Leaflet JS -->
        <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>

        <script>
            if (document.getElementById("search-table") && typeof simpleDatatables.DataTable !== 'undefined') {
                const dataTable = new simpleDatatables.DataTable("#search-table", {
                    searchable: true,
                    sortable: false
                });
            }
        </script>
    </div>
</x-layout2>
