<x-layout2>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Administrador/Sensores/Crear Sensor') }}
        </h2>
    </x-slot>

    <div class="justify-center">
        <form class="max-w-sm mx-auto" action="{{ route('admin.edits.sens', $sens->id) }}" method="POST">
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
                <label for="name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Nombre del
                    Sensor</label>
                <input type="text" id="name" name="name"
                    class="shadow-sm bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 dark:shadow-sm-light"
                    value="{{ $sens->name }}" required />
            </div>
            <div class="mb-5">
                <label for="macaddress"
                    class="block m  b-2 text-sm font-medium text-gray-900 dark:text-white">Identificador del
                    sensor</label>
                <input type="text" id="macaddress" name="macaddress"
                    class="shadow-sm bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 dark:shadow-sm-light"
                    required value="{{ $sens->macaddress }}" />
            </div>
            <div class="mb-5">
                <label for="sector" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Nombre
                    del
                    sector en donde se encuentra</label>
                <input type="text" id="sector" name="sector"
                    class="shadow-sm bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 dark:shadow-sm-light"
                    required value="{{ $sens->sector }}" />
            </div>
            <div class="mb-5">
                <label for="longitude"
                    class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Longitud</label>
                <input type="text" id="longitude" name="longitude"
                    class="shadow-sm bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 dark:shadow-sm-light"
                    required value="{{ $sens->longitude }}" />
            </div>
            <div class="mb-5">
                <label for="latitude"
                    class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Latitud</label>
                <input type="latitude" id="latitude" name="latitude"
                    class="shadow-sm bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 dark:shadow-sm-light"
                    required value="{{ $sens->latitude }}" />
            </div>
            <div class="mb-5">
                <label for="ubications" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Seleccione
                    la ubicación del sensor</label>
                <select id="ubications" name="ubication_id"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                    @foreach ($ubications as $ubication)
                        <option value="{{ $ubication->id }}" data-latitude="{{ $ubication->latitude }}"
                            data-longitude="{{ $ubication->longitude }}"
                            @if ($ubication->id == $sens->ubication_id) selected @endif>
                            {{ $ubication->name }} - {{ $ubication->sector }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="mb-5">
                <label for="types" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Tipos</label>
                <div id="typesContainer">
                    @foreach ($sens->summaries as $summary)
                        <div class="type-wrapper mb-2">
                            <select name="types[]"
                                class="block w-full px-4 py-3 text-base text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                @foreach ($types as $type)
                                    <option value="{{ $type->id }}"
                                        @if ($type->id == $summary->id_type) selected @endif>
                                        {{ $type->name }}
                                    </option>
                                @endforeach
                            </select>
                            <button type="button"
                                class="removeTypeButton mt-2 bg-red-500 text-white py-2 px-4 rounded-lg">Eliminar</button>
                        </div>
                    @endforeach
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
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
    <link rel="stylesheet" href="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.css" />
    <script src="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Inicializar el mapa como antes
            var defaultLat = {{ $sens->latitude }};
            var defaultLng = {{ $sens->longitude }};
            var map = L.map('map').setView([defaultLat, defaultLng], 13);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
            }).addTo(map);

            var marker = L.marker([defaultLat, defaultLng]).addTo(map);

            document.getElementById('ubications').addEventListener('change', function() {
                var selectedOption = this.options[this.selectedIndex];
                var newLat = selectedOption.getAttribute('data-latitude');
                var newLng = selectedOption.getAttribute('data-longitude');

                if (newLat && newLng) {
                    var latlng = [parseFloat(newLat), parseFloat(newLng)];
                    map.setView(latlng, 13);
                    marker.setLatLng(latlng);
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

        document.getElementById('addTypeButton').addEventListener('click', function() {
            const typesContainer = document.getElementById('typesContainer');
            const typeWrapper = document.createElement('div');
            typeWrapper.classList.add('type-wrapper', 'mb-2');
            typeWrapper.innerHTML = `
                                    <select name="types[]" class="block w-full px-4 py-3 text-base text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                        @foreach ($types as $type)
                                            <option value="{{ $type->id }}">{{ $type->name }}</option>
                                        @endforeach
                                    </select>
                                    <button type="button" class="removeTypeButton mt-2 bg-red-500 text-white py-2 px-4 rounded-lg">Eliminar</button>
                                `;
            typesContainer.appendChild(typeWrapper);
        });

        // Eliminar tipo
        document.addEventListener('click', function(event) {
            if (event.target.classList.contains('removeTypeButton')) {
                const typeWrapper = event.target.parentElement;
                const selectElement = typeWrapper.querySelector('select');
                const typeId = selectElement.value;

                // Realizar la solicitud AJAX para eliminar el tipo de la tabla Summary
                fetch(`/deleteSummary/${typeId}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Content-Type': 'application/json'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            typeWrapper.remove();
                        } else {
                            alert('Error al eliminar el tipo');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                    });
            }
        });

        document.addEventListener('click', function(event) {
            if (event.target.classList.contains('removeTypeButton')) {
                event.target.closest('.type-wrapper').remove();
            }
        });
    </script>

</x-layout2>
