<x-layout2>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Administrador/Sensores/Editar Ubicación') }}
        </h2>
    </x-slot>

    <div class="justify-center">
        <form class="max-w-sm mx-auto" action="{{route('admin.edits.ubication', $ubication->id)}}" enctype="multipart/form-data" method="POST">
            @csrf
            @method('POST')
            <div class="mb-5">
                <label for="name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Nombre de la ubicación</label>
                <input type="text" id="name" name="name"
                    class="shadow-sm bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 dark:shadow-sm-light"
                    placeholder="Nombre de la ubicación" required value="{{$ubication->name}}"/>
            </div>
            <div class="mb-5">
                <label for="identifier"
                    class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Nombre del sector</label>
                <input type="text" id="sector" name="sector"
                    class="shadow-sm bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 dark:shadow-sm-light"
                    required value="{{$ubication->sector}}"/>
            </div>
            <div class="mb-5">
                <label for="unit" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Longitud</label>
                <input type="text" id="longitude" name="longitude"
                    class="shadow-sm bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 dark:shadow-sm-light"
                    required readonly value="{{$ubication->longitude}}" />
            </div>
            <div class="mb-5">
                <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white" for="latitude">Latitud</label>
                <input
                    class="block w-full text-lg text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400"
                    id="latitude" name="latitude" type="text" readonly value="{{$ubication->latitude}}">
            </div>
            <div id="map" class="mb-5" style="height: 400px;"></div>
            <button type="submit"
                class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Actualizar ubicación</button>
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
            // Obtener las coordenadas existentes
            var defaultLat = {{$ubication->latitude}};
            var defaultLng = {{$ubication->longitude}};

            // Inicializar el mapa con la ubicación existente
            var map = L.map('map').setView([defaultLat, defaultLng], 13);

            // Añadir capa de mapa de OpenStreetMap
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
            }).addTo(map);

            // Añadir marcador inicial
            var marker = L.marker([defaultLat, defaultLng]).addTo(map);

            // Añadir buscador
            var geocoder = L.Control.geocoder({
                defaultMarkGeocode: false,
                placeholder: 'Buscar ubicación...',
                errorMessage: 'No se encontró ninguna ubicación.'
            })
            .on('markgeocode', function(e) {
                var latlng = e.geocode.center;
                map.setView(latlng, 13);
                marker.setLatLng(latlng);
                updateLatLng(latlng);
            })
            .addTo(map);

            // Actualizar marcador al hacer clic en el mapa
            map.on('click', function (e) {
                marker.setLatLng(e.latlng);
                updateLatLng(e.latlng);
            });

            function updateLatLng(latlng) {
                document.getElementById('latitude').value = latlng.lat.toFixed(6);
                document.getElementById('longitude').value = latlng.lng.toFixed(6);
            }
        });
    </script>
</x-layout2>