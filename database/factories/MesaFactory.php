<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Mesa;
use App\Models\Setor;
use Faker\Generator as Faker;

$factory->define(Mesa::class, function (Faker $faker) {
    $setor = factory(Setor::class)->create();
    $numero = $faker->unique()->numberBetween(1, 10000);
    return [
        'setor_id' => $setor->id,
        'numero' => $numero,
        'nome' => __('messages.table_number', ['number' => $numero]),
    ];
});
