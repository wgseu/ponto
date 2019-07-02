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
namespace MZ\Payment;

use MZ\Wallet\CarteiraTest;
use MZ\Wallet\MoedaTest;
use MZ\Session\MovimentacaoTest;
use MZ\Session\CaixaTest;
use MZ\Provider\PrestadorTest;
use MZ\Payment\FormaPagtoTest;
use MZ\Sale\PedidoTest;
use MZ\Account\ContaTest;
use MZ\Payment\CartaoTest;
use MZ\Payment\ChequeTest;
use MZ\Account\CreditoTest;
use MZ\Environment\MesaTest;
use MZ\Sale\Pedido;
use MZ\Database\DB;
use MZ\Exception\ValidationException;

class PagamentoTest extends \MZ\Framework\TestCase
{
    /**
     * Build a valid pagamento
     * @param string $detalhes detalhes do pagamento
     * @return Pagamento
     */
    public static function build($detalhes = null)
    {
        $last = Pagamento::find([], ['id' => -1]);
        $id = $last->getID() + 1;
        $carteira = CarteiraTest::create();
        $moeda = MoedaTest::create();
        $pagamento = new Pagamento();
        $pagamento->setCarteiraID($carteira->getID());
        $pagamento->setMoedaID($moeda->getID());
        $pagamento->setValor(12.3);
        $pagamento->setNumeroParcela(1);
        $pagamento->setParcelas(3);
        $pagamento->setLancado(12.3);
        $pagamento->setDetalhes($detalhes);
        $pagamento->setDataCompensacao('2016-12-25 12:15:00');
        return $pagamento;
    }

    /**
     * Create a pagamento on database
     * @param string $detalhes detalhes do pagamento
     * @return Pagamento
     */
    public static function create($detalhes = null)
    {
        $pagamento = self::build($detalhes);
        $pagamento->insert();
        return $pagamento;
    }

    public function testFind()
    {
        $pagamento = self::create('Pagamento de teste');
        $condition = ['search' => $pagamento->getDetalhes()];
        $found_pagamento = Pagamento::find($condition);
        $this->assertEquals($pagamento, $found_pagamento);
        list($found_pagamento) = Pagamento::findAll($condition, [], 1);
        $this->assertEquals($pagamento, $found_pagamento);
        $this->assertEquals(1, Pagamento::count($condition));
    }

    public function testAdd()
    {
        $pagamento = self::build();
        $pagamento->insert();
        $this->assertTrue($pagamento->exists());
    }

    public function testAddInvalid()
    {
        $pagamento = self::build();
        $pagamento->setCarteiraID(null);
        $pagamento->setMoedaID(null);
        $pagamento->setValor(null);
        $pagamento->setNumeroParcela(null);
        $pagamento->setParcelas(null);
        $pagamento->setLancado(null);
        $pagamento->setEstado('Teste');
        $pagamento->setDataCompensacao(null);
        try {
            $pagamento->insert();
            $this->fail('Valores invalidos');
        } catch (ValidationException $e) {
            $this->assertEquals(
                ['carteiraid', 'moedaid', 'valor', 'numeroparcela', 'parcelas', 'lancado', 'estado', 'datacompensacao'],
                array_keys($e->getErrors())
            );
        }
    }

    public function testMoedaID()
    {
        $pagamento = self::create();
        $findMoeda = $pagamento->findMoedaID();
        $this->assertEquals($pagamento->getMoedaID(), $findMoeda->getID());
    }

    public function testMoedaIDIsnull()
    {
        $pagamento = self::create();
        $pagamento->setMoedaID(NULL);
        $findMoeda = $pagamento->findMoedaID();
        $this->assertEquals($findMoeda->getID(), NULL);
    }

    public function testPagamentoID()
    {
        $pagamento = self::create();
        $pagamento->setPagamentoID(1);
        $findPagamento = $pagamento->findPagamentoID();
        $this->assertEquals($pagamento->getPagamentoID(), $findPagamento->getID());
    }

