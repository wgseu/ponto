<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Sessao;
use App\Models\Cozinha;
use Illuminate\Support\Carbon;
use Faker\Generator as Faker;

$factory->define(Sessao::class, function (Faker $faker) {
    $cozinha = factory(Cozinha::class)->create();
    return [
        'cozinha_id' => $cozinha->id,
        'data_inicio' => Carbon::now(),
    ];
});
