<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Telefone;
use App\Models\Cliente;
use App\Models\Pais;
use Faker\Generator as Faker;

$factory->define(Telefone::class, function (Faker $faker) {
    $cliente_id = factory(Cliente::class)->create();
    $pais_id = factory(Pais::class)->create();
    return [
        'cliente_id' => $cliente_id->id,
        'pais_id' => $pais_id->id,
        'numero' => $faker->name,
    ];
});