    public function testPagamentoIDIsnull()
    {
        $pagamento = self::create();
        $findPagamento = $pagamento->findPagamentoID();
        $this->assertEquals($findPagamento->getID(), NULL);
    }

    public function testAgrupamentoID()
    {
        $pagamento = self::create();
        $pagamento->setAgrupamentoID(1);
        $agrupamento = $pagamento->findAgrupamentoID();
        $this->assertEquals($pagamento->getID(), $agrupamento->getID());
    }

    public function testAgrupamentoIDIsnull()
    {
        $pagamento = self::create();
        $agrupamento = $pagamento->findAgrupamentoID();
        $this->assertEquals($agrupamento->getID(), NULL);
    }

    public function testMovimentacaoID()
    {
        $pagamento = self::create();
        $movimentaocao = MovimentacaoTest::create();
        $pagamento->setMovimentacaoID($movimentaocao->getID());
        $findMovimentaocao = $pagamento->findMovimentacaoID();
        $this->assertEquals($movimentaocao->getID(), $findMovimentaocao->getID());
    }

    public function testMovimentacaoIDIsnull()
    {
        $pagamento = self::create();
        $findMovimentaocao = $pagamento->findMovimentacaoID();
        $this->assertEquals($findMovimentaocao->getID(), NULL);
    }


    public function testFindFuncionarioID()
    {
        $pagamento = self::create();
        $prestador = PrestadorTest::create();
        $pagamento->setFuncionarioID($prestador->getID());
        $findPrestador = $pagamento->findFuncionarioID();
        $this->assertEquals($findPrestador->getID(), $prestador->getID());
    }

    public function testFindFuncionarioIDIsnull()
    {
        $pagamento = self::create();
        $findPrestador = $pagamento->findFuncionarioID();
        $this->assertEquals($findPrestador->getID(), null);
    }

    public function testFindFormaPagtoID()
    {
        $pagamento = self::create();
        $formaPagto = FormaPagtoTest::create();
        $pagamento->setFormaPagtoID($formaPagto->getID());
        $findFormaPagto = $pagamento->findFormaPagtoID();
        $this->assertEquals($pagamento->getFormaPagtoID(), $findFormaPagto->getID());
    }

    public function testFindFormaPagtoIDIsnull()
    {
        $pagamento = self::create();
        $findFormaPagto = $pagamento->findFormaPagtoID();
        $this->assertEquals($findFormaPagto->getID(), NULL);
    }

    public function testPedidoID()
    {
        $pagamento = self::create();
        $movimentaocao = MovimentacaoTest::create();
        $pedido = PedidoTest::create();
        $pagamento->setPedidoID($pedido->getID());
        $findPedido = $pagamento->findPedidoID();
        $this->assertEquals($pagamento->getPedidoID(), $findPedido->getID());
    }

    public function testPedidoIDIsnull()
    {
        $pagamento = self::create();
        $pedido = $pagamento->findPedidoID();
        $this->assertEquals($pedido->getID(), NULL);
    }

    public function testFindContaID()
    {
        $pagamento = self::create();
        $conta = ContaTest::create();
        $pagamento->setContaID($conta->getID());
        $findConta = $pagamento->findContaID();
        $this->assertEquals($conta->getID(), $findConta->getID());
    }

    public function testFindContaIDIsnull()
    {
        $pagamento = self::create();
        $findConta = $pagamento->findContaID();
        $this->assertEquals($findConta->getID(), NULL);
    }

    public function testFindCartaoID()
    {
        $pagamento = self::create();
        $cartao = CartaoTest::create();
        $pagamento->setCartaoID($cartao->getID());
        $findCartao = $pagamento->findCartaoID();
        $this->assertEquals($findCartao->getID(), $pagamento->getCartaoID());
    }

    public function testeFindCartaoIDIsnull()
    {
        $pagamento = self::create();
        $findCartao = $pagamento->findCartaoID();
        $this->assertEquals($findCartao->getID(), NULL);
    }

