<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Conferencia;
use App\Models\Prestador;
use App\Models\Produto;
use App\Models\Setor;
use Faker\Generator as Faker;

$factory->define(Conferencia::class, function (Faker $faker) {
    $produto = factory(Produto::class)->create();
    $setor = factory(Setor::class)->create();
    return [
        'numero' => $faker->unique()->numberBetween(1, 10000),
        'produto_id' => $produto->id,
        'setor_id' => $setor->id,
        'conferido' => 4.1,
    ];
});
