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

    if ($request->has('sector') && $request->sector !== '') {
      $query->whereHas('ubications', function ($q) use ($request) {
        $q->where('sector', $request->sector);
      });
    }

    if ($request->has('type') && $request->type !== '') {
      $query->where('type_id', $request->type);
    }

    $sensors = $query->get()->map(function ($summary) {
      $lastData = $summary->data->first();
      return [
        'name' => $summary->ubications->name,
        'latitude' => $summary->ubications->latitude,
        'longitude' => $summary->ubications->longitude,
        'last_value' => $lastData ? $lastData->value : null,
        'unit' => $summary->type->unit,
        'battery' => $lastData ? $lastData->battery : null,
        'last_update' => $lastData ? $lastData->date . ' ' . $lastData->time : null,
      ];
    });

    return response()->json($sensors);
  }
}
