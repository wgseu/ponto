<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Endereco;
use App\Models\Cidade;
use App\Models\Bairro;
use Faker\Generator as Faker;

$factory->define(Endereco::class, function (Faker $faker) {
    $cidade = factory(Cidade::class)->create();
    $bairro = factory(Bairro::class)->create();
    return [
        'cidade_id' => $cidade->id,
        'bairro_id' => $bairro->id,
        'logradouro' => $faker->unique()->name,
        'cep' => '87880000',
    ];
});
