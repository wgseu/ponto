<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Evento;
use App\Models\Nota;
use Faker\Generator as Faker;

$factory->define(Evento::class, function (Faker $faker) {
    $nota = factory(Nota::class)->create();
    return [
        'nota_id' => $nota->id,
        'estado' => Evento::ESTADO_ABERTO,
        'mensagem' => $faker->name,
        'codigo' => $faker->name,
    ];
});
