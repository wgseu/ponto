<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Acesso;
use App\Models\Funcao;
use App\Models\Permissao;
use Faker\Generator as Faker;

$factory->define(Acesso::class, function (Faker $faker) {
    $funcao_id = factory(Funcao::class)->create();
    $permissao_id = factory(Permissao::class)->create();
    return [
        'funcao_id' => $funcao_id->id,
        'permissao_id' => $permissao_id->id,
    ];
});
