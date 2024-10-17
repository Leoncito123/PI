<x-layout2>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Administrador/Sensores') }}
        </h2>
    </x-slot>

    {{-- Formulario de búsqueda con JavaScript --}}
    <div class="mb-4">
        <input type="text" id="searchInput" placeholder="Buscar..." class="border rounded-lg p-2">
    </div>

    {{-- Botones para crear --}}
    <div class="flex items-center justify-end mb-4">
        <a href="{{ route('admin.create.type') }}"
            class="bg-blue-500 text-white p-2 rounded-lg shadow-md hover:bg-blue-700">Crear Variable</a>
    </div>

    {{-- Tabla de sensores --}}
    <div class="py-12">
        <div id="sensorGrid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 p-6">
            @if ($types->isEmpty())
                <div class="col-span-3">
                    <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
                        <h2 class="text-lg font-semibold text-gray-900 dark:text-white">No hay variables</h2>
                    </div>
                </div>
            @else
                @foreach ($types as $type)
                    <div
                        class="sensor-card bg-white dark:bg-gray-800 shadow rounded-lg overflow-hidden hover:translate-x-1 hover:translate-y-1 ">
                        @if ($type->icon)
                            <img src="{{ asset('storage/' . $type->icon) }}" alt="icono"
                                class="w-full h-48 object-cover">
                        @endif
                        <div class="p-4">
                            <h3 class="sensor-name text-lg font-semibold text-gray-900 dark:text-white"><strong>Nombre:
                                </strong>{{ $type->name }}</h3>
                            <p class="sensor-variable text-gray-700 dark:text-gray-300"><strong>Identificador:
                                </strong>{{ $type->variable }}</p>
                            <p class="text-gray-700 dark:text-gray-300"><strong>Unidad: </strong>{{ $type->unit }}</p>
                            <p class="text-gray-700 dark:text-gray-300"><strong>Valor Maximo:
                                </strong>{{ $type->max_value }}</p>
                            <p class="text-gray-700 dark:text-gray-300"><strong>Valor Minimo:
                                </strong>{{ $type->min_value }}</p>
                            <p class="text-gray-700 dark:text-gray-300"><strong>Rangos: </strong>{{ $type->segment }}
                            </p>
                            <p class="text-gray-700 dark:text-gray-300"><strong>Intervalo de tiempo:
                                </strong>{{ $type->interval }}</p>
                            <div class="mt-4 flex justify-between">
                                <a href="{{ route('admin.edit.type', ['id' => $type->id]) }}"
                                    class="text-white bg-green-400 hover:bg-trans focus:ring-4 focus:outline-none hover:text-gray-900 font-medium rounded-lg text-sm px-5 py-2.5 dark:bg-fuerte dark:hover:bg-secundario dark:focus:ring-blue-800"
                                    type="button">
                                    Editar
                                </a>
                                <form action="{{ route('admin.delete.type', ['id' => $type->id]) }}" method="POST"
                                    onsubmit="return confirm('¿Estás seguro de que deseas eliminar este tipo?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="font-medium bg-red-500 text-white hover:bg-red-700 dark:text-white focus:ring-4 rounded-lg text-sm px-5 py-2.5 block">
                                        Eliminar
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            @endif
        </div>
    </div>

    {{-- Script para filtrar --}}
    <script>
        document.getElementById('searchInput').addEventListener('keyup', function() {
            let filter = this.value.toLowerCase();
            let cards = document.querySelectorAll('.sensor-card');

            cards.forEach(card => {
                let name = card.querySelector('.sensor-name').innerText.toLowerCase();
                let variable = card.querySelector('.sensor-variable').innerText.toLowerCase();

                if (name.includes(filter) || variable.includes(filter)) {
                    card.style.display = '';
                } else {
                    card.style.display = 'none';
                }
            });
        });
    </script>
</x-layout2>
