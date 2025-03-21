<?php

namespace Database\Seeders;

use App\Models\Cliente;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class ClienteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

       /*  for ($i = 0; $i < 50; $i++) {
            Cliente::create([
                'nombre'   => $faker->name,
                'ruc'      => $faker->numerify('############'), // 12 dígitos para el RUC
                'correo'   => $faker->unique()->safeEmail,
                'telefono' => $faker->phoneNumber,
                'contacto' => $faker->name,
                'empresa_id'    => 1
            ]);
        } */
    }
}
//php artisan db:seed --class=ClienteSeeder