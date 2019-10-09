<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Cardapio;
use App\Models\Produto;
use Faker\Generator as Faker;

$factory->define(Cardapio::class, function (Faker $faker) {
    $produto_id = factory(Produto::class)->create();
    return [
        'produto_id' => $produto_id->id,
    ];
});
