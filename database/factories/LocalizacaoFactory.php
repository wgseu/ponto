<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Localizacao;
use App\Models\Cliente;
use App\Models\Bairro;
use Faker\Generator as Faker;

$factory->define(Localizacao::class, function (Faker $faker) {
    $cliente = factory(Cliente::class)->create();
    $bairro = factory(Bairro::class)->create();
    return [
        'cliente_id' => $cliente->id,
        'bairro_id' => $bairro->id,
        'logradouro' => $faker->name,
        'numero' => $faker->name,
    ];
});
