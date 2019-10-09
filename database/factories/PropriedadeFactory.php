<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Propriedade;
use App\Models\Grupo;
use Faker\Generator as Faker;

$factory->define(Propriedade::class, function (Faker $faker) {
    $grupo_id = factory(Grupo::class)->create();
    return [
        'grupo_id' => $grupo_id->id,
        'nome' => $faker->unique()->name,
    ];
});
