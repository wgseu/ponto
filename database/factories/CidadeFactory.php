<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Cidade;
use App\Models\Estado;
use Faker\Generator as Faker;

$factory->define(Cidade::class, function (Faker $faker) {
    $estado_id = factory(Estado::class)->create();
    return [
        'estado_id' => $estado_id->id,
        'nome' => $faker->unique()->name,
    ];
});
