<?php
/**
 * Copyright 2014 da MZ Software - MZ Desenvolvimento de Sistemas LTDA
 *
 * Este arquivo é parte do programa GrandChef - Sistema para Gerenciamento de Churrascarias, Bares e Restaurantes.
 * O GrandChef é um software proprietário; você não pode redistribuí-lo e/ou modificá-lo.
 * DISPOSIÇÕES GERAIS
 * O cliente não deverá remover qualquer identificação do produto, avisos de direitos autorais,
 * ou outros avisos ou restrições de propriedade do GrandChef.
 *
 * O cliente não deverá causar ou permitir a engenharia reversa, desmontagem,
 * ou descompilação do GrandChef.
 *
 * PROPRIEDADE DOS DIREITOS AUTORAIS DO PROGRAMA
 *
 * GrandChef é a especialidade do desenvolvedor e seus
 * licenciadores e é protegido por direitos autorais, segredos comerciais e outros direitos
 * de leis de propriedade.
 *
 * O Cliente adquire apenas o direito de usar o software e não adquire qualquer outros
 * direitos, expressos ou implícitos no GrandChef diferentes dos especificados nesta Licença.
 *
 * @author Equipe GrandChef <desenvolvimento@mzsw.com.br>
 */
namespace Tests\Feature;

use Tests\TestCase;
use App\Models\$[Table.norm];
use Illuminate\Foundation\Testing\RefreshDatabase;

class $[Table.norm]Test extends TestCase
{
    use RefreshDatabase;

    public function testCreate$[Table.norm]()
    {
        $headers = PrestadorTest::auth();
        $seed_$[table.unix] =  factory($[Table.norm]::class)->create();
        $response = $this->graphfl('create_$[table.norm]', [
            'input' => [
$[field.each(all)]
$[field.if(primary|default|null)]
$[field.else.if(enum)]
                '$[field]' => $[field.each(option)]$[field.if(first)]$[Table.norm]::$[FIELD.unix]_$[FIELD.option.norm]$[field.end]$[field.end],
$[field.else.if(blob)]
                '$[field]' => 'testeimagem',
$[field.else.if(currency)]
                '$[field]' => 1.50,
$[field.else.if(bigint)]
                '$[field]' => 1,
$[field.else.if(boolean)]
                '$[field]' => true,
$[field.else.if(double)]
                '$[field]' => 1.0,
$[field.else.if(text)]
                '$[field]' => 'Teste',
$[field.else.if(float)]
                '$[field]' => 1.30,
$[field.else.if(datetime)]
$[field.match(.*cadastro|.*criacao|.*lancamento|.*envio|.*atualizacao|.*arquivado|.*arquivamento)]
$[field.else]
                '$[field]' => '2016-12-25 12:15:00',
$[field.end]
$[field.else.if(time)]
                '$[field]' => '12:15:00',
$[field.else.if(date)]
                '$[field]' => '2016-12-25',
$[field.else.if(string)]
                '$[field]' => 'Teste',
$[field.else.if(reference)]
                '$[field]' => $seed_$[table.unix]->$[field],
$[field.else.if(integer)]
                '$[field]' => 1,
$[field.end]
$[field.end]
            ]
        ], $headers);

        $found_$[table.unix] = $[Table.norm]::findOrFail($response->json('data.Create$[Table.norm].id'));
$[field.each(all)]
$[field.if(primary|default|null)]
$[field.else.if(enum)]
        $this->assertEquals($[field.each(option)]$[field.if(first)]$[Table.norm]::$[FIELD.unix]_$[FIELD.option.norm]$[field.end]$[field.end], $found_$[table.unix]->$[field]);
$[field.else.if(blob)]
        $this->assertEquals('testeimagem', $found_$[table.unix]->$[field]);
$[field.else.if(currency)]
        $this->assertEquals(1.50, $found_$[table.unix]->$[field]);
$[field.else.if(bigint)]
        $this->assertEquals(1, $found_$[table.unix]->$[field]);
$[field.else.if(boolean)]
        $this->assertEquals(true, $found_$[table.unix]->$[field]);
$[field.else.if(double)]
        $this->assertEquals(1.0, $found_$[table.unix]->$[field]);
$[field.else.if(text)]
        $this->assertEquals('Teste', $found_$[table.unix]->$[field]);
$[field.else.if(float)]
        $this->assertEquals(1.30, $found_$[table.unix]->$[field]);
$[field.else.if(datetime)]
$[field.match(.*cadastro|.*criacao|.*lancamento|.*envio|.*atualizacao|.*arquivado|.*arquivamento)]
$[field.else]
        $this->assertEquals('2016-12-25 12:15:00', $found_$[table.unix]->$[field]);
$[field.end]
$[field.else.if(time)]
        $this->assertEquals('12:15:00', $found_$[table.unix]->$[field]);
$[field.else.if(date)]
        $this->assertEquals('2016-12-25', $found_$[table.unix]->$[field]);
$[field.else.if(string)]
        $this->assertEquals('Teste', $found_$[table.unix]->$[field]);
$[field.else.if(reference)]
        $this->assertEquals($seed_$[table.unix]->$[field], $found_$[table.unix]->$[field]);
$[field.else.if(integer)]
        $this->assertEquals(1, $found_$[table.unix]->$[field]);
$[field.end]
$[field.end]
    }

