<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class AdminSeeder extends Seeder
{
    public function run()
    {
        // Crear roles
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $supervisorRole = Role::firstOrCreate(['name' => 'supervisor']);
        $workerRole = Role::firstOrCreate(['name' => 'worker']);


        for ($i = 1; $i <= 1; $i++) {
            $admin = User::create([
                'name' => "Admin_$i",
                'email' => "Admin_$i@admin.com",
                'password' => Hash::make('123456789'),
            ]);

            // Asignar el rol
            $admin->assignRole($adminRole);
        }

        for ($i = 1; $i <= 10; $i++) {
            $user = User::create([
                'name' => "Supervisor_$i",
                'email' => "supervisor_$i@supervisor.com",
                'password' => Hash::make('123456789'),
            ]);

            // Asignar el rol
            $user->assignRole($supervisorRole);
        }

        for ($i = 1; $i <= 20; $i++) {
            $user = User::create([
                'name' => "Worker_$i",
                'email' => "Worker_$i@worker.com",
                'password' => Hash::make('123456789')
            ]);

            // Asignar el rol
            $user->assignRole($workerRole);
        }
    }
}
