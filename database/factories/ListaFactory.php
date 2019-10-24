<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Lista;
use App\Models\Prestador;
use Faker\Generator as Faker;
use Illuminate\Support\Carbon;

$factory->define(Lista::class, function (Faker $faker) {
    $encarregado_id = factory(Prestador::class)->create();
    return [
        'descricao' => $faker->name,
        'encarregado_id' => $encarregado_id->id,
        'data_viagem' => Carbon::now(),
    ];
});