    public function testUpdate$[Table.norm]()
    {
        $headers = PrestadorTest::auth();
        $$[table.unix] = factory($[Table.norm]::class)->create();
        $this->graphfl('update_$[table.unix]', [
            'id' => $$[table.unix]->id,
            'input' => [
$[field.each(all)]
$[field.if(primary|default|null)]
$[field.else.if(enum)]
                '$[field]' => $[field.each(option)]$[field.if(first)]$[Table.norm]::$[FIELD.unix]_$[FIELD.option.norm]$[field.end]$[field.end],
$[field.else.if(blob)]
                '$[field]' => 'Atualizou',
$[field.else.if(currency)]
                '$[field]' => 1.50,
$[field.else.if(bigint)]
                '$[field]' => 1,
$[field.else.if(boolean)]
                '$[field]' => true,
$[field.else.if(double)]
                '$[field]' => 1.0,
$[field.else.if(text)]
                '$[field]' => 'Atualizou',
$[field.else.if(float)]
                '$[field]' => 1.0,
$[field.else.if(datetime)]
$[field.match(.*cadastro|.*criacao|.*lancamento|.*envio|.*atualizacao|.*arquivado|.*arquivamento)]
$[field.else]
                '$[field]' => '2016-12-28 12:30:00',
$[field.end]
$[field.else.if(time)]
                '$[field]' => '12:30:00',
$[field.else.if(date)]
                '$[field]' => '2016-12-25',
$[field.else.if(string)]
                '$[field]' => 'Atualizou',
$[field.else.if(reference)]
$[field.else.if(integer)]
                '$[field]' => 1,
$[field.end]
$[field.end]
            ]
        ], $headers);
        $$[table.norm]->refresh();
$[field.each(all)]
$[field.if(primary|default|null)]
$[field.else.if(enum)]
        $this->assertEquals($[field.each(option)]$[field.if(first)]$[Table.norm]::$[FIELD.unix]_$[FIELD.option.norm]$[field.end]$[field.end], $$[table.unix]->$[field]);
$[field.else.if(blob)]
        $this->assertEquals('Atualizou', $$[table.unix]->$[field]);
$[field.else.if(currency)]
        $this->assertEquals(1.50, $$[table.unix]->$[field]);
$[field.else.if(bigint)]
        $this->assertEquals(1, $$[table.unix]->$[field]);
$[field.else.if(boolean)]
        $this->assertEquals(true, $$[table.unix]->$[field]);
$[field.else.if(double)]
        $this->assertEquals(1.0, $$[table.unix]->$[field]);
$[field.else.if(text)]
        $this->assertEquals('Atualizou', $$[table.unix]->$[field]);
$[field.else.if(float)]
        $this->assertEquals(1.0, $$[table.unix]->$[field]);
$[field.else.if(datetime)]
$[field.match(.*cadastro|.*criacao|.*lancamento|.*envio|.*atualizacao|.*arquivado|.*arquivamento)]
$[field.else]
        $this->assertEquals('2016-12-28 12:30:00', $$[table.unix]->$[field]);
$[field.end]
$[field.else.if(time)]
        $this->assertEquals('12:30:00', $$[table.unix]->$[field]);
$[field.else.if(date)]
        $this->assertEquals('2016-12-25', $$[table.unix]->$[field]);
$[field.else.if(string)]
        $this->assertEquals('Atualizou', $$[table.unix]->$[field]);
$[field.else.if(reference)]
$[field.else.if(integer)]
        $this->assertEquals(1, $$[table.unix]->$[field]);
$[field.end]
$[field.end]
    }

    public function testDelete$[Table.norm]()
    {
        $headers = PrestadorTest::auth();
        $$[table.unix]_to_delete = factory($[Table.norm]::class)->create();
        $$[table.unix]_to_delete = $this->graphfl('delete_$[table.unix]', ['id' => $$[table.unix]_to_delete->id], $headers);
$[table.exists(data_arquivado)]
        $$[table.unix]_to_delete->refresh();
        $this->assertTrue($$[table.unix]_to_delete->trashed());
        $this->assertNotNull($$[table.unix]_to_delete->data_arquivado);
$[table.else]
        $$[table.unix] = $[Table.norm]::find($$[table.unix]_to_delete->id);
        $this->assertNull($$[table.unix]);
$[table.end]
    }

    public function testQuery$[Table.norm]()
    {
        for ($i=0; $i < 10; $i++) {
            factory($[Table.norm]::class)->create();
        }
        $headers = PrestadorTest::auth();
        $response = $this->graphfl('query_$[table.unix]', [], $headers);
        $this->assertEquals(10, $response->json('data.$[table.unix.plural].total'));
    }
}
