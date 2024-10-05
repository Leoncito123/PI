    <x-layout2>
        <x-slot name="header">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Administrador/Sensores/Crear Sensor') }}
            </h2>
        </x-slot>

        <div class="justify-center">
            <form class="max-w-sm mx-auto" action="{{ route('admin.creates.sens') }}" method="POST">
                @csrf
                @method('POST')
                <div class="mb-5">
                    <label for="name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Nombre del
                        Sensor</label>
                    <input type="text" id="name" name="name"
                        class="shadow-sm bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 dark:shadow-sm-light"
                        placeholder="name@flowbite.com" required />
                </div>
                <div class="mb-5">
                    <label for="macaddress"
                        class="block m  b-2 text-sm font-medium text-gray-900 dark:text-white">Identificador del
                        sensor</label>
                    <input type="text" id="macaddress" name="macaddress"
                        class="shadow-sm bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 dark:shadow-sm-light"
                        required />
                </div>
                <div class="mb-5">
                    <label for="sector" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Nombre
                        del
                        sector en donde se encuentra</label>
                    <input type="text" id="sector" name="sector"
                        class="shadow-sm bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 dark:shadow-sm-light"
                        required />
                </div>
                <div class="mb-5">
                    <label for="longitude"
                        class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Longitud</label>
                    <input type="text" id="longitude" name="longitude"
                        class="shadow-sm bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 dark:shadow-sm-light"
                        required />
                </div>
                <div class="mb-5">
                    <label for="latitude"
                        class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Latitud</label>
                    <input type="latitude" id="latitude" name="latitude"
                        class="shadow-sm bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 dark:shadow-sm-light"
                        required />
                </div>
                <div class="mb-5">
                    <label for="ubications"
                        class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Seleccione
                        la ubicación del sensor</label>
                    <select id="ubications" name="ubication_id"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                        <option value="">Selecciona una ubicación</option>
                        @foreach ($ubications as $ubication)
                            <option value="{{ $ubication->id }}" data-latitude="{{ $ubication->latitude }}"
                                data-longitude="{{ $ubication->longitude }}">
                                {{ $ubication->name }} - {{ $ubication->sector }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-5">
                    <label for="countries"
                        class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Seleccione
                        el tipo de sensor para este sensor</label>
                    <div id="typesContainer">
                        <div class="type-wrapper">
                            <select id="countries" name="types[]"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                <option value="">Selecciona un tipo</option>
                                @foreach ($types as $type)
                                    <option value="{{ $type->id }}"> {{ $type->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <button type="button" id="addTypeButton" class="mt-2 bg-green-500 text-white py-2 px-4 rounded-lg">
                    Añadir otro tipo
                </button>
                <div id="map" class="mb-5" style="height: 400px;"></div>
                <button type="submit"
                    class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Crear
                    Sensor</button>
            </form>

        </div>

        <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
        <!-- Leaflet JS -->
        <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
        <!-- Leaflet Control Geocoder -->
        <link rel="stylesheet" href="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.css" />
        <script src="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.js"></script>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Inicializar el mapa como antes
                @php
                    $ubications = $ubications->first();
                @endphp
                
                var defaultLat = {{ $ubications->latitude }};
                var defaultLng = {{ $ubications->longitude }};
                var map = L.map('map').setView([defaultLat, defaultLng], 13);
                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
                }).addTo(map);

                // Añadir marcador inicial
                var marker = L.marker([defaultLat, defaultLng]).addTo(map);

                // Evento al cambiar la ubicación en el select
                document.getElementById('ubications').addEventListener('change', function() {
                    var selectedOption = this.options[this.selectedIndex];
                    var newLat = selectedOption.getAttribute('data-latitude');
                    var newLng = selectedOption.getAttribute('data-longitude');

                    if (newLat && newLng) {
                        var latlng = [parseFloat(newLat), parseFloat(newLng)];
                        map.setView(latlng, 13); // Actualiza el centro del mapa
                        marker.setLatLng(latlng); // Mueve el marcador
                        updateLatLng({
                            lat: latlng[0],
                            lng: latlng[1]
                        });
                    }
                });

                function updateLatLng(latlng) {
                    document.getElementById('latitude').value = latlng.lat.toFixed(6);
                    document.getElementById('longitude').value = latlng.lng.toFixed(6);
                }
            });
        </script>
        <script>
            document.getElementById('addTypeButton').addEventListener('click', function() {
                const typesContainer = document.getElementById('typesContainer');
                const typeWrapper = document.querySelector('.type-wrapper').cloneNode(true); // Clona el select de tipos
                typesContainer.appendChild(typeWrapper); // Añade el nuevo select al contenedor
            });
        </script>

    </x-layout2>
