<?php

namespace App\Http\Controllers;

use App\Models\Data;
use App\Models\Summaries;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
  public function index(Request $request)
  {
    $selectedSector = $request->input('sector', 'all');
    $dateRange = $request->input('date_range', '7'); // Default to last 7 days

    $query = Data::query()
      ->join('summaries', 'data.summary_id', '=', 'summaries.id')
      ->join('ubications', 'summaries.ubication_id', '=', 'ubications.id')
      ->join('types', 'summaries.type_id', '=', 'types.id');

    if ($selectedSector !== 'all') {
      $query->where('data.sector', $selectedSector);
    }

    $query->where('data.date', '>=', now()->subDays($dateRange));

    $data = $query->select(
      'data.date',
      'data.value',
      'data.battery',
      'data.sector',
      'ubications.name as ubication_name',
      'types.name as type_name'
    )->get();

    $averageValue = $data->avg('value');
    $averageBattery = $data->avg('battery');
    $totalReadings = $data->count();

    $valuesByDate = $data->groupBy('date')->map->avg('value');
    $batteryByDate = $data->groupBy('date')->map->avg('battery');
    $readingsBySector = $data->groupBy('sector')->map->count();

    $sectors = Data::distinct('sector')->pluck('sector');

    return view('dashboard', compact(
      'data',
      'averageValue',
      'averageBattery',
      'totalReadings',
      'valuesByDate',
      'batteryByDate',
      'readingsBySector',
      'sectors',
      'selectedSector',
      'dateRange'
    ));
  }
}
