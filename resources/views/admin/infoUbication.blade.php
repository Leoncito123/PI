<x-layout2>
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <div id="chart"></div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var options = {
                chart: {
                    type: 'bar',
                    height: 350
                },
                series: {!! json_encode($chartData) !!},
                xaxis: {
                    categories: {!! json_encode($sensorTypes) !!},
                    title: {
                        text: 'Tipos de Sensores'
                    }
                },
                yaxis: {
                    title: {
                        text: 'Número de Sensores'
                    }
                },
                title: {
                    text: 'Número de Sensores por Tipo y Ubicación',
                    align: 'center'
                },
                plotOptions: {
                    bar: {
                        horizontal: false,
                        columnWidth: '55%',
                        endingShape: 'rounded'
                    },
                },
                dataLabels: {
                    enabled: false
                },
                stroke: {
                    show: true,
                    width: 2,
                    colors: ['transparent']
                },
                fill: {
                    opacity: 1
                },
                tooltip: {
                    y: {
                        formatter: function(val) {
                            return val + " sensores"
                        }
                    }
                }
            };

            var chart = new ApexCharts(document.querySelector("#chart"), options);
            chart.render();
        });
    </script>
</x-layout2>
