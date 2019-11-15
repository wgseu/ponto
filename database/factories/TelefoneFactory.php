<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Telefone;
use App\Models\Cliente;
use App\Models\Pais;
use Faker\Generator as Faker;

$factory->define(Telefone::class, function (Faker $faker) {
    $cliente = factory(Cliente::class)->create();
    $pais = factory(Pais::class)->create();
    return [
        'cliente_id' => $cliente->id,
        'pais_id' => $pais->id,
        'numero' => $faker->name,
    ];
});
