<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Propriedade;
use App\Models\Grupo;
use Faker\Generator as Faker;

$factory->define(Propriedade::class, function (Faker $faker) {
    $grupo = factory(Grupo::class)->create();
    return [
        'grupo_id' => $grupo->id,
        'nome' => $faker->unique()->name,
    ];
});
