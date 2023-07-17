<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use App\Models\Event;
use App\Models\User;
use App\Models\Student;
use App\Models\Payment;


class PaymentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();

        // Obtener el ID de usuarios, eventos y estudiantes existentes para establecer las relaciones
        $userIds = User::pluck('id')->toArray();
        $eventIds = Event::pluck('id')->toArray();
        $studentIds = Student::pluck('id')->toArray();

        
        // Crear más pagos de prueba con datos aleatorios
        for ($i = 0; $i < 5; $i++) {
            Payment::create([
                'user_id' => $faker->randomElement($userIds),
                'invoice' => $faker->unique()->numerify('INV-####'),
                'amount' => $faker->numberBetween(100, 1000),
                'event_id' => $faker->randomElement($eventIds),
                'student_id' => $faker->randomElement($studentIds),
            ]);
        }
    }
}
