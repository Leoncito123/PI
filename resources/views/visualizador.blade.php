<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Visualizador en tiempo real') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="mb-4">
                        <h3 class="text-lg font-semibold mb-2">Filtros</h3>
                        <div class="flex flex-wrap gap-4">
                            <select id="sectorFilter"
                                class="rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                <option value="">Todos los sectores</option>
                            </select>
                            <select id="typeFilter"
                                class="rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                <option value="">Todos los tipos</option>

                            </select>
                            <button id="applyFilters"
                                class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600">Aplicar
                                filtros</button>
                        </div>
                    </div>
                    <div id="map" style="height: 600px;" class="rounded-lg"></div>
                    <div class="mt-4">
                        <h3 class="text-lg font-semibold mb-2">Ubicaciones</h3>
                        <div id="locationButtons" class="flex flex-wrap gap-2">
                            <!-- Botones de ubicación se cargarán dinámicamente -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Incluye Leaflet CSS y JS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>

    <script>
        let map;
        let markers = [];

        document.addEventListener('DOMContentLoaded', function() {
            initMap();
            loadFilters();
            loadLocationButtons();
            loadSensors(); // Carga inicial de sensores
        });

        function initMap() {
            map = L.map('map').setView([-33.4569, -70.6483], 13); // Coordenadas iniciales
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '© OpenStreetMap contributors'
            }).addTo(map);
        }

        function loadFilters() {
            // Cargar opciones de sectores
            fetch('/api/sectors')
                .then(response => response.json())
                .then(data => {
                    const sectorFilter = document.getElementById('sectorFilter');
                    data.forEach(sector => {
                        const option = document.createElement('option');
                        option.value = sector;
                        option.textContent = sector;
                        sectorFilter.appendChild(option);
                    });
                });

            // Cargar opciones de tipos
            fetch('/api/types')
                .then(response => response.json())
                .then(data => {
                    const typeFilter = document.getElementById('typeFilter');
                    data.forEach(type => {
                        const option = document.createElement('option');
                        option.value = type.id;
                        option.textContent = type.name;
                        typeFilter.appendChild(option);
                    });
                });
        }

        function loadLocationButtons() {
            fetch('/api/ubications')
                .then(response => response.json())
                .then(data => {
                    const locationButtons = document.getElementById('locationButtons');
                    data.forEach(location => {
                        const button = document.createElement('button');
                        button.textContent = location.name + ' / ' + location.sector;
                        button.className = 'px-3 py-1 bg-gray-200 rounded-md hover:bg-gray-300';
                        button.onclick = () => focusLocation(location.latitude, location.longitude);
                        locationButtons.appendChild(button);
                    });
                });
        }

        function focusLocation(lat, lng) {
            map.setView([lat, lng], 15);
        }

        function loadSensors() {
            const sector = document.getElementById('sectorFilter').value;
            const type = document.getElementById('typeFilter').value;

            // Limpiar marcadores existentes
            markers.forEach(marker => map.removeLayer(marker));
            markers = [];

            fetch(`/api/sensors?sector=${sector}&type=${type}`)
                .then(response => response.json())
                .then(data => {
                    if (data.length === 0) {
                        alert('No se encontraron sensores para los filtros aplicados.');
                        return;
                    }

                    data.forEach(sensor => {
                        if (sensor.latitude && sensor.longitude) {
                            const marker = L.marker([sensor.latitude, sensor.longitude]).addTo(map);
                            marker.bindPopup(`
                        <p><strong>${sensor.name}</strong></p>
                        <p>Último valor: ${sensor.last_value} ${sensor.unit || ''}</p>
                        <p>Batería: ${sensor.battery || 'N/A'}%</p>
                        <p>Última actualización: ${sensor.last_update || 'Sin datos'}</p>
                    `);
                            markers.push(marker);
                        }
                    });
                })
                .catch(error => {
                    console.error('Error al cargar los sensores:', error);
                    alert('Ocurrió un error al intentar cargar los datos.');
                });
        }


        document.getElementById('applyFilters').addEventListener('click', loadSensors);
    </script>

</x-app-layout>
