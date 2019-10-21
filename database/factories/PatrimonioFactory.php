<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Patrimonio;
use App\Models\Cliente;
use Faker\Generator as Faker;

$factory->define(Patrimonio::class, function (Faker $faker) {
    $empresa_id = factory(Cliente::class)->create();
    return [
        'empresa_id' => $empresa_id->id,
        'numero' => $faker->unique()->name,
        'descricao' => $faker->name,
        'quantidade' => 2.30,
    ];
});