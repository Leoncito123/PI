<x-layout2>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Administrador/Sensores/Crear Tipo') }}
        </h2>
    </x-slot>

    <div class="justify-center">
        <form class="max-w-sm mx-auto" action="{{route('admin.creates.ubications')}}" enctype="multipart/form-data" method="POST">
            @csrf
            @method('POST')
            <div class="mb-5">
                <label for="name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Nombre de la ubicación</label>
                <input type="text" id="name" name="name"
                    class="shadow-sm bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 dark:shadow-sm-light"
                    placeholder="Nombre de la ubicación" required />
            </div>
            <div class="mb-5">
                <label for="identifier"
                    class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Nombre del sector</label>
                <input type="text" id="sector" name="sector"
                    class="shadow-sm bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 dark:shadow-sm-light"
                    required />
            </div>
            <div class="mb-5">
                <label for="unit" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Longitud</label>
                <input type="text" id="longitude" name="longitude"
                    class="shadow-sm bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 dark:shadow-sm-light"
                    required readonly />
            </div>
            <div class="mb-5">
                <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white" for="latitude">Latitud</label>
                <input
                    class="block w-full text-lg text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400"
                    id="latitude" name="latitude" type="text" readonly>
            </div>
            <div class="mb-5">
                <label for="ubications"
                    class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Seleccione un supervisor a cargo</label>
                <select id="supervisor_id" name="supervisor_id"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                    <option value="">Seleccione un supervisor a cargo</option>
                    @foreach ($supervisores as $supervisor)
                        <option value="{{ $supervisor->id }}">
                            {{ $supervisor->name }} - {{ $supervisor->email }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div id="map" class="mb-5" style="height: 400px;"></div>
            <button type="submit"
                class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Crear nueva variable</button>
        </form>
    </div>

    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <!-- Leaflet JS -->
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
    <!-- Leaflet Control Geocoder -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.css" />
    <script src="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Inicializar el mapa
            var map = L.map('map').setView([0, 0], 2);

            // Añadir capa de mapa de OpenStreetMap
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
            }).addTo(map);

            // Añadir buscador
            var geocoder = L.Control.geocoder({
                defaultMarkGeocode: false,
                placeholder: 'Buscar ubicación...',
                errorMessage: 'No se encontró ninguna ubicación.'
            })
            .on('markgeocode', function(e) {
                var latlng = e.geocode.center;
                map.setView(latlng, 13);
                if (marker) {
                    marker.setLatLng(latlng);
                } else {
                    marker = L.marker(latlng).addTo(map);
                }
                updateLatLng(latlng);
            })
            .addTo(map);

            // Añadir marcador al hacer clic en el mapa
            var marker;
            map.on('click', function (e) {
                if (marker) {
                    marker.setLatLng(e.latlng);
                } else {
                    marker = L.marker(e.latlng).addTo(map);
                }
                updateLatLng(e.latlng);
            });

            function updateLatLng(latlng) {
                document.getElementById('latitude').value = latlng.lat.toFixed(6);
                document.getElementById('longitude').value = latlng.lng.toFixed(6);
            }

            // Geolocalización
            if ("geolocation" in navigator) {
                navigator.geolocation.getCurrentPosition(function(position) {
                    var latlng = L.latLng(position.coords.latitude, position.coords.longitude);
                    map.setView(latlng, 13);
                    marker = L.marker(latlng).addTo(map);
                    updateLatLng(latlng);
                });
            }
        });
    </script>
</x-layout2>