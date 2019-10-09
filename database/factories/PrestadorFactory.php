<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Prestador;
use App\Models\Funcao;
use App\Models\Cliente;
use Faker\Generator as Faker;

$factory->define(Prestador::class, function (Faker $faker) {
    $funcao_id = factory(Funcao::class)->create();
    $cliente_id = factory(Cliente::class)->create();
    return [
        'codigo' => $faker->unique()->name,
        'funcao_id' => $funcao_id->id,
        'cliente_id' => $cliente_id->id,
    ];
});
