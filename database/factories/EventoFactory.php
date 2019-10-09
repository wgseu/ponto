<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Evento;
use App\Models\Nota;
use Faker\Generator as Faker;

$factory->define(Evento::class, function (Faker $faker) {
    $nota_id = factory(Nota::class)->create();
    return [
        'nota_id' => $nota_id->id,
        'estado' => Evento::ESTADO_ABERTO,
        'mensagem' => $faker->name,
        'codigo' => $faker->name,
    ];
});
