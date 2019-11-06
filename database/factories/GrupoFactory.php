<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Grupo;
use App\Models\Produto;
use Faker\Generator as Faker;

$factory->define(Grupo::class, function (Faker $faker) {
    $produto_id = factory(Produto::class)->create(['tipo' => Produto::TIPO_PACOTE]);
    return [
        'produto_id' => $produto_id->id,
        'nome' => $faker->unique()->name,
        'descricao' => $faker->name,
    ];
});
