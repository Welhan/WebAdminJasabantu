<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use App\Models\UserModel;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        $faker = Faker::create();
        for ($i = 0; $i < 10; $i++) {
            UserModel::create([

                'UniqueID' => $faker->randomDigit,
                'Name' => $faker->name,
                'Email' => $faker->unique()->safeEmail,
                'Pin' => bcrypt($faker->numberBetween(100000, 999999)), // Password yang sama untuk semua pengguna, ganti sesuai kebutuhan Anda
                'Phone' => $faker->phoneNumber(), // Password yang sama untuk semua pengguna, ganti sesuai kebutuhan Anda
            ]);
        }
    }
}
