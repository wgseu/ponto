<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Prestador;
use App\Models\Funcao;
use App\Models\Cliente;
use Faker\Generator as Faker;

$factory->define(Prestador::class, function (Faker $faker) {
    $funcao = factory(Funcao::class)->create();
    $cliente = factory(Cliente::class)->create();
    return [
        'codigo' => $faker->unique()->numberBetween(1, 10000) . '',
        'funcao_id' => $funcao->id,
        'cliente_id' => $cliente->id,
    ];
});