    public function testFindChequeID()
    {
        $pagamento = self::create();
        $cheque = ChequeTest::create();
        $pagamento->setChequeID($cheque->getID());
        $findCheque = $pagamento->findChequeID();
        $this->assertEquals($findCheque->getID(), $pagamento->getChequeID());
    }

    public function testFindChequeIDIsnull()
    {
        $pagamento = self::create();
        $findCheque = $pagamento->findChequeID();
        $this->assertEquals($findCheque->getID(), null);
    }

    public function testFindCrediarioID()
    {
        $pagamento = self::create();
        $conta = ContaTest::create();
        $pagamento->setCrediarioID($conta->getID());
        $findConta = $pagamento->findCrediarioID();
        $this->assertEquals($findConta->getID(), $pagamento->getCrediarioID());
    }

    public function testFindCrediarioIDIsnull()
    {
        $pagamento = self::create();
        $findCrediario = $pagamento->findCrediarioID();
        $this->assertEquals($findCrediario->getID(), null);
    }

    public function testFindCreditoID()
    {
        $pagamento = self::create();
        $credito = CreditoTest::create();
        $pagamento->setCreditoID($credito->getID());
        $findCredito = $pagamento->findCreditoID();
        $this->assertEquals($findCredito->getID(), $pagamento->getCreditoID());
    }

    public function testFindCreditoIDIsnull()
    {
        $pagamento = self::create();
        $credito = $pagamento->findCreditoID();
        $this->assertEquals($credito->getID(), null);
    }

    public function testGetEstadoOptions()
    {
        $pagamento = self::create();
        $options = Pagamento::getEstadoOptions($pagamento->getEstado());
        $this->assertEquals($pagamento->getEstado(), $options);
    }

    public function testRawFindTotal()
    {
        $pagamento = self::create();
        $movimentaocao = MovimentacaoTest::create();
        $pedido = PedidoTest::create();
        $pagamento->setPedidoID($pedido->getID());
        $pagamento->setEstado(Pagamento::ESTADO_PAGO);
        $pagamento->update();
        $condition = ['mesaid' => 11, 'm.sessaoid' => 1];
        $result = $pagamento::rawFindTotal($condition);
        $this->assertEquals(0, $result);
    }

    public function testRawFindTotalSearchNumber()
    {
        $pagamento = self::create();
        $movimentaocao = MovimentacaoTest::create();
        $pedido = PedidoTest::create();
        $pagamento->setPedidoID($pedido->getID());
        $pagamento->setEstado(Pagamento::ESTADO_PAGO);
        $pagamento->update();
        $condition = ['search' => 1];
        $result = $pagamento::rawFindTotal($condition);
        $this->assertEquals(12.3, $result);
    }

    public function testRawFindTotalDataCompensacao()
    {
        $pagamento = self::create();
        $movimentaocao = MovimentacaoTest::create();
        $pedido = PedidoTest::create();
        $pagamento->setPedidoID($pedido->getID());
        $pagamento->setEstado(Pagamento::ESTADO_PAGO);
        $pagamento->update();
        $condition = ['apartir_datacompensacao' => '2016-12-25 12:15:00'];
        $result = $pagamento::rawFindTotal($condition);
        $this->assertEquals(12.3, $result);
    }

    public function testRawFindTotalApartirLancamento()
    {
        $pagamento = self::create();
        $movimentaocao = MovimentacaoTest::create();
        $pedido = PedidoTest::create();
        $pagamento->setPedidoID($pedido->getID());
        $pagamento->setEstado(Pagamento::ESTADO_PAGO);
        $pagamento->update();
        $condition = ['ate_datalancamento' => DB::now()];
        $result = $pagamento::rawFindTotal($condition);
        $this->assertEquals(12.3, $result);
    }

