<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use App\Models\Program;
use App\Models\Event;

class EventSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();

        // Obtener el ID de programas existentes para establecer la relación
        $programIds = Program::pluck('id')->toArray();

        // Crear más eventos de prueba con datos aleatorios
        for ($i = 0; $i < 5; $i++) {
            Event::create([
                'name' => $faker->sentence,
                'start_date' => $faker->dateTimeBetween('now', '+1 month'),
                'end_date' => $faker->dateTimeBetween('+2 months', '+3 months'),
                'payment_limit' => $faker->dateTimeBetween('now', '+1 month'),
                'price' => $faker->numberBetween(100, 1000),
                'initial_fee' => $faker->numberBetween(20, 200),
                'terms_and_conditions' => $faker->paragraph,
                'program_id' => $faker->randomElement($programIds),
            ]);
        }
    }
}
