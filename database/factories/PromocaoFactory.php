<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Categoria;
use App\Models\Promocao;
use Faker\Generator as Faker;

$factory->define(Promocao::class, function (Faker $faker) {
    $categoria =  factory(Categoria::class)->create();
    return [
        'inicio' => $faker->numberBetween(1, 10),
        'fim' => $faker->numberBetween(11, 20),
        'valor' => 4.50,
        'categoria_id' => $categoria->id,
    ];
});
