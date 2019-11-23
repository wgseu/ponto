<?php

/**
 * Copyright 2014 da GrandChef - GrandChef Desenvolvimento de Sistemas LTDA
 *
 * Este arquivo é parte do programa GrandChef - Sistema para Gerenciamento de Restaurantes e Afins.
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
 * @author Equipe GrandChef <desenvolvimento@grandchef.com.br>
 */

namespace Tests\Feature;

use App\Models\Bairro;
use App\Models\Categoria;
use Tests\TestCase;
use App\Models\Produto;
use App\Models\Promocao;
use App\Models\Servico;
use App\Models\Zona;

class PromocaoTest extends TestCase
{
    public function testCreatePromocao()
    {
        $headers = PrestadorTest::authOwner();
        $seed_promocao = factory(Promocao::class)->create();
        $response = $this->graphfl('create_promocao', [
            'input' => [
                'inicio' => 1700,
                'fim' => 1800,
                'valor' => 1.50,
                'categoria_id' => $seed_promocao->categoria_id
            ]
        ], $headers);

        $found_promocao = Promocao::findOrFail($response->json('data.CreatePromocao.id'));
        $this->assertEquals(1700, $found_promocao->inicio);
        $this->assertEquals(1800, $found_promocao->fim);
        $this->assertEquals(1.50, $found_promocao->valor);
    }

    public function testUpdatePromocao()
    {
        $headers = PrestadorTest::authOwner();
        $promocao = factory(Promocao::class)->create();
        $this->graphfl('update_promocao', [
            'id' => $promocao->id,
            'input' => [
                'inicio' => 1700,
                'fim' => 1800,
                'valor' => 1.50,
            ]
        ], $headers);
        $promocao->refresh();
        $this->assertEquals(1700, $promocao->inicio);
        $this->assertEquals(1800, $promocao->fim);
        $this->assertEquals(1.50, $promocao->valor);
    }

    public function testDeletePromocao()
    {
        $headers = PrestadorTest::authOwner();
        $promocao_to_delete = factory(Promocao::class)->create();
        $this->graphfl('delete_promocao', ['id' => $promocao_to_delete->id], $headers);
        $promocao_to_delete->refresh();
        $this->assertTrue($promocao_to_delete->trashed());
        $this->assertNotNull($promocao_to_delete->data_arquivado);
    }

    public function testFindPromocao()
    {
        $headers = PrestadorTest::authOwner();
        $promocao = factory(Promocao::class)->create();
        $response = $this->graphfl('query_promocao', [ 'id' => $promocao->id ], $headers);
        $this->assertEquals($promocao->id, $response->json('data.promocoes.data.0.id'));
    }

    public function testTipoCategoria()
    {
        $seed_promocao = factory(Promocao::class)->create();
        $headers = PrestadorTest::authOwner();
        $this->expectException('Exception');
        $this->graphfl('create_promocao', [
            'input' => [
                'inicio' => 1,
                'fim' => 20,
                'valor' => 1.50,
                'categoria_id' => $seed_promocao->categoria_id
            ]
        ], $headers);
    }

    public function testTipoProduto()
    {
        $seed_produto = factory(Produto::class)->create();
        $headers = PrestadorTest::authOwner();
        $this->graphfl('create_promocao', [
            'input' => [
                'inicio' => 1700,
                'fim' => 1800,
                'valor' => 1.50,
                'produto_id' => $seed_produto->id
            ]
        ], $headers);
        $this->expectException('Exception');
        $this->graphfl('create_promocao', [
            'input' => [
                'inicio' => 1700,
                'fim' => 1800,
                'valor' => 1.50,
                'produto_id' => $seed_produto->id
            ]
        ], $headers);
    }

    public function testTipoServico()
    {
        $seed_servico = factory(Servico::class)->create();
        $headers = PrestadorTest::authOwner();
        $this->graphfl('create_promocao', [
            'input' => [
                'inicio' => 1700,
                'fim' => 1800,
                'valor' => 1.50,
                'servico_id' => $seed_servico->id
            ]
        ], $headers);
        $this->expectException('Exception');
        $this->graphfl('create_promocao', [
            'input' => [
                'inicio' => 1700,
                'fim' => 1800,
                'valor' => 1.50,
                'servico_id' => $seed_servico->id
            ]
        ], $headers);
    }

