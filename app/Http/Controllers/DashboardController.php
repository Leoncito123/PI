<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Data;
use App\Models\DismissedAlert;
use App\Models\Summaries;
use App\Models\Ubication;
use App\Models\Type;



class DashboardController extends Controller
{
    public function index(Request $request)
    {
      // Filtros por sector, ubicación, tipo y rango de fechas
      $selectedSector = $request->input('sector', 'all');
      $selectedUbication = $request->input('ubication', 'all');
      $selectedType = $request->input('type', 'all');
      $dateRange = $request->input('date_range', '7');
  
      $query = Data::query()
        ->join('summaries', 'data.summary_id', '=', 'summaries.id')
        ->join('ubications', 'summaries.ubication_id', '=', 'ubications.id')
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
        'ubications.name as ubication_name',
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
      $ubications = Summaries::join('ubications', 'summaries.ubication_id', '=', 'ubications.id')
        ->distinct()
        ->pluck('ubications.name', 'ubications.id');
      $types = Type::pluck('name', 'id');
  
      // **Alertas**
      $dismissedAlerts = DismissedAlert::where('user_id', auth()->id())
        ->get()
        ->map(function ($dismissal) {
          return $dismissal->type . '_' . $dismissal->sector . '_' . $dismissal->alert_date;
        })
        ->toArray();
  
      // Alertas
      $alerts = [];
      foreach ($data as $entry) {
        // Crear identificador único para cada alerta
        $valueAlertKey = 'value_' . $entry->sector . '_' . $entry->date;
        $batteryAlertKey = 'battery_' . $entry->sector . '_' . $entry->date;
  
        // Comprobar si el valor está fuera del rango permitido
        if (($entry->value < $entry->min_value || $entry->value > $entry->max_value)
          && !in_array($valueAlertKey, $dismissedAlerts)
        ) {
          $alerts[] = [
            'type' => 'value',
            'message' => "El valor {$entry->value} {$entry->unit} en el sector {$entry->sector} está fuera del rango permitido ({$entry->min_value}-{$entry->max_value} {$entry->unit}).",
            'date' => $entry->date,
            'sector' => $entry->sector,
            'alert_key' => $valueAlertKey
          ];
        }
  
        // Comprobar si la batería está baja
        if ($entry->battery < 20 && !in_array($batteryAlertKey, $dismissedAlerts)) {
          $alerts[] = [
            'type' => 'battery',
            'message' => "La batería del sensor en el sector {$entry->sector} está baja ({$entry->battery}%).",
            'date' => $entry->date,
            'sector' => $entry->sector,
            'alert_key' => $batteryAlertKey
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
    public function dismissAlert(Request $request)
    {
        $parts = explode('_', $request->alert_key);

        // Verificar que el array tiene suficientes elementos
        if (count($parts) < 5) {
            return response()->json([
                'success' => false,
                'message' => 'Formato de alerta inválido.'
            ], 400);
        }

        // Crear el registro en la tabla dismissed_alerts
        DismissedAlert::create([
            'type' => $parts[0],
            'sector' => $parts[1],
            'alert_date' => $parts[2],
            'user_id' => auth()->id(),
            'data_id' => $parts[3],
            'name' => $parts[4]
        ]);

        return response()->json(['success' => true]);
    }
}
