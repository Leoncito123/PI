<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ubication;
use App\Models\Type;
use App\Models\Summaries;

class MapController extends Controller
{
  public function index()
  {
    return view('visualizador');
  }

  public function getSectors()
  {
    // Asumiendo que el sector es una columna en la tabla 'ubications'
    return Ubication::distinct()->pluck('sector');
  }

  public function getTypes()
  {
    return Type::select('id', 'name')->get();
  }

  public function getUbications()
  {
    return Ubication::select('id', 'name', 'sector', 'latitude', 'longitude')->get();
  }

  public function getSensors(Request $request)
  {
    $query = Summaries::with(['ubications', 'type', 'data' => function ($q) {
      $q->latest('date')->latest('time')->take(1);
    }]);

    // Filtrar por sector si está presente
    if ($request->filled('sector')) {
      $query->whereHas('ubications', function ($q) use ($request) {
        $q->where('sector', $request->sector);
      });
    }

    // Filtrar por tipo si está presente
    if ($request->filled('type')) {
      $query->where('type_id', $request->type);
    }

    $sensors = $query->get()->map(function ($summary) {
      $ubication = $summary->ubications;
      $lastData = $summary->data->first();

      return [
        'name' => $ubication ? $ubication->name : 'Desconocido',
        'latitude' => $ubication ? $ubication->latitude : null,
        'longitude' => $ubication ? $ubication->longitude : null,
        'last_value' => $lastData ? $lastData->value : 'Sin datos',
        'unit' => $summary->type ? $summary->type->unit : 'N/A',
        'battery' => $lastData ? $lastData->battery : 'N/A',
        'last_update' => $lastData ? $lastData->date . ' ' . $lastData->time : 'No actualizado',
      ];
    });

    return response()->json($sensors);
  }
}
