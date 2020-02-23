<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Telefone;
use App\Models\Cliente;
use Faker\Generator as Faker;

$factory->define(Telefone::class, function (Faker $faker) {
    $cliente = factory(Cliente::class)->create();
    $pais = app('country');
    return [
        'cliente_id' => $cliente->id,
        'pais_id' => $pais->id,
        'numero' => $faker->numerify('##########'),
    ];
});
