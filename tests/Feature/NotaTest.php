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
use App\Models\Nota;
use Illuminate\Foundation\Testing\RefreshDatabase;

class NotaTest extends TestCase
{
    use RefreshDatabase;

    public function testCreateNota()
    {
        $headers = PrestadorTest::auth();
        $seed_nota =  factory(Nota::class)->create();
        $response = $this->graphfl('create_nota', [
            'input' => [
                'tipo' => Nota::TIPO_NOTA,
                'ambiente' => Nota::AMBIENTE_HOMOLOGACAO,
                'acao' => Nota::ACAO_AUTORIZAR,
                'estado' => Nota::ESTADO_ABERTO,
                'serie' => 1,
                'numero_inicial' => 1,
                'numero_final' => 1,
                'sequencia' => 1,
                'contingencia' => true,
                'data_emissao' => '2016-12-25 12:15:00',
            ]
        ], $headers);

        $found_nota = Nota::findOrFail($response->json('data.CreateNota.id'));
        $this->assertEquals(Nota::TIPO_NOTA, $found_nota->tipo);
        $this->assertEquals(Nota::AMBIENTE_HOMOLOGACAO, $found_nota->ambiente);
        $this->assertEquals(Nota::ACAO_AUTORIZAR, $found_nota->acao);
        $this->assertEquals(Nota::ESTADO_ABERTO, $found_nota->estado);
        $this->assertEquals(1, $found_nota->serie);
        $this->assertEquals(1, $found_nota->numero_inicial);
        $this->assertEquals(1, $found_nota->numero_final);
        $this->assertEquals(1, $found_nota->sequencia);
        $this->assertEquals(true, $found_nota->contingencia);
        $this->assertEquals('2016-12-25 12:15:00', $found_nota->data_emissao);
    }

    public function testUpdateNota()
    {
        $headers = PrestadorTest::auth();
        $nota = factory(Nota::class)->create();
        $this->graphfl('update_nota', [
            'id' => $nota->id,
            'input' => [
                'tipo' => Nota::TIPO_NOTA,
                'ambiente' => Nota::AMBIENTE_HOMOLOGACAO,
                'acao' => Nota::ACAO_AUTORIZAR,
                'estado' => Nota::ESTADO_ABERTO,
                'serie' => 1,
                'numero_inicial' => 1,
                'numero_final' => 1,
                'sequencia' => 1,
                'contingencia' => true,
                'data_emissao' => '2016-12-28 12:30:00',
            ]
        ], $headers);
        $nota->refresh();
        $this->assertEquals(Nota::TIPO_NOTA, $nota->tipo);
        $this->assertEquals(Nota::AMBIENTE_HOMOLOGACAO, $nota->ambiente);
        $this->assertEquals(Nota::ACAO_AUTORIZAR, $nota->acao);
        $this->assertEquals(Nota::ESTADO_ABERTO, $nota->estado);
        $this->assertEquals(1, $nota->serie);
        $this->assertEquals(1, $nota->numero_inicial);
        $this->assertEquals(1, $nota->numero_final);
        $this->assertEquals(1, $nota->sequencia);
        $this->assertEquals(true, $nota->contingencia);
        $this->assertEquals('2016-12-28 12:30:00', $nota->data_emissao);
    }

    public function testDeleteNota()
    {
        $headers = PrestadorTest::auth();
        $nota_to_delete = factory(Nota::class)->create();
        $nota_to_delete = $this->graphfl('delete_nota', ['id' => $nota_to_delete->id], $headers);
        $nota_to_delete->refresh();
        $this->assertTrue($nota_to_delete->trashed());
        $this->assertNotNull($nota_to_delete->data_arquivado);
    }

    public function testFindNota()
    {
        $headers = PrestadorTest::auth();
        $nota = factory(Nota::class)->create();
        $response = $this->graphfl('query_nota', [ 'id' => $nota->id ], $headers);
        $this->assertEquals($nota->id, $response->json('data.notas.data.0.id'));
    }
}
