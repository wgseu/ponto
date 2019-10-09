<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Cartao;
use App\Models\Forma;
use Faker\Generator as Faker;

$factory->define(Cartao::class, function (Faker $faker) {
    $forma_id = factory(Forma::class)->create();
    return [
        'forma_id' => $forma_id->id,
        'bandeira' => $faker->unique()->name,
    ];
});
