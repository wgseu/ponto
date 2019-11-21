<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Viagem;
use App\Models\Prestador;
use Faker\Generator as Faker;
use Illuminate\Support\Carbon;

$factory->define(Viagem::class, function (Faker $faker) {
    $responsavel = factory(Prestador::class)->create();
    return [
        'responsavel_id' => $responsavel->id,
    ];
});
