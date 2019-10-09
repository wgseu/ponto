<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Endereco;
use App\Models\Cidade;
use App\Models\Bairro;
use Faker\Generator as Faker;

$factory->define(Endereco::class, function (Faker $faker) {
    $cidade_id = factory(Cidade::class)->create();
    $bairro_id = factory(Bairro::class)->create();
    return [
        'cidade_id' => $cidade_id->id,
        'bairro_id' => $bairro_id->id,
        'logradouro' => $faker->unique()->name,
        'cep' => $faker->unique()->name,
    ];
});
