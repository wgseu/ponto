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
namespace MZ\Account;

use MZ\Account\ClassificacaoTest;
use MZ\Account\ClienteTest;
use MZ\Provider\PrestadorTest;
use MZ\Exception\ValidationException;

class ContaTest extends \MZ\Framework\TestCase
{
    /**
     * Build a valid conta
     * @param string $descricao Conta descrição
     * @return Conta
     */
    public static function build($descricao = null)
    {
        $last = Conta::find([], ['id' => -1]);
        $id = $last->getID() + 1;
        $classificacao = ClassificacaoTest::create();
        $prestador = PrestadorTest::create();
        $cliente = ClienteTest::create();
        $conta = new Conta();
        $conta->setClassificacaoID($classificacao->getID());
        $conta->setFuncionarioID($prestador->getID());
        $conta->setClienteID($cliente->getID());
        $conta->setTipo(Conta::TIPO_RECEITA);
        $conta->setDescricao($descricao ?: "Conta {$id}");
        $conta->setValor(12.3);
        $conta->setConsolidado(12.3);
        $conta->setFonte(Conta::FONTE_FIXA);
        $conta->setNumeroParcela(123);
        $conta->setParcelas(123);
        $conta->setFrequencia(123);
        $conta->setModo(Conta::MODO_DIARIO);
        $conta->setAutomatico('Y');
        $conta->setAcrescimo(12.3);
        $conta->setMulta(12.3);
        $conta->setJuros(12.3);
        $conta->setFormula(Conta::FORMULA_SIMPLES);
        $conta->setVencimento('2016-12-25 12:15:00');
        $conta->setEstado(Conta::ESTADO_ATIVA);
        $conta->setDataEmissao('2016-12-25 12:15:00');
        return $conta;
    }

    /**
     * Create a conta on database
     * @param string $descricao Conta descrição
     * @return Conta
     */
    public static function create($descricao = null)
    {
        $conta = self::build($descricao);
        $conta->insert();
        return $conta;
    }

    public function testFind()
    {
        $conta = self::create();
        $condition = ['descricao' => $conta->getDescricao()];
        $found_conta = Conta::find($condition);
        $this->assertEquals($conta, $found_conta);
        list($found_conta) = Conta::findAll($condition, [], 1);
        $this->assertEquals($conta, $found_conta);
        $this->assertEquals(1, Conta::count($condition));
    }

    public function testFinds()
    {
        $conta = self::create();

        //findClassificacaoID
        $classificacao = $conta->findClassificacaoID();
        $this->assertEquals($conta->getClassificacaoID(), $classificacao->getID());
        //find Funcionario
        $funcionario = $conta->findFuncionarioID();
        $this->assertEquals($conta->getFuncionarioID(), $funcionario->getID());
        //findConta
        $contaF = $conta->findContaID();
        $this->assertEquals($conta->getContaID(), $contaF->getID());
        //findCarteiraID
        $carteira = $conta->findCarteiraID();
        $this->assertEquals($conta->getCarteiraID(), $carteira->getID());
        //findAgrupamentoID
        $agrupamento = $conta->findAgrupamentoID();
        $this->assertEquals($conta->getAgrupamentoID(), $agrupamento->getID());
        //findClienteID
        $cliente = $conta->findClienteID();
        $this->assertEquals($conta->getClienteID(), $cliente->getID());
        //findPedidoID
        $pedido = $conta->findPedidoID();
        $this->assertEquals($conta->getPedidoID(), $pedido->getID());
    }


    public function testAdd()
    {
        $conta = self::build();
        $conta->insert();
        $this->assertTrue($conta->exists());
    }

