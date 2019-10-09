<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Localizacao;
use App\Models\Cliente;
use App\Models\Bairro;
use Faker\Generator as Faker;

$factory->define(Localizacao::class, function (Faker $faker) {
    $cliente_id = factory(Cliente::class)->create();
    $bairro_id = factory(Bairro::class)->create();
    return [
        'cliente_id' => $cliente_id->id,
        'bairro_id' => $bairro_id->id,
        'logradouro' => $faker->name,
        'numero' => $faker->name,
    ];
});
