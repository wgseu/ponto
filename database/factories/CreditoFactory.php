<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Credito;
use App\Models\Cliente;
use Faker\Generator as Faker;

$factory->define(Credito::class, function (Faker $faker) {
    $cliente_id = factory(Cliente::class)->create();
    return [
        'cliente_id' => $cliente_id->id,
        'valor' => 4.50,
        'detalhes' => $faker->name,
    ];
});
