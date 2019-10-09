<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Mesa;
use App\Models\Setor;
use Faker\Generator as Faker;

$factory->define(Mesa::class, function (Faker $faker) {
    $setor_id = factory(Setor::class)->create();
    return [
        'setor_id' => $setor_id->id,
        'numero' => $faker->numberBetween(1, 70),
    ];
});
