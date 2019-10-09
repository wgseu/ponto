<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\$[Table.norm];
$[field.each(all)]
$[field.if(null)]
$[field.else.if(reference)]
use App\Models\$[Reference.norm];
$[field.end]
$[field.end]
$[table.exists(data_cadastro|data_criacao|data_lancamento|data_envio|data_atualizacao|data_arquivado|data_arquivamento)]
$[table.else.exists(datetime|date|time)]
use Illuminate\Support\Carbon;
$[table.end]
use Faker\Generator as Faker;

$factory->define($[Table.norm]::class, function (Faker $faker) {
$[field.each(all)]
$[field.if(null)]
$[field.else.if(reference)]
    $$[field.unix] = factory($[Reference.norm]::class)->create();
$[field.end]
$[field.end]
    return [
$[field.each(all)]
$[field.if(primary|null|info)]
$[field.else.if(reference)]
        '$[field]' => $$[field.unix]->id,
$[field.else.if(date)]
        '$[field]' => Carbon::now(),
$[field.else.if(time)]
        '$[field]' => Carbon::now(),
$[field.else.if(datetime)]
$[field.match(.*cadastro|.*criacao|.*lancamento|.*envio|.*atualizacao|.*arquivado|.*arquivamento)]
$[field.else]
        '$[field]' => Carbon::now(),
$[field.end]
$[field.else.if(currency)]
        '$[field]' => 4.50,
$[field.else.if(float|double)]
        '$[field]' => 2.30,
$[field.else.if(integer|bigint)]
        '$[field]' => $faker->numberBetween(1, 70),
$[field.else.if(blob)]
        '$[field]' => 0,
$[field.else.if(boolean)]
        '$[field]' => false,
$[field.else.if(enum)]
        '$[field]' => $[field.each(option)]$[field.if(first)]$[Table.norm]::$[FIELD.unix]_$[FIELD.option.norm]$[field.end]$[field.end],
$[field.else]
        '$[field]' => $faker->$[field.if(unique)]unique()->$[field.end]name,
$[field.end]
$[field.end]
    ];
});
