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

        <button onclick="openChatModal()"
            class="fixed bottom-4 right-4 bg-indigo-600 text-white rounded-full p-4 shadow-lg hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-4l-4 4z" />
            </svg>
        </button>

        <!-- Modal del chat -->
        <div id="chatModal"
            class="hidden fixed inset-0 bg-black/25 backdrop-blur-sm overflow-y-auto h-full w-full z-50">
            <div class="relative top-10 mx-auto p-6 w-full max-w-2xl">
                <!-- Card Principal -->
                <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
                    <div class="flex flex-col h-[700px]">
                        <!-- Header del modal con diseño mejorado -->
                        <div class="flex justify-between items-center px-6 py-4 bg-white border-b">
                            <div class="flex items-center space-x-3">
                                <div class="h-10 w-10 bg-indigo-100 rounded-full flex items-center justify-center">
                                    <svg class="h-6 w-6 text-indigo-600" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-4l-4 4z" />
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-900">Chat con Gemini AI</h3>
                                    <p class="text-sm text-gray-500">Asistente Virtual</p>
                                </div>
                            </div>
                            <button onclick="closeChatModal()"
                                class="rounded-full p-2 hover:bg-gray-100 transition-colors">
                                <svg class="h-6 w-6 text-gray-500" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>

                        <!-- Filtros con diseño mejorado -->
                        <div class="px-6 py-3 bg-gray-50">
                            <div class="relative">
                                <select id="chatSector"
                                    class="w-full appearance-none bg-white border border-gray-300 rounded-lg px-4 py-2.5 pr-8 
                                         focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-colors">
                                    <option value="all">Todos los sectores</option>
                                    @foreach ($sectors as $sector)
                                        <option value="{{ $sector }}">{{ $sector }}</option>
                                    @endforeach
                                </select>
                                <div class="absolute right-3 top-1/2 transform -translate-y-1/2 pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 9l-7 7-7-7" />
                                    </svg>
                                </div>
                            </div>
                        </div>

                        <!-- Área de mensajes con diseño mejorado -->
                        <div id="chat-messages" class="flex-1 overflow-y-auto px-6 py-4 space-y-4 bg-gray-50">
                            <!-- Los mensajes se insertarán aquí dinámicamente -->
                            <div class="message bot">
                                <div class="flex items-start space-x-3">
                                    <div
                                        class="h-8 w-8 bg-indigo-100 rounded-full flex items-center justify-center flex-shrink-0">
                                        <svg class="h-5 w-5 text-indigo-600" fill="none" viewBox="0 0 24 24"
                                            stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-4l-4 4z" />
                                        </svg>
                                    </div>
                                    <div class="flex-1">
                                        <p class="text-sm font-medium text-gray-900">Gemini AI</p>
                                        <p class="text-gray-700">¡Hola! ¿En qué puedo ayudarte hoy?</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>

                    <!-- Formulario de entrada con diseño mejorado -->
                    <div class="p-6 bg-white border-t">
                        <form id="chat-form" class="flex space-x-3">
                            <div class="relative flex-1">
                                <input type="text" id="question"
                                    class="w-full pl-4 pr-12 py-3 border border-gray-300 rounded-lg 
                                             focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-colors"
                                    placeholder="Escribe tu pregunta aquí...">
                                <button type="submit"
                                    class="absolute right-2 top-1/2 transform -translate-y-1/2 
                                             bg-indigo-600 text-white p-2 rounded-lg hover:bg-indigo-700 
                                             focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all">
                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M5 12h14M12 5l7 7-7 7" />
                                    </svg>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .message {
            @apply p-4 rounded-lg my-2 max-w-[85%] shadow-sm;
        }

        .message.user {
            @apply bg-white text-gray-800 ml-auto;
        }

        .message.bot {
            @apply bg-indigo-50 text-gray-800 mr-auto;
        }

        .message.error {
            @apply bg-red-50 text-red-800 mx-auto;
        }

        /* Estilizar la barra de desplazamiento */
        #chat-messages::-webkit-scrollbar {
            width: 6px;
        }

        #chat-messages::-webkit-scrollbar-track {
            background: transparent;
        }

        #chat-messages::-webkit-scrollbar-thumb {
            background-color: rgba(156, 163, 175, 0.5);
            border-radius: 3px;
        }

        #chat-messages::-webkit-scrollbar-thumb:hover {
            background-color: rgba(156, 163, 175, 0.7);
        }
    </style>
    <script>
        function openChatModal() {
            document.getElementById('chatModal').classList.remove('hidden');
        }

        function closeChatModal() {
            document.getElementById('chatModal').classList.add('hidden');
        }

        // Cerrar modal al hacer clic fuera
        document.getElementById('chatModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeChatModal();
            }
        });

        document.getElementById('chat-form').addEventListener('submit', async (e) => {
            e.preventDefault();

            const question = document.getElementById('question').value;
            const sector = document.getElementById('chatSector').value;

            if (!question.trim()) return;

            // Agregar pregunta al chat
            appendMessage('Tú: ' + question, 'user');

            try {
                const response = await fetch('/gemini/ask', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({
                        question: question,
                        sector: sector
                    })
                });

                const data = await response.json();
                appendMessage('Gemini: ' + data.answer, 'bot');
            } catch (error) {
                appendMessage('Error: No se pudo obtener la respuesta', 'error');
            }

            document.getElementById('question').value = '';
        });

        function appendMessage(message, type) {
            const chatMessages = document.getElementById('chat-messages');
            const messageDiv = document.createElement('div');
            messageDiv.className = `message ${type}`;

            // Separar el prefijo (Tú: o Gemini:) del mensaje
            const [prefix, ...messageParts] = message.split(':');
            const messageContent = messageParts.join(':').trim();

            // Crear el contenido HTML según el tipo de mensaje
            if (type === 'user') {
                messageDiv.innerHTML = `
            <div class="flex flex-col">
                <span class="text-sm font-medium text-gray-900 mb-1">${prefix}</span>
                <span class="text-gray-700">${messageContent}</span>
            </div>
        `;
            } else if (type === 'bot') {
                messageDiv.innerHTML = `
            <div class="flex items-start space-x-3">
                <div class="h-8 w-8 bg-indigo-100 rounded-full flex items-center justify-center flex-shrink-0">
                    <svg class="h-5 w-5 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                            d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-4l-4 4z"/>
                    </svg>
                </div>
                <div class="flex-1">
                    <p class="text-sm font-medium text-gray-900">${prefix}</p>
                    <p class="text-gray-700">${messageContent}</p>
                </div>
            </div>
        `;
            } else {
                // Mensaje de error
                messageDiv.innerHTML = `
            <div class="flex items-center justify-center">
                <svg class="h-5 w-5 text-red-500 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                        d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span>${message}</span>
            </div>
        `;
            }

            chatMessages.appendChild(messageDiv);

            // Scroll suave hasta el último mensaje
            messageDiv.scrollIntoView({
                behavior: 'smooth',
                block: 'end'
            });
        }
    </script>
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
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({
                        alert_key: alertKey
                    })
                });

                if (response.ok) {
                    const alert = document.getElementById(elementId);
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
            } catch (error) {
                console.error('Error al descartar la alerta:', error);
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
