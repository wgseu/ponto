<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Permissao;
use App\Models\Funcionalidade;
use Faker\Generator as Faker;

$factory->define(Permissao::class, function (Faker $faker) {
    $funcionalidade_id = factory(Funcionalidade::class)->create();
    return [
        'funcionalidade_id' => $funcionalidade_id->id,
        'nome' => $faker->unique()->name,
        'descricao' => $faker->name,
    ];
});
