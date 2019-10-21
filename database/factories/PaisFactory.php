<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Pais;
use App\Models\Moeda;
use Faker\Generator as Faker;

$factory->define(Pais::class, function (Faker $faker) {
    $moeda_id = factory(Moeda::class)->create();
    return [
        'nome' => $faker->unique()->name,
        'sigla' => $faker->unique()->word,
        'codigo' => $faker->unique()->word,
        'moeda_id' => $moeda->id,
        'idioma' => $faker->unique()->word,
    ];
});
