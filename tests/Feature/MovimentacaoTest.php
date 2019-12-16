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

use Tests\TestCase;
use App\Models\Caixa;
use App\Models\Sessao;
use App\Models\Pedido;
use App\Models\Horario;
use App\Models\Cozinha;
use App\Models\Prestador;
use App\Models\Pagamento;
use App\Models\Movimentacao;
use Illuminate\Support\Carbon;
use App\Exceptions\ValidationException;

class MovimentacaoTest extends TestCase
{
    public function testCreateMovimentacao()
    {
        $headers = PrestadorTest::authOwner();
        $cozinha = factory(Cozinha::class)->create();
        factory(Horario::class)->create([
            'cozinha_id' => $cozinha->id
        ]);
        $caixa = factory(Caixa::class)->create();
        $response = $this->graphfl('create_movimentacao', [
            'input' => [
                'caixa_id' => $caixa->id,
            ]
        ], $headers);

        $found_movimentacao = Movimentacao::findOrFail($response->json('data.CreateMovimentacao.id'));
        $this->assertEquals(true, $found_movimentacao->aberta);
        $this->assertEquals($caixa->id, $found_movimentacao->caixa_id);
    }

    public function testUpdateMovimentacao()
    {
        $movimentacao = factory(Movimentacao::class)->create();
        $headers = PrestadorTest::authOwner();
        $this->graphfl('update_movimentacao', [
            'id' => $movimentacao->id,
            'input' => [
                'aberta' => false,
            ]
        ], $headers);
        $movimentacao->refresh();
        $this->assertNotTrue($movimentacao->aberta);
    }

    public function testFindMovimentacao()
    {
        $headers = PrestadorTest::authOwner();
        $movimentacao = factory(Movimentacao::class)->create();
        $response = $this->graphfl('query_movimentacao', [ 'id' => $movimentacao->id ], $headers);
        $this->assertEquals($movimentacao->id, $response->json('data.movimentacoes.data.0.id'));
    }

    public function testSessaoFechada()
    {
        $sessao = factory(Sessao::class)->create([
            'aberta' => false,
            'data_termino' => Carbon::now(),
        ]);
        $this->expectException(ValidationException::class);
        factory(Movimentacao::class)->create([
            'sessao_id' => $sessao->id,
        ]);
    }

    public function testSessaoNaoMudar()
    {
        $movimentacao = factory(Movimentacao::class)->create();
        $movimentacao->sessao_id = 2;
        $this->expectException(ValidationException::class);
        $movimentacao->save();
    }

    public function testCaixaFechado()
    {
        $caixa = factory(Caixa::class)->create([
            'ativa' => false,
        ]);
        $this->expectException(ValidationException::class);
        factory(Movimentacao::class)->create([
            'caixa_id' => $caixa->id,
        ]);
    }

    public function testMovimentacaoCriadaAberta()
    {
        $prestador = factory(Prestador::class)->create();
        $this->expectException(ValidationException::class);
        factory(Movimentacao::class)->create([
            'aberta' => false,
            'fechador_id' => $prestador->id,
            'data_fechamento' => '2019-12-28 12:30:00',
        ]);
    }

    public function testMovimentacaoJaEstaFechada()
    {
        $prestador = factory(Prestador::class)->create();
        $movimentacao = factory(Movimentacao::class)->create();
        $movimentacao->update([
            'aberta' => false,
            'fechador_id' => $prestador->id,
            'data_fechamento' => '2019-12-28 12:30:00',
        ]);
        $movimentacao->refresh();
        $this->expectException(ValidationException::class);
        $movimentacao->update([
            'aberta' => true,
        ]);
    }

    public function testPedidoAberto()
    {
        $sessao = factory(Sessao::class)->create();
        $pedido = factory(Pedido::class)->create([
            'sessao_id' => $sessao->id,
        ]);
        $movimentacao = factory(Movimentacao::class)->create([
            'sessao_id' => $sessao->id,
        ]);
        $movimentacao->refresh();
        $prestador = factory(Prestador::class)->create();
        $this->expectException(ValidationException::class);
        $movimentacao->update([
            'aberta' => false,
            'fechador_id' => $prestador->id,
            'data_fechamento' => '2019-12-28 12:30:00',
        ]);
    }

    public function testPagamentoAberto()
    {
        $movimentacao = factory(Movimentacao::class)->create();
        $pagamento = factory(Pagamento::class)->make()->calculate();
        $pagamento->movimentacao_id = $movimentacao->id;
        $pagamento->save();
        $prestador = factory(Prestador::class)->create();
        $this->expectException(ValidationException::class);
        $movimentacao->update([
            'aberta' => false,
            'fechador_id' => $prestador->id,
            'data_fechamento' => '2019-12-28 12:30:00',
        ]);
    }

    public function testMovimentacaoExists()
    {
        $seed_movimentacao = factory(Movimentacao::class)->create();
        $this->expectException(ValidationException::class);
        factory(Movimentacao::class)->create([
            'sessao_id' => $seed_movimentacao->sessao_id,
            'caixa_id' => $seed_movimentacao->caixa_id,
            'iniciador_id' => $seed_movimentacao->iniciador_id,
            'aberta' => true,
        ]);
    }

    public function testMovimentacaoUmIndicador()
    {
        $movimentacao = factory(Movimentacao::class)->create();
        $prestador = factory(Prestador::class)->create();
        $this->expectException(ValidationException::class);
        $movimentacao->update([
            'iniciador_id' => $prestador->id,
        ]);
    }

    public function testMovimentacaoFechadorNaoNullAberto()
    {
        $this->expectException(ValidationException::class);
        factory(Movimentacao::class)->create([
            'aberta' => false,
        ]);
    }

    public function testMovimentacaoAberturaAlterar()
    {
        $movimentacao = factory(Movimentacao::class)->create();
        $movimentacao->data_abertura = '2020-01-25 12:15:00';
        $this->expectException(ValidationException::class);
        $movimentacao->save();
    }

    public function testCriarMovimentacaoSessaoNull()
    {
        $prestador = factory(Prestador::class)->create();
        $caixa = factory(Caixa::class)->create();
        $cozinha = factory(Cozinha::class)->create();
        factory(Horario::class)->create([
            'cozinha_id' => $cozinha->id,
        ]);
        $headers = PrestadorTest::authOwner();
        $response = $this->graphfl('create_movimentacao', [
            'input' => [
                'caixa_id' => $caixa->id,
            ]
        ], $headers);
        $movimentacao = Movimentacao::findOrFail($response->json('data.CreateMovimentacao.id'));
        $sessao = Sessao::where('id', $movimentacao->sessao_id)->first();

        $this->assertEquals($sessao->id, $movimentacao->sessao_id);
        $this->assertEquals($caixa->id, $movimentacao->caixa_id);
    }

    public function testCriarMovimentacaoHorarioNull()
    {
        $prestador = factory(Prestador::class)->create();
        $caixa = factory(Caixa::class)->create();
        $headers = PrestadorTest::authOwner();
        $this->expectException('Exception');
        $this->graphfl('create_movimentacao', [
            'input' => [
                'caixa_id' => $caixa->id,
                'iniciador_id' => $prestador->id,
                'data_abertura' => '2016-12-25 12:15:00',
            ]
        ], $headers);
    }
}
