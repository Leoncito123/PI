<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Data;
use App\Models\DismissedAlert;
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
        $dateRange = $request->input('date_range', '7'); // Por defecto, últimos 7 días

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
            'data.id',
            'data.name',
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

        // Calcular valores promedio
        $averageValue = $data->avg('value');
        $averageBattery = $data->avg('battery');
        $totalReadings = $data->count();

        // Agrupar valores por fecha
        $valuesByDate = $data->groupBy('date')->map->avg('value');
        $batteryByDate = $data->groupBy('date')->map->avg('battery');

        // Agrupar lecturas por sector
        $readingsBySector = $data->groupBy('sector')->map->count();

        // Obtener las alertas descartadas por el usuario actual
        $dismissedAlerts = DismissedAlert::where('user_id', auth()->id())
            ->get()
            ->map(function ($dismissal) {
                $valueKey = 'value_' . $dismissal->sector . '_' . $dismissal->alert_date . '_' . $dismissal->data_id . '_' . $dismissal->name;
                $batteryKey = 'battery_' . $dismissal->sector . '_' . $dismissal->alert_date . '_' . $dismissal->data_id . '_' . $dismissal->name;

                return [
                    'value_key' => $valueKey,
                    'battery_key' => $batteryKey
                ];
            })
            ->flatMap(fn($item) => [$item['value_key'], $item['battery_key']])
            ->toArray();

        // Alertas
        $alerts = [];
        foreach ($data as $entry) {
            $valueAlertKey = 'value_' . $entry->sector . '_' . $entry->date . '_' . $entry->id . '_' . $entry->name;
            $batteryAlertKey = 'battery_' . $entry->sector . '_' . $entry->date . '_' . $entry->id . '_' . $entry->name;

            if (($entry->value < $entry->min_value || $entry->value > $entry->max_value)
                && !in_array($valueAlertKey, $dismissedAlerts)
            ) {
                $alerts[] = [
                    'type' => 'value',
                    'message' => "El valor {$entry->value} {$entry->unit} del sensor {$entry->name} en el sector {$entry->sector} está fuera del rango permitido ({$entry->min_value}-{$entry->max_value} {$entry->unit}).",
                    'date' => $entry->date,
                    'sector' => $entry->sector,
                    'alert_key' => $valueAlertKey
                ];
            }

            if ($entry->battery < 20 && !in_array($batteryAlertKey, $dismissedAlerts)) {
                $alerts[] = [
                    'type' => 'battery',
                    'message' => "La batería del sensor {$entry->name} en el sector {$entry->sector} está baja ({$entry->battery}%).",
                    'date' => $entry->date,
                    'sector' => $entry->sector,
                    'alert_key' => $batteryAlertKey
                ];
            }
        }

        // Obtener listas de sectores, ubicaciones y tipos para los filtros
        $sectors = Data::select('sector')->distinct()->get();
        $ubications = Ubication::all();
        $types = Type::all();

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
