<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Metrica;
use App\Models\Avaliacao;
use Faker\Generator as Faker;

$factory->define(Avaliacao::class, function (Faker $faker) {
    $metrica = factory(Metrica::class)->create();
    return [
        'metrica_id' => $metrica->id,
        'estrelas' => $faker->numberBetween(1, 5),
    ];
});