    public function testRawFindTotalDataLancamento()
    {
        $pagamento = self::create();
        $movimentaocao = MovimentacaoTest::create();
        $pedido = PedidoTest::create();
        $pagamento->setPedidoID($pedido->getID());
        $pagamento->setEstado(Pagamento::ESTADO_PAGO);
        $pagamento->update();
        $condition = ['apartir_datalancamento' => DB::now()];
        $result = $pagamento::rawFindTotal($condition);
        $this->assertEquals(12.3, $result);
    }

    public function testRawFindTotalApartirValor()
    {
        $pagamento = self::create();
        $movimentaocao = MovimentacaoTest::create();
        $pedido = PedidoTest::create();
        $pagamento->setPedidoID($pedido->getID());
        $pagamento->setEstado(Pagamento::ESTADO_PAGO);
        $pagamento->update();
        $condition = ['apartir_valor' => 1];
        $result = $pagamento::rawFindTotal($condition);
        $this->assertEquals(12.3, $result);
    }

    public function testGetReceitas()
    {
        $pagamento = self::create();
        $movimentaocao = MovimentacaoTest::create();
        $pedido = PedidoTest::create();
        $pagamento->setPedidoID($pedido->getID());
        $pagamento->setEstado(Pagamento::ESTADO_PAGO);
        $pagamento->update();
        $condition = ['receitas'];
        $result = $pagamento::getReceitas($condition);
        $this->assertEquals($pagamento->getValor(), $result);
    }

    public function testGetDespesas()
    {
        $pagamento = self::create();
        $movimentaocao = MovimentacaoTest::create();
        $pedido = PedidoTest::create();
        $conta = ContaTest::create();
        $pagamento->setPedidoID($pedido->getID());
        $pagamento->setEstado(Pagamento::ESTADO_PAGO);
        $pagamento->setContaID($conta->getID());
        $pagamento->update();
        $condition = ['mesaid' => 11];
        $result = $pagamento::getDespesas($condition);
        $this->assertEquals(0, $result);
    }

    public function testGetFaturamento()
    {
        $pagamento = self::create();
        $movimentaocao = MovimentacaoTest::create();
        $pedido = PedidoTest::create();
        $conta = ContaTest::create();
        $pagamento->setPedidoID($pedido->getID());
        $pagamento->setEstado(Pagamento::ESTADO_PAGO);
        $pagamento->setContaID($conta->getID());
        $pagamento->update();
        $condition = ['mesaid' => 11];
        $result = $pagamento::getFaturamento($condition);
        $this->assertEquals($pagamento->getValor(), $result);
    }

    public function testRawFindAllTotal()
    {
        $pagamento = self::create();
        $movimentaocao = MovimentacaoTest::create();
        $pedido = PedidoTest::create();
        $conta = ContaTest::create();
        $pagamento->setPedidoID($pedido->getID());
        $pagamento->setEstado(Pagamento::ESTADO_PAGO);
        $pagamento->setContaID($conta->getID());
        $pagamento->update();
        $data = date('Y-m-d', strtotime(DB::now()));
        $condition = ['mesaid' => 11];
        $group = ['dia' => DB::now()];
        $limit = 1;
        $offset = 0;
        $result = $pagamento::rawFindAllTotal($condition, $group, $limit, $offset);
        $this->assertEquals(
            [
                [
                'total' => $pagamento->getValor(),
                'data' => $data
                ]
            ], $result);
    }

    public function testRawFindAllTotalGroup()
    {
        $pagamento = self::create();
        $movimentaocao = MovimentacaoTest::create();
        $pedido = PedidoTest::create();
        $conta = ContaTest::create();
        $pagamento->setPedidoID($pedido->getID());
        $pagamento->setEstado(Pagamento::ESTADO_PAGO);
        $pagamento->setContaID($conta->getID());
        $pagamento->update();
        $condition = ['mesaid' => 11];
        $group = ['forma_tipo' => 'Dinheiro'];
        $limit = 1;
        $offset = 0;
        $result = $pagamento::rawFindAllTotal($condition, $group, $limit, $offset);
        $this->assertEquals(
            [
                [
                'total' => $pagamento->getValor(),
                'tipo' => null
                ]
            ], $result);
    }

}
