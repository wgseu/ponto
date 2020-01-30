<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Nota;
use Faker\Generator as Faker;
use Illuminate\Support\Carbon;

$factory->define(Nota::class, function (Faker $faker) {
    return [
        'tipo' => Nota::TIPO_NOTA,
        'ambiente' => Nota::AMBIENTE_HOMOLOGACAO,
        'acao' => Nota::ACAO_AUTORIZAR,
        'estado' => Nota::ESTADO_ABERTO,
        'serie' => $faker->numberBetween(1, 70),
        'numero_inicial' => $faker->numberBetween(1, 70),
        'numero_final' => $faker->numberBetween(1, 70),
        'sequencia' => $faker->numberBetween(1, 70),
        'contingencia' => false,
        'data_emissao' => Carbon::now()->format('c'),
    ];
});
