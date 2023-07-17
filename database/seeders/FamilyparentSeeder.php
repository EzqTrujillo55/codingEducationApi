<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use Faker\Factory as Faker;
use App\Models\User;
use App\Models\Familyparent;


class FamilyparentSeeder extends Seeder
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
        $userId = User::first()->id;

        for ($i = 0; $i < 5; $i++) {
            Familyparent::create([
                'mothers_name' => $faker->name('female'),
                'mothers_phone' => $faker->phoneNumber,
                'mothers_email' => $faker->unique()->safeEmail,
                'fathers_name' => $faker->name('male'),
                'fathers_phone' => $faker->phoneNumber,
                'fathers_email' => $faker->unique()->safeEmail,
                'user_id' => $userId,
            ]);
        }
    }
}
