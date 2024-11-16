<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>
    <div class="container mx-auto px-4 py-8">
        @if (count($alerts ?? []) > 0)
            <div id="alertsContainer" class="space-y-4 mb-8">
                @foreach ($alerts as $alert)
                    <div id="alert-{{ $loop->index }}"
                        class="alert-item rounded-lg shadow-md overflow-hidden {{ $alert['type'] == 'battery' ? 'bg-yellow-50 border-l-4 border-yellow-400' : 'bg-red-50 border-l-4 border-red-400' }}">
                        <div class="p-4">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0">
                                        @if ($alert['type'] == 'battery')
                                            <svg class="h-5 w-5 text-yellow-400" xmlns="http://www.w3.org/2000/svg"
                                                viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                                <path fill-rule="evenodd"
                                                    d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                        @else
                                            <svg class="h-5 w-5 text-red-400" xmlns="http://www.w3.org/2000/svg"
                                                viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                                <path fill-rule="evenodd"
                                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                        @endif
                                    </div>
                                    <div class="ml-3">
                                        <h3
                                            class="text-sm font-medium {{ $alert['type'] == 'battery' ? 'text-yellow-800' : 'text-red-800' }}">
                                            {{ $alert['message'] }}
                                        </h3>
                                        <div
                                            class="mt-2 text-sm {{ $alert['type'] == 'battery' ? 'text-yellow-700' : 'text-red-700' }}">
                                            <p>Fecha: {{ $alert['date'] }} | Sector: {{ $alert['sector'] }}</p>
                                        </div>
                                    </div>
                                </div>
                                <div>
                                    <button
                                        onclick="dismissAlert('{{ $alert['alert_key'] }}', 'alert-{{ $loop->index }}')"
                                        class="text-gray-400 hover:text-gray-500 focus:outline-none focus:text-gray-500 transition ease-in-out duration-150">
                                        <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd"
                                                d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                                clip-rule="evenodd" />
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
        <div class="mb-6">
            <form action="{{ route('dashboard') }}" method="GET" class="flex space-x-4">
                <div>
                    <label for="sector" class="block text-sm font-medium text-gray-700">Sector</label>
                    <select name="sector" id="sector"
                        class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                        <option value="all" {{ $selectedSector == 'all' ? 'selected' : '' }}>Todos los sectores
                        </option>
                        @foreach ($sectors as $sector)
                            <option value="{{ $sector }}" {{ $selectedSector == $sector ? 'selected' : '' }}>
                                {{ $sector }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="date_range" class="block text-sm font-medium text-gray-700">Rango de fechas</label>
                    <select name="date_range" id="date_range"
                        class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                        <option value="7" {{ $dateRange == '7' ? 'selected' : '' }}>Últimos 7 días</option>
                        <option value="30" {{ $dateRange == '30' ? 'selected' : '' }}>Últimos 30 días</option>
                        <option value="90" {{ $dateRange == '90' ? 'selected' : '' }}>Últimos 90 días</option>
                    </select>
                </div>
                <div class="flex items-end">
                    <button type="submit"
                        class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700">Filtrar</button>
                </div>
            </form>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="px-4 py-5 sm:p-6">
                    <dt class="text-sm font-medium text-gray-500 truncate">Valor Promedio</dt>
                    <dd class="mt-1 text-3xl font-semibold text-gray-900">{{ number_format($averageValue, 2) }}</dd>
                </div>
            </div>
            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="px-4 py-5 sm:p-6">
                    <dt class="text-sm font-medium text-gray-500 truncate">Batería Promedio</dt>
                    <dd class="mt-1 text-3xl font-semibold text-gray-900">{{ number_format($averageBattery, 2) }}%</dd>
                </div>
            </div>
            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="px-4 py-5 sm:p-6">
                    <dt class="text-sm font-medium text-gray-500 truncate">Total de Lecturas</dt>
                    <dd class="mt-1 text-3xl font-semibold text-gray-900">{{ $totalReadings }}</dd>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <div class="bg-white shadow rounded-lg p-6">
                <h2 class="text-xl font-semibold mb-4">Tendencia de Valores</h2>
                <canvas id="valuesChart"></canvas>
            </div>
            <div class="bg-white shadow rounded-lg p-6">
                <h2 class="text-xl font-semibold mb-4">Niveles de Batería</h2>
                <canvas id="batteryChart"></canvas>
            </div>
            <div class="bg-white shadow rounded-lg p-6">
                <h2 class="text-xl font-semibold mb-4">Distribución por Sector</h2>
                <canvas id="sectorChart"></canvas>
            </div>
        </div>
    </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const valuesChart = new Chart(document.getElementById('valuesChart'), {
            type: 'line',
            data: {
                labels: {!! json_encode($valuesByDate->keys()) !!},
                datasets: [{
                    label: 'Valor',
                    data: {!! json_encode($valuesByDate->values()) !!},
                    borderColor: 'rgb(75, 192, 192)',
                    tension: 0.1
                }]
            }
        });

        const batteryChart = new Chart(document.getElementById('batteryChart'), {
            type: 'line',
            data: {
                labels: {!! json_encode($batteryByDate->keys()) !!},
                datasets: [{
                    label: 'Batería',
                    data: {!! json_encode($batteryByDate->values()) !!},
                    borderColor: 'rgb(255, 99, 132)',
                    tension: 0.1
                }]
            }
        });

        const sectorChart = new Chart(document.getElementById('sectorChart'), {
            type: 'pie',
            data: {
                labels: {!! json_encode($readingsBySector->keys()) !!},
                datasets: [{
                    data: {!! json_encode($readingsBySector->values()) !!},
                    backgroundColor: [
                        'rgb(255, 99, 132)',
                        'rgb(54, 162, 235)',
                        'rgb(255, 205, 86)',
                        'rgb(75, 192, 192)',
                        'rgb(153, 102, 255)',
                        'rgb(255, 159, 64)'
                    ]
                }]
            }
        });

        async function dismissAlert(alertKey, elementId) {
            try {
                const response = await fetch('/dismiss-alert', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json', // Asegura la aceptación de JSON
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                            'content')
                    },
                    body: JSON.stringify({
                        alert_key: alertKey
                    })
                });

                if (response.ok) {
                    // Procesar la alerta eliminada
                    const alert = document.getElementById(elementId);
                    if (alert) {
                        alert.classList.add('removing');

                        setTimeout(() => {
                            alert.remove();
                            // Verificar si no quedan más alertas
                            const alertsContainer = document.getElementById('alertsContainer');
                            if (alertsContainer && alertsContainer.children.length === 0) {
                                alertsContainer.remove();
                            }
                        }, 500);
                    }
                } else {
                    // Muestra un mensaje de error si la respuesta no es exitosa
                    const errorData = await response.json();
                    console.error('Error al descartar la alerta:', errorData.message || 'Error desconocido.');
                }
            } catch (error) {
                console.error('Error al realizar la solicitud de descarte:', error);
            }
        }
    </script>
    <style>
        .alert-item {
            transition: all 0.5s ease-in-out;
            max-height: 1000px;
            /* Un valor alto para asegurar que cubra el contenido */
            opacity: 1;
        }

        .alert-item.removing {
            max-height: 0;
            opacity: 0;
            margin-top: 0;
            margin-bottom: 0;
            padding-top: 0;
            padding-bottom: 0;
            border-width: 0;
        }
    </style>
</x-app-layout>
