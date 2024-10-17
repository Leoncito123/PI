<?php

namespace Database\Seeders;

use App\Models\Ubication;
use App\Models\User;
use App\Models\User_ubication;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class Ubications_users extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //Ubicaciones
        foreach (range(1, 10) as $index) {
            foreach (range(1, 2) as $index2) {
                Ubication::create([
                    'name' => "Ubicación_$index",
                    'sector' => "Sector_$index2",
                    'longitude' => '-100.405957',
                    'latitude' => '20.552917',
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
