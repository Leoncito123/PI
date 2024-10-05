<x-layout2>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Administrador/Usuarios') }}
        </h2>
    </x-slot>
    <div class="flex items-center justify-end mb-4">
        <a href="{{ route('admin.create.ubication') }}"
            class="bg-blue-500 text-white p-2 rounded-lg shadow-md hover:bg-blue-700">Crear Ubicacion</a>
    </div>
    <div class="py-12">
        <table id="search-table">
            <thead>
                <tr>
                    <th><span class="flex items-center">Nombre</span></th>
                    <th><span class="flex items-center">Sector</span></th>
                    <th><span class="flex items-center">Longitud</span></th>
                    <th><span class="flex items-center">Latitud</span></th>
                    <th><span class="flex items-center">Ver Mapa</span></th>
                    <th><span class="flex items-center">Editar</span></th>
                    <th><span class="flex items-center">Eliminar</span></th>
                </tr>
            </thead>
            <tbody>
                @if ($ubications->isEmpty())
                    <tr>
                        <td colspan="7" class="text-center">No hay ubicaciones registradas</td>
                    </tr>
                @endif
                @foreach ($ubications as $ubication)
                    <tr>
                        <td class="font-medium text-gray-900 whitespace-nowrap dark:text-white">{{ $ubication->name }}</td>
                        <td>{{ $ubication->sector }}</td>
                        <td>{{ $ubication->longitude }}</td>
                        <td>{{ $ubication->latitude }}</td>
                        <td>
                            <button onclick="showMap({{ $ubication->latitude }}, {{ $ubication->longitude }}, '{{ $ubication->name }}')"
                                class="bg-green-500 text-white p-2 rounded-lg shadow-md hover:bg-green-700">
                                Ver Mapa
                            </button>
                        </td>
                        <td>
                            <a href="{{route('admin.edit.ubication',$ubication->id)}}">
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
                            <form action="{{ route('admin.delete.ubication', ['id' => $ubication->id]) }}" method="POST"
                                onsubmit="return confirm('¿Estás seguro de que deseas eliminar esta ubicación?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    class="font-medium bg-intenso text-white hover:bg-red-500 dark:text-white
                                    focus:ring-4 rounded-lg text-sm px-5 py-2.5 block">
                                    <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 7h14m-9 3v8m4-8v8M10 3h4a1 1 0 0 1 1 1v3H9V4a1 1 0 0 1 1-1ZM6 7h12v13a1 1 0 0 1-1 1H7a1 1 0 0 1-1-1V7Z"/>
                                    </svg>                                      
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Modal para el mapa -->
        <div id="mapModal" class="fixed z-10 inset-0 overflow-y-auto hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title"></h3>
                        <div class="mt-2">
                            <div id="map" style="height: 400px;"></div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button type="button" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm" onclick="closeModal()">
                            Cerrar
                        </button>
                    </div>
                </div>
            </div>
        </div>

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

            let map;

            function showMap(lat, lng, name) {
                document.getElementById('mapModal').classList.remove('hidden');
                document.getElementById('modal-title').textContent = 'Ubicación: ' + name;

                if (!map) {
                    map = L.map('map').setView([lat, lng], 13);
                    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
                    }).addTo(map);
                } else {
                    map.setView([lat, lng], 13);
                }

                L.marker([lat, lng]).addTo(map)
                    .bindPopup(name)
                    .openPopup();
            }

            function closeModal() {
                document.getElementById('mapModal').classList.add('hidden');
            }
        </script>
    </div>
</x-layout2>