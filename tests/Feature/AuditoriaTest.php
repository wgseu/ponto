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
use App\Models\Auditoria;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AuditoriaTest extends TestCase
{
    use RefreshDatabase;

    public function testCreateAuditoria()
    {
        $headers = PrestadorTest::auth();
        $seed_auditoria =  factory(Auditoria::class)->create();
        $response = $this->graphfl('create_auditoria', [
            'input' => [
                'prestador_id' => $seed_auditoria->prestador_id,
                'autorizador_id' => $seed_auditoria->autorizador_id,
                'tipo' => Auditoria::TIPO_FINANCEIRO,
                'prioridade' => Auditoria::PRIORIDADE_BAIXA,
                'descricao' => 'Teste',
                'data_registro' => '2016-12-25 12:15:00',
            ]
        ], $headers);

        $found_auditoria = Auditoria::findOrFail($response->json('data.CreateAuditoria.id'));
        $this->assertEquals($seed_auditoria->prestador_id, $found_auditoria->prestador_id);
        $this->assertEquals($seed_auditoria->autorizador_id, $found_auditoria->autorizador_id);
        $this->assertEquals(Auditoria::TIPO_FINANCEIRO, $found_auditoria->tipo);
        $this->assertEquals(Auditoria::PRIORIDADE_BAIXA, $found_auditoria->prioridade);
        $this->assertEquals('Teste', $found_auditoria->descricao);
        $this->assertEquals('2016-12-25 12:15:00', $found_auditoria->data_registro);
    }

    public function testUpdateAuditoria()
    {
        $headers = PrestadorTest::auth();
        $auditoria = factory(Auditoria::class)->create();
        $this->graphfl('update_auditoria', [
            'id' => $auditoria->id,
            'input' => [
                'tipo' => Auditoria::TIPO_FINANCEIRO,
                'prioridade' => Auditoria::PRIORIDADE_BAIXA,
                'descricao' => 'Atualizou',
                'data_registro' => '2016-12-28 12:30:00',
            ]
        ], $headers);
        $auditoria->refresh();
        $this->assertEquals(Auditoria::TIPO_FINANCEIRO, $auditoria->tipo);
        $this->assertEquals(Auditoria::PRIORIDADE_BAIXA, $auditoria->prioridade);
        $this->assertEquals('Atualizou', $auditoria->descricao);
        $this->assertEquals('2016-12-28 12:30:00', $auditoria->data_registro);
    }

    public function testDeleteAuditoria()
    {
        $headers = PrestadorTest::auth();
        $auditoria_to_delete = factory(Auditoria::class)->create();
        $auditoria_to_delete = $this->graphfl('delete_auditoria', ['id' => $auditoria_to_delete->id], $headers);
        $auditoria = Auditoria::find($auditoria_to_delete->id);
        $this->assertNull($auditoria);
    }

    public function testQueryAuditoria()
    {
        for ($i=0; $i < 10; $i++) {
            factory(Auditoria::class)->create();
        }
        $headers = PrestadorTest::auth();
        $response = $this->graphfl('query_auditoria', [], $headers);
        $this->assertEquals(10, $response->json('data.auditorias.total'));
    }
}
