<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Caixa;
use App\Models\Dispositivo;
use Faker\Generator as Faker;

$factory->define(Dispositivo::class, function (Faker $faker) {
    $serial = $faker->unique()->name;
    $caixa = factory(Caixa::class)->create();
    return [
        'nome' => $faker->name,
        'tipo' => Dispositivo::TIPO_COMPUTADOR,
        'caixa_id' => $caixa->id,
        'serial' => $serial,
        'validacao' => $serial,
    ];
});
