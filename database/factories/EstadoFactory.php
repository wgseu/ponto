<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Estado;
use App\Models\Pais;
use Faker\Generator as Faker;

$factory->define(Estado::class, function (Faker $faker) {
    $pais = factory(Pais::class)->create();
    return [
        'pais_id' => $pais->id,
        'nome' => $faker->unique()->name,
        'uf' => $faker->unique()->name,
    ];
});
