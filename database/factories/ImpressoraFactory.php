<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Impressora;
use App\Models\Dispositivo;
use App\Models\Setor;
use Faker\Generator as Faker;

$factory->define(Impressora::class, function (Faker $faker) {
    $dispositivo = factory(Dispositivo::class)->create();
    $setor = factory(Setor::class)->create();
    return [
        'dispositivo_id' => $dispositivo->id,
        'setor_id' => $setor->id,
        'nome' => $faker->name,
        'modelo' => $faker->name,
    ];
});
