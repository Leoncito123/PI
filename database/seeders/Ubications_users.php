<?php

namespace Database\Seeders;

use App\Models\Ubication;
use App\Models\User;
use App\Models\User_ubication;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class Ubications_users extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        // Definir un diccionario con coordenadas aproximadas de varios países
        $countryCoordinates = [
            'Mexico' => ['lat_min' => 14.39, 'lat_max' => 32.71, 'lon_min' => -118.47, 'lon_max' => -86.81],
            'Argentina' => ['lat_min' => -55.0, 'lat_max' => -22.0, 'lon_min' => -73.56, 'lon_max' => -53.63],
            'España' => ['lat_min' => 36.0, 'lat_max' => 43.79, 'lon_min' => -9.29, 'lon_max' => 3.32],
            'Estados Unidos' => ['lat_min' => 24.396308, 'lat_max' => 49.384358, 'lon_min' => -125.0, 'lon_max' => -66.93457],
        ];

        // Ubicaciones
        foreach (range(1, 10) as $index) {
            // Seleccionar un país aleatorio
            $country = array_rand($countryCoordinates);
            $coords = $countryCoordinates[$country];

            // Generar coordenadas aleatorias dentro de los límites del país seleccionado
            $longitude = $faker->longitude($coords['lon_min'], $coords['lon_max']);
            $latitude = $faker->latitude($coords['lat_min'], $coords['lat_max']);

            foreach (range(1, 2) as $index2) {
                Ubication::create([
                    'name' => "Ubicación_$index",
                    'sector' => "Sector_$index2",
                    'longitude' => $longitude,
                    'latitude' => $latitude,
                ]);
            }
        }

        $ubications = Ubication::select('id')->get();
        $adminIndex = 0;
        $supervisorIndex = 0;
        $workerIndex = 0;

        foreach ($ubications as $ubication) {
            $admins = User::role('admin')->orderBy('id')->skip($adminIndex)->take(1)->get();
            foreach ($admins as $admin) {
                User_ubication::create([
                    'user_id' => $admin->id,
                    'ubication_id' => $ubication->id,
                ]);
                $adminIndex++;
            }

            $supervisors = User::role('supervisor')->orderBy('id')->skip($supervisorIndex)->take(2)->get();
            foreach ($supervisors as $supervisor) {
                User_ubication::create([
                    'user_id' => $supervisor->id,
                    'ubication_id' => $ubication->id,
                ]);
                $supervisorIndex++;
            }

            $workers = User::role('worker')->orderBy('id')->skip($workerIndex)->take(2)->get();
            foreach ($workers as $worker) {
                User_ubication::create([
                    'user_id' => $worker->id,
                    'ubication_id' => $ubication->id,
                ]);
                $workerIndex++;
            }
        }
    }
}
