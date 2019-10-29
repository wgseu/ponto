<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Sistema;
use Faker\Generator as Faker;

$factory->define(Sistema::class, function (Faker $faker) {
    $faker->unique(true);
    return [
    ];
});
