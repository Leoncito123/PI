<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            AdminSeeder::class
        ]);

        $this->call([
            Ubications_users::class
        ]);

        $this->call([
            Contendio_Seeder::class
        ]);

        $this->call([
            Llenado_datos::class
        ]);
    }
}
