<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Bairro;
use App\Models\Cidade;
use Faker\Generator as Faker;

$factory->define(Bairro::class, function (Faker $faker) {
    $cidade = factory(Cidade::class)->create();
    return [
        'cidade_id' => $cidade->id,
        'nome' => $faker->unique()->name,
        'valor_entrega' => 4.50,
    ];
});
