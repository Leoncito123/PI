<?php

namespace Database\Seeders;

use App\Models\Data;
use App\Models\Summaries;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class Llenado_datos extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Crear datos
        $summaries = Summaries::all();
        foreach ($summaries as $summari) {
            for ($i = 0; $i < 10; $i++) {
                for ($j = 0; $j < 10; $j++) {
                    $messageData = [
                        'date' => Carbon::now()->subDays($j)->format('Y-m-d'),
                        'time' => Carbon::now()->format('H:i:s'),
                        'value' => rand(0, 100),
                        'battery' => rand(0, 100),
                        'cuadrante' => 'Cuadrante_' . rand(1, 4)
                    ];

                    Data::create([
                        'name' => $summari->macaddress->macaddress,
                        'date' => $messageData['date'],
                        'time' => $messageData['time'],
                        'value' => $messageData['value'],
                        'battery' => $messageData['battery'],
                        'sector' => $messageData['cuadrante'],
                        'summary_id' => $summari->id
                    ]);
                }
            }
        }
    }
}