    public function testAddInvalid()
    {
        $conta = new Conta();
        $conta->setClassificacaoID(null);
        $conta->setFuncionarioID(null);
        $conta->setTipo(null);
        $conta->setDescricao(null);
        $conta->setValor(null);
        $conta->setConsolidado(null);
        $conta->setFonte(null);
        $conta->setNumeroParcela(null);
        $conta->setParcelas(null);
        $conta->setFrequencia(null);
        $conta->setModo(null);
        $conta->setAutomatico(null);
        $conta->setAcrescimo(null);
        $conta->setMulta(null);
        $conta->setJuros(null);
        $conta->setFormula(null);
        $conta->setVencimento(null);
        $conta->setEstado(null);
        $conta->setDataEmissao(null);
        try {
            $conta->insert();
            $this->fail('Não pode cadastrar com valores NULL');
        } catch (ValidationException $e) {
            $this->assertEquals(['classificacaoid', 'funcionarioid', 'tipo', 'descricao', 'valor', 'consolidado', 'fonte',
            'numeroparcela', 'parcelas', 'frequencia', 'modo', 'automatico', 'acrescimo', 'multa', 'juros', 'formula', 'vencimento', 'estado',
            'dataemissao'], array_keys($e->getErrors()));
        }
        //---------------------------
        $conta = self::build();
        $conta->setValor(0);
        try {
            $conta->insert();
            $this->fail('Não cadastrar conta com valor ZERO');
        } catch (ValidationException $e) {
            $this->assertEquals(['valor'], array_keys($e->getErrors()));
        }
        //---------------------------
        $conta = self::build();
        $conta->setTipo('Receita');
        $conta->setValor(-3);
        try {
            $conta->insert();
            $this->fail('Não cadastrar conta com RECEITA NEGATIVA');
        } catch (ValidationException $e) {
            $this->assertEquals(['valor'], array_keys($e->getErrors()));
        }
        //---------------------------
        $conta = self::build();
        $conta->setTipo('Despesa');
        $conta->setValor(15);
        try {
            $conta->insert();
            $this->fail('Não cadastrar conta com DESPESA POSITIVA');
        } catch (ValidationException $e) {
            $this->assertEquals(['valor', 'acrescimo', 'multa'], array_keys($e->getErrors()));
        }
        //---------------------------
        $conta = self::build();
        $conta->setTipo('Receita');
        $conta->setAcrescimo(-1);
        try {
            $conta->insert();
            $this->fail('Conta acrescimo não pode ser negativa');
        } catch (ValidationException $e) {
            $this->assertEquals(['acrescimo'], array_keys($e->getErrors()));
        }
        //---------------------------
        $conta = self::build();
        $conta->setTipo('Despesa');
        $conta->setAcrescimo(5);
        try {
            $conta->insert();
            $this->fail('Conta multa não pode ser positiva');
        } catch (ValidationException $e) {
            $this->assertEquals(['valor', 'acrescimo', 'multa'], array_keys($e->getErrors()));
        }
        //---------------------------
        $conta = self::build();
        $conta->setTipo('Receita');
        $conta->setMulta(-9);
        try {
            $conta->insert();
            $this->fail('Conta multa não pode ser negativa');
        } catch (ValidationException $e) {
            $this->assertEquals(['multa'], array_keys($e->getErrors()));
        }
        //---------------------------
        $conta = self::build();
        $conta->setJuros(-8);
        try {
            $conta->insert();
            $this->fail('Juros não pode ser negativo');
        } catch (ValidationException $e) {
            $this->assertEquals(['juros'], array_keys($e->getErrors()));
        }
        //---------------------------
        $conta = self::build();
        $conta->setTipo('Receita');
        $conta->setMulta(-9);
        try {
            $conta->insert();
            $this->fail('Conta multa não pode ser negativa');
        } catch (ValidationException $e) {
            $this->assertEquals(['multa'], array_keys($e->getErrors()));
        }
    }

