<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Acesso;
use App\Models\Funcao;
use App\Models\Permissao;
use Faker\Generator as Faker;

$factory->define(Acesso::class, function (Faker $faker) {
    $funcao = factory(Funcao::class)->create();
    $permissao = Permissao::first();
    return [
        'funcao_id' => $funcao->id,
        'permissao_id' => $permissao->id,
    ];
});
