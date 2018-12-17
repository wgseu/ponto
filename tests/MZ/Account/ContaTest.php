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
use MZ\Provider\PrestadorTest;

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
        $conta = new Conta();
        $conta->setClassificacaoID($classificacao->getID());
        $conta->setFuncionarioID($prestador->getID());
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

    public function testAdd()
    {
        $conta = self::build();
        $conta->insert();
        $this->assertTrue($conta->exists());
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
