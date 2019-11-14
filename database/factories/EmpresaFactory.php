<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Empresa;
use App\Models\Pais;
use Faker\Generator as Faker;

$factory->define(Empresa::class, function (Faker $faker) {
    $pais = factory(Pais::class)->create();
    return [
        'pais_id' => $pais->id,
    ];
});