    public function testUpdateInvalid()
    {
        $conta = self::create();
        $conta->setEstado(Conta::ESTADO_PAGA);
        $conta->update();
        try {
            $conta->setEstado(Conta::ESTADO_ANALISE);
            $conta->update();
        } catch (ValidationException $e) {
            $this->assertEquals(['id'], array_keys($e->getErrors()));
        }
        //----------
        $conta = self::create();
        $conta->setEstado(Conta::ESTADO_CANCELADA);
        $conta->update();
        try {
            $conta->setEstado(Conta::ESTADO_ANALISE);
            $conta->update();
        } catch (ValidationException $e) {
            $this->assertEquals(['id'], array_keys($e->getErrors()));
        }
        // $this->getEstado() == self::ESTADO_PAGA && !is_equal($this->getConsolidado(), $this->getValor())
        // $old_conta->getEstado() == self::ESTADO_PAGA && !is_equal($old_conta->getConsolidado(), $this->getValor())
        $conta = self::create();
        $conta->setEstado(Conta::ESTADO_PAGA);
        $conta->setConsolidado(12.3);
        $conta->update();
        try {
            $conta->setValor(15.);
            $conta->update();
        } catch (ValidationException $e) {
            $this->assertEquals(['id'], array_keys($e->getErrors()));
        }
    }

    // public function testClienteSemLimite()
    // {
    //     //general error: 1 misuse of aggregate: SUM()
    //     $cliente = ClienteTest::build();
    //     $cliente->setLimiteCompra(5.);
    //     $cliente->insert();

    //     $conta = self::build();
    //     $conta->setClienteID($cliente->getID());
    //     try {
    //         $conta->insert();
    //         $this->fail('Cliente sem limite ');
    //     } catch (ValidationException $e) {
    //         $this->assertEquals(['valor'], array_keys($e->getErrors()));
    //     }
    // }

    public function testIsCancelada()
    {
        $conta = self::build();
        $conta->setEstado(Conta::ESTADO_CANCELADA);
        $this->assertTrue($conta->isCancelada());

    }

    public function testMakeAnexoURL()
    {
        $conta = new Conta();
        //static/doc/conta
        $makeUrl = $conta->makeAnexoURL(true);
        $this->assertEquals('/static/doc/conta.png', $makeUrl);
        $conta->setAnexoURL('imagem.png');
        $this->assertEquals('/static/doc/conta/imagem.png', $conta->makeAnexoURL());
    }

    public function testClean()
    {
        $old_conta = new Conta();
        $old_conta->setAnexoURL('contanaoexistente2.png');
        $conta = new Conta();
        $conta->setAnexoURL('contanaoexistente.png');
        $conta->clean($old_conta);
        $this->assertEquals($old_conta, $conta);
    }

    public function testGetTipo()
    {
        $conta = self::create();
        $options = Conta::getTipoOptions();
        $this->assertEquals(Conta::getTipoOptions($conta->getTipo()), $options[$conta->getTipo()]);
    }

    public function testGetFonte()
    {
        $conta = self::create();
        $options = Conta::getFonteOptions();
        $this->assertEquals(Conta::getFonteOptions($conta->getFonte()), $options[$conta->getFonte()]);
    }

    public function testGetModo()
    {
        $conta = self::create();
        $options = Conta::getModoOptions();
        $this->assertEquals(Conta::getModoOptions($conta->getModo()), $options[$conta->getModo()]);
    }

    public function testGetFormula()
    {
        $conta = self::create();
        $options = Conta::getFormulaOptions();
        $this->assertEquals(Conta::getFormulaOptions($conta->getFormula()), $options[$conta->getFormula()]);
    }

    public function testGetEstado()
    {
        $conta = self::create();
        $options = Conta::getEstadoOptions();
        $this->assertEquals(Conta::getEstadoOptions($conta->getEstado()), $options[$conta->getEstado()]);
    }

    public function testUpdate()
    {
        $conta = self::create();
        $conta->update();
        $this->assertTrue($conta->exists());
    }

    public function testDelete()
    {
        $conta = self::create();
        $conta->delete();
        $conta->loadByID();
        $this->assertFalse($conta->exists());
    }
}