    public function testTipoNulo()
    {
        $headers = PrestadorTest::authOwner();
        $this->expectException('Exception');
        $this->graphfl('create_promocao', [
            'input' => [
                'inicio' => 1700,
                'fim' => 1800,
                'valor' => 1.50,
            ]
        ], $headers);
    }

    public function testTipoMultiplo()
    {
        $seed_produto = factory(Produto::class)->create();
        $seed_servico = factory(Servico::class)->create();
        $headers = PrestadorTest::authOwner();
        $this->expectException('Exception');
        $this->graphfl('create_promocao', [
            'input' => [
                'inicio' => 1700,
                'fim' => 1800,
                'valor' => 1.50,
                'produto_id' => $seed_produto->id,
                'servico_id' => $seed_servico->id,
            ]
        ], $headers);
    }

    public function testTipoServicoNuloBairro()
    {
        $seed_bairro = factory(Bairro::class)->create();
        $headers = PrestadorTest::authOwner();
        $this->expectException('Exception');
        $this->graphfl('create_promocao', [
            'input' => [
                'inicio' => 1700,
                'fim' => 1800,
                'valor' => 1.50,
                'bairro_id' => $seed_bairro->id
            ]
        ], $headers);
    }

    public function testBairroVazio()
    {
        $seed_zona = factory(Zona::class)->create();
        $headers = PrestadorTest::authOwner();
        $this->expectException('Exception');
        $this->graphfl('create_promocao', [
            'input' => [
                'inicio' => 1700,
                'fim' => 1800,
                'valor' => 1.50,
                'zona_id' => $seed_zona->id
            ]
        ], $headers);
    }

    public function testInicioMaiorFim()
    {
        $headers = PrestadorTest::authOwner();
        $seed_categoria = factory(Categoria::class)->create();
        $this->expectException('Exception');
        $this->graphfl('create_promocao', [
            'input' => [
                'inicio' => 1800,
                'fim' => 1700,
                'valor' => 1.50,
                'agendamento' => false,
                'categoria_id' => $seed_categoria->id
            ]
        ], $headers);
    }

    public function testPromocaoInicioConflito()
    {
        $headers = PrestadorTest::authOwner();
        $seed_promocao = factory(Promocao::class)->create();
        $this->expectException('Exception');
        $this->graphfl('create_promocao', [
            'input' => [
                'inicio' => 1500,
                'fim' => 1650,
                'valor' => 1.50,
                'evento' => false,
                'categoria_id' => $seed_promocao->categoria_id
            ]
        ], $headers);
    }

    public function testPontoNegativo()
    {
        $headers = PrestadorTest::authOwner();
        $seed_categoria = factory(Categoria::class)->create();
        $this->expectException('Exception');
        $this->graphfl('create_promocao', [
            'input' => [
                'inicio' => 1700,
                'fim' => 1800,
                'valor' => 1.50,
                'categoria_id' => $seed_categoria->id,
                'pontos' => -2,
            ]
        ], $headers);
    }

    public function testPontoPositivo()
    {
        $headers = PrestadorTest::authOwner();
        $seed_promocao = factory(Promocao::class)->create();
        $seed_categoria = factory(Categoria::class)->create();
        $this->expectException('Exception');
        $this->graphfl('create_promocao', [
            'input' => [
                'inicio' => 1700,
                'fim' => 1800,
                'valor' => 1.50,
                'categoria_id' => $seed_categoria->id,
                'promocao_id' => $seed_promocao->id,
                'pontos' => 2,
            ]
        ], $headers);
    }

    public function testValorAgendamento()
    {
        $headers = PrestadorTest::authOwner();
        $seed_categoria = factory(Categoria::class)->create();
        $this->expectException('Exception');
        $this->graphfl('create_promocao', [
            'input' => [
                'inicio' => 1700,
                'fim' => 1800,
                'valor' => 1.50,
                'categoria_id' => $seed_categoria->id,
                'valor' => -1,
                'agendamento' => true,
            ]
        ], $headers);
    }
}
