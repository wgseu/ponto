<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Impressora;
use App\Models\Dispositivo;
use App\Models\Setor;
use Faker\Generator as Faker;

$factory->define(Impressora::class, function (Faker $faker) {
    $dispositivo_id = factory(Dispositivo::class)->create();
    $setor_id = factory(Setor::class)->create();
    return [
        'dispositivo_id' => $dispositivo_id->id,
        'setor_id' => $setor_id->id,
        'nome' => $faker->name,
        'modelo' => $faker->name,
    ];
});
