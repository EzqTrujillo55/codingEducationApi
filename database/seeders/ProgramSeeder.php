<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use App\Models\Program;


class ProgramSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();

        // Crear más programas de prueba con datos aleatorios
        for ($i = 0; $i < 5; $i++) {
            Program::create([
                'name' => $faker->sentence,
                'description' => $faker->paragraph,
                'image_url' => $faker->imageUrl(400, 300, 'program', true)
            ]);
        }
    }
}
