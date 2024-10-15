<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="mb-6">
        <form action="{{ route('dashboard') }}" method="GET" class="flex space-x-4">
            <div>
                <label for="sector" class="block text-sm font-medium text-gray-700">Sector</label>
                <select name="sector" id="sector"
                    class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                    <option value="all" {{ $selectedSector == 'all' ? 'selected' : '' }}>Todos los sectores</option>
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
    </script>

</x-app-layout>
