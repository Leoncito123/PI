<?php

namespace Database\Seeders;

use App\Models\Device;
use App\Models\Macaddress;
use App\Models\Output;
use App\Models\Summaries;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Type;
use App\Models\Ubication;

class Contendio_Seeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Crear tipos
        Type::create([
            'name' => 'Ph',
            'identifier' => 'Ph',
            'unit' => 'ph',
            'min_value' => 0,
            'max_value' => 100,
            'segment' => 3,
            'interval' => '10',
        ]);

        Type::create([
            'name' => 'Presion',
            'identifier' => 'Presion',
            'unit' => 'psi',
            'min_value' => 0,
            'max_value' => 10,
            'segment' => 3,
            'interval' => '10',
        ]);

        Type::create([
            'name' => 'Temperatura',
            'identifier' => 'Temperatura',
            'unit' => '°C',
            'min_value' => 0,
            'max_value' => 100,
            'segment' => 3,
            'interval' => '10',
        ]);

        // Crear ubicaciones y dispositivos
        $ubicaciones = Ubication::all();
        foreach ($ubicaciones as $ubication) {
            for ($i = 1; $i <= 5; $i++) {
                Device::create([
                    'name' => "Device_$ubication->id $i",
                    'ubication_id' => $ubication->id
                ]);
            }
        }

        // Crear salidas para cada dispositivo
        $devices = Device::select('id')->get();
        foreach ($devices as $device) {
            Output::create([
                'name' => "Output_$device->id",
                'dev_id' => $device->id,
                'status' => 1,
                'type_id' => rand(1, 3)
            ]);
        }

        // Crear macaddresses y summaries
        foreach ($ubicaciones as $ubication) {
            $macAddress = Macaddress::create([
                'name' => "Macaddress_$ubication->id",
                'macaddress' => "00$ubication->id",
                'sector' => $ubication->sector,
                'longitude' => $ubication->longitude,
                'latitude' => $ubication->latitude
            ]);

            Summaries::create([
                'ubication_id' => $ubication->id,
                'macaddress_id' => $macAddress->id,
                'type_id' => rand(1, 3)
            ]);
        }
    }
}
