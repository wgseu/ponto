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
use App\Models\Cartao;
use App\Models\Dispositivo;
use App\Models\Forma;
use App\Models\Resumo;
use App\Models\Saldo;

class MovimentacaoTest extends TestCase
{
    public function testCreateMovimentacao()
    {
        $prestador = PrestadorTest::authOwner();
        $dispositivo = DispositivoTest::auth();
        $headers = ClienteTest::mergeAuth($prestador, $dispositivo);
        $cozinha = factory(Cozinha::class)->create();
        $forma = factory(Forma::class)->create();
        factory(Sessao::class)->create();
        factory(Saldo::class)->create([
            'moeda_id' => app('currency')->id,
            'carteira_id' => $forma->carteira_id,
            'valor' => 1500,
        ]);
        factory(Horario::class)->create([
            'cozinha_id' => $cozinha->id
        ]);
        $this->graphfl('create_movimentacao', [
            'input' => [
                'aberta' => true,
                'valor_inicial' => 100,
            ]
        ], $headers);
        $found_movimentacao = Movimentacao::where('aberta', true)->firstOrFail();
        $pagamento = Pagamento::where('movimentacao_id', $found_movimentacao->id)->firstOrFail();
        $this->assertEquals(true, $found_movimentacao->aberta);
        $this->assertEquals(100, $pagamento->valor);
    }

    public function testUpdateMovimentacao()
    {
        $movimentacao = factory(Movimentacao::class)->create();
        $headers = PrestadorTest::authOwner();
        $forma = factory(Forma::class)->create();
        $cartao = factory(Cartao::class)->create();
        $this->graphfl('update_movimentacao', [
            'id' => $movimentacao->id,
            'input' => [
                'aberta' => false,
                'resumos' => [
                    [
                        'forma_id' => $cartao->forma_id,
                        'cartao_id' => $cartao->id,
                        'valor' => 100
                    ],
                    [
                        'forma_id' => $forma->id,
                        'cartao_id' => null,
                        'valor' => 24.5
                    ]
                ],
            ]
        ], $headers);
        $movimentacao->refresh();
        $resumoCartao = Resumo::where('forma_id', $cartao->forma_id)->firstOrFail();
        $resumoDinheiro = Resumo::where('forma_id', $forma->id)->firstOrFail();
        $this->assertNotTrue($movimentacao->aberta);
        $this->assertEquals(100, $resumoCartao->valor);
        $this->assertEquals(24.5, $resumoDinheiro->valor);
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
            'data_fechamento' => '2019-12-28T12:30:00Z',
        ]);
    }

    public function testMovimentacaoJaEstaFechada()
    {
        $prestador = factory(Prestador::class)->create();
        $movimentacao = factory(Movimentacao::class)->create();
        $movimentacao->update([
            'aberta' => false,
            'fechador_id' => $prestador->id,
            'data_fechamento' => '2019-12-28T12:30:00Z',
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
            'data_fechamento' => '2019-12-28T12:30:00Z',
        ]);
    }

    public function testPagamentoAberto()
    {
        $movimentacao = factory(Movimentacao::class)->create();
        $pagamento = factory(Pagamento::class)->make();
        $pagamento->movimentacao_id = $movimentacao->id;
        $pagamento->save();
        $prestador = factory(Prestador::class)->create();
        $this->expectException(ValidationException::class);
        $movimentacao->update([
            'aberta' => false,
            'fechador_id' => $prestador->id,
            'data_fechamento' => '2019-12-28T12:30:00Z',
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
        $cozinha = factory(Cozinha::class)->create();
        factory(Horario::class)->create([
            'cozinha_id' => $cozinha->id,
        ]);
        $authPrestador = PrestadorTest::authOwner();
        $dispositivo = DispositivoTest::auth();
        $headers = ClienteTest::mergeAuth($authPrestador, $dispositivo);
        $this->graphfl('create_movimentacao', [
            'input' => [
                'aberta' => true,
                'valor_inicial' => 0,
            ]
        ], $headers);
        $movimentacao = Movimentacao::where('aberta', true)->firstOrFail();
        $sessao = Sessao::where('id', $movimentacao->sessao_id)->first();

        $this->assertEquals($sessao->id, $movimentacao->sessao_id);
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
                'data_abertura' => '2016-12-25T12:15:00Z',
            ]
        ], $headers);
    }

    public function testCreateMovimentacaoSemSaldo()
    {
        $prestador = PrestadorTest::authOwner();
        $dispositivo = DispositivoTest::auth();
        $headers = ClienteTest::mergeAuth($prestador, $dispositivo);
        $cozinha = factory(Cozinha::class)->create();
        factory(Horario::class)->create([
            'cozinha_id' => $cozinha->id,
        ]);
        factory(Forma::class)->create();
        factory(Sessao::class)->create();
        $this->expectException('Exception');
        $this->graphfl('create_movimentacao', [
            'input' => [
                'aberta' => true,
                'valor_inicial' => 100,
            ]
        ], $headers);
    }

    public function testCreateMovimentacaoDispositivoNaoConfigurado()
    {
        $dispositivoSemCaixa = factory(Dispositivo::class)->create(['caixa_id' => null]);
        $dispositivo = DispositivoTest::auth($dispositivoSemCaixa);
        $prestador = PrestadorTest::authOwner();
        $headers = ClienteTest::mergeAuth($prestador, $dispositivo);
        $cozinha = factory(Cozinha::class)->create();
        factory(Horario::class)->create([
            'cozinha_id' => $cozinha->id,
        ]);
        factory(Forma::class)->create();
        factory(Sessao::class)->create();
        $this->expectException('Exception');
        $this->graphfl('create_movimentacao', [
            'input' => [
                'aberta' => true,
                'valor_inicial' => 0,
            ]
        ], $headers);
    }

    public function testFechamentoSemResumo()
    {
        $prestador = PrestadorTest::authOwner();
        $dispositivo = DispositivoTest::auth();
        $headers = ClienteTest::mergeAuth($prestador, $dispositivo);
        $cozinha = factory(Cozinha::class)->create();
        $forma = factory(Forma::class)->create();
        factory(Sessao::class)->create();
        factory(Saldo::class)->create([
            'moeda_id' => app('currency')->id,
            'carteira_id' => $forma->carteira_id,
            'valor' => 1500,
        ]);
        factory(Horario::class)->create([
            'cozinha_id' => $cozinha->id
        ]);
        $this->graphfl('create_movimentacao', [
            'input' => [
                'aberta' => true,
                'valor_inicial' => 100,
            ]
        ], $headers);
        $movimentacao = Movimentacao::where('aberta', true)->firstOrFail();
        $this->graphfl('update_movimentacao', [
            'id' => $movimentacao->id,
            'input' => [
                'aberta' => false,
                'resumos' => [],
            ]
        ], $prestador);
        $movimentacao->refresh();
        $pagamento = Pagamento::where('carteira_id', $forma->carteira_id)
            ->where('valor', 100)->first();
        $this->assertNotTrue($movimentacao->aberta);
        $this->assertNotNull($pagamento);
    }
}
