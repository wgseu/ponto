<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Sessao;
use Illuminate\Support\Carbon;
use Faker\Generator as Faker;

$factory->define(Sessao::class, function (Faker $faker) {
    return [
        'data_inicio' => Carbon::now(),
    ];
});
