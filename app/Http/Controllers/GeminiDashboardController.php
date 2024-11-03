<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GeminiAPI\Client;
use GeminiAPI\Resources\Parts\TextPart;
use App\Models\Data;
use App\Models\Summaries;
use App\Models\Type;

class GeminiDashboardController extends Controller
{
  private $client;

  public function __construct()
  {
    $this->client = new Client(env('GEMINI_API_KEY'));
  }

  public function ask(Request $request)
  {
    $request->validate([
      'question' => 'required',
    ]);

    $selectedSector = $request->input('sector', 'all');
    $selectedUbication = $request->input('ubication', 'all');
    $selectedType = $request->input('type', 'all');
    $dateRange = $request->input('date_range', '7');

    // Construir la consulta base
    $query = Data::query()
      ->join('summaries', 'data.summary_id', '=', 'summaries.id')
      ->join('ubications', 'summaries.ubication_id', '=', 'ubications.id')
      ->join('types', 'summaries.type_id', '=', 'types.id');

    // Aplicar filtros
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

    // Obtener datos
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

    // Calcular métricas básicas
    $metrics = [
      'average_value' => $data->avg('value'),
      'average_battery' => $data->avg('battery'),
      'total_readings' => $data->count(),
      'sectors' => $data->pluck('sector')->unique()->values(),
      'types' => $data->pluck('type_name')->unique()->values(),
      'ubications' => $data->pluck('ubication_name')->unique()->values(),
    ];

    // Crear un contexto para Gemini
    $context = "Basado en los siguientes datos del dashboard:\n" .
      "- Promedio de valores: {$metrics['average_value']}\n" .
      "- Promedio de batería: {$metrics['average_battery']}%\n" .
      "- Total de lecturas: {$metrics['total_readings']}\n" .
      "- Sectores disponibles: " . implode(', ', $metrics['sectors']->toArray()) . "\n" .
      "- Tipos de datos: " . implode(', ', $metrics['types']->toArray()) . "\n" .
      "- Ubicaciones: " . implode(', ', $metrics['ubications']->toArray()) . "\n\n" .
      "Los datos específicos son:\n" .
      $data->map(function ($item) {
        return "- Fecha: {$item->date}, Sector: {$item->sector}, Valor: {$item->value} {$item->unit}, Batería: {$item->battery}%";
      })->implode("\n");

    // Combinar la pregunta del usuario con el contexto
    $fullPrompt = $context . "\n\nPregunta del usuario: " . $request->question;

    // Obtener respuesta de Gemini
    $response = $this->client->geminiPro()->generateContent(
      new TextPart($fullPrompt)
    );

    return response()->json([
      'question' => $request->question,
      'answer' => $response->text(),
      'context' => [
        'metrics' => $metrics,
        'filtered_data' => $data
      ]
    ]);
  }
}
