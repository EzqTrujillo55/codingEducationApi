<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use App\Models\Student;
use App\Models\Familyparent;

class StudentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();

        // Obtener el ID de un usuario existente para establecer la relación
        $parentId = Familyparent::first()->id;

        for ($i = 0; $i < 10; $i++) {
            Student::create([
                'first_name' => $faker->firstName,
                'last_name' => $faker->lastName,
                'birthdate' => $faker->date('Y-m-d', '2000-01-01'),
                'nationality' => $faker->country,
                'passport' => $faker->bothify('??######'),
                'valid_visa' => $faker->boolean,
                'end_of_validity' => $faker->dateTimeBetween('now', '+1 year')->format('Y-m-d'),
                'student_email' => $faker->unique()->safeEmail,
                'residence_country' => $faker->country,
                'city' => $faker->city,
                'postal_code' => $faker->postcode,
                'emergency_contact_full_name' => $faker->name,
                'emergency_contact_relationship' => $faker->randomElement(['Father', 'Mother', 'Guardian']),
                'emergency_contact_email' => $faker->unique()->safeEmail,
                'emergency_contact_phone_number' => $faker->phoneNumber,
                'parents_id' => $parentId,
            ]);
        }
    }
}
