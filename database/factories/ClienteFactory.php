<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Cliente;
use Faker\Generator as Faker;

$factory->define(Cliente::class, function (Faker $faker) {
    return [
        'nome' => $faker->name,
        'login' => $faker->unique()->name,
        'email' => $faker->unique()->email,
        'senha' => 'Teste123',
    ];
});
