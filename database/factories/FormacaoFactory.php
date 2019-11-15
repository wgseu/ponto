<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Formacao;
use App\Models\Item;
use Faker\Generator as Faker;

$factory->define(Formacao::class, function (Faker $faker) {
    $item = factory(Item::class)->create();
    return [
        'item_id' => $item->id,
    ];
});
