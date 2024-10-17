<?php

namespace App\Http\Controllers;

use App\Models\Data;
use App\Models\Summaries;
use App\Models\Type;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
  public function index(Request $request)
  {
    // Filtros por sector, ubicación, tipo y rango de fechas
    $selectedSector = $request->input('sector', 'all');
    $selectedUbication = $request->input('ubication', 'all');
    $selectedType = $request->input('type', 'all');
    $dateRange = $request->input('date_range', '7'); // Por defecto, últimos 7 días

    $query = Data::query()
      ->join('summaries', 'data.summary_id', '=', 'summaries.id')
      ->join('ubications', 'summaries.ubication_id', '=', 'ubications.id') // Unión correcta de 'ubications'
      ->join('types', 'summaries.type_id', '=', 'types.id');

    if ($selectedSector !== 'all') {
      $query->where('data.sector', $selectedSector);
    }
    if ($selectedUbication !== 'all') {
      $query->where('ubications.id', $selectedUbication);
    }
    if ($selectedType !== 'all') {
      $query->where('types.id', $selectedType);
    }

    $query->where('data.date', '>=', now()->subDays($dateRange));

    $data = $query->select(
      'data.date',
      'data.value',
      'data.battery',
      'data.sector',
      'ubications.name as ubication_name', // Referencia correcta de la columna 'ubications.name'
      'types.name as type_name',
      'types.min_value',
      'types.max_value',
      'types.unit'
    )->get();

    // Cálculos de métricas
    $averageValue = $data->avg('value');
    $averageBattery = $data->avg('battery');
    $totalReadings = $data->count();

    // Agrupación de datos para gráficos
    $valuesByDate = $data->groupBy('date')->map->avg('value');
    $batteryByDate = $data->groupBy('date')->map->avg('battery');
    $readingsBySector = $data->groupBy('sector')->map->count();

    // Filtrados disponibles
    $sectors = Data::distinct('sector')->pluck('sector');
    // Aquí se ajusta la consulta para evitar el error de columna desconocida
    $ubications = Summaries::join('ubications', 'summaries.ubication_id', '=', 'ubications.id')
      ->distinct()
      ->pluck('ubications.name', 'ubications.id');
    $types = Type::pluck('name', 'id');

    // **Alertas**
    $alerts = [];
    foreach ($data as $entry) {
      // Comprobar si el valor está fuera del rango permitido
      if ($entry->value < $entry->min_value || $entry->value > $entry->max_value) {
        $alerts[] = [
          'type' => 'value',
          'message' => "El valor {$entry->value} {$entry->unit} en el sector {$entry->sector} está fuera del rango permitido ({$entry->min_value}-{$entry->max_value} {$entry->unit}).",
          'date' => $entry->date,
          'sector' => $entry->sector
        ];
      }

      // Comprobar si la batería está baja
      if ($entry->battery < 20) { // Definir umbral de batería baja
        $alerts[] = [
          'type' => 'battery',
          'message' => "La batería del sensor en el sector {$entry->sector} está baja ({$entry->battery}%).",
          'date' => $entry->date,
          'sector' => $entry->sector
        ];
      }
    }

    return view('dashboard', compact(
      'data',
      'averageValue',
      'averageBattery',
      'totalReadings',
      'valuesByDate',
      'batteryByDate',
      'readingsBySector',
      'sectors',
      'ubications',
      'types',
      'selectedSector',
      'selectedUbication',
      'selectedType',
      'dateRange',
      'alerts'
    ));
  }
}
