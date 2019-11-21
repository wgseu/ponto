<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Banco;
use App\Models\Carteira;
use Faker\Generator as Faker;

$factory->define(Carteira::class, function (Faker $faker) {
    $banco = factory(Banco::class)->create();
    return [
        'tipo' => Carteira::TIPO_BANCARIA,
        'descricao' => $faker->name,
        'banco_id' => $banco->id,
        'agencia' => '0000-1',
        'conta' => '22333-5',
    ];
});
