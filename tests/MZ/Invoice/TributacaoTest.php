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

namespace MZ\Invoice;

use MZ\Exception\ValidationException;

class TributacaoTest extends \MZ\Framework\TestCase
{
    /**
     * Build a valid tributação
     * @param string $ncm Tributação ncm
     * @return Tributacao
     */
    public static function build($ncm = null)
    {
        $last = Tributacao::find([], ['id' => -1]);
        $id = $last->getID() + 1;
        $origem = OrigemTest::create();
        $operacao = OperacaoTest::create();
        $imposto = ImpostoTest::create();
        $tributacao = new Tributacao();
        $tributacao->setNCM('NCM da tributação');
        $tributacao->setOrigemID($origem->getID());
        $tributacao->setOperacaoID($operacao->getID());
        $tributacao->setImpostoID($imposto->getID());
        return $tributacao;
    }

    /**
     * Create a tributação on database
     * @param string $ncm Tributação ncm
     * @return Tributacao
     */
    public static function create($ncm = null)
    {
        $tributacao = self::build($ncm);
        $tributacao->insert();
        return $tributacao;
    }

    public function testFinds()
    {
        $tributacao = self::create();

        $origem = $tributacao->findOrigemID();
        $this->assertEquals($tributacao->getOrigemID(), $origem->getID());

        $operacao = $tributacao->findOperacaoID();
        $this->assertEquals($tributacao->getOperacaoID(), $operacao->getID());

        $imposto = $tributacao->findImpostoID();
        $this->assertEquals($tributacao->getImpostoID(), $imposto->getID());
    }

    public function testAdd()
    {
        $tributacao = self::build();
        $tributacao->insert();
        $this->assertTrue($tributacao->exists());
    }

    public function testAddInvalid()
    {
        $tributacao = self::build();
        $tributacao->setNCM(null);
        $tributacao->setOrigemID(null);
        $tributacao->setOperacaoID(null);
        $tributacao->setImpostoID(null);
        try {
            $tributacao->insert();
            $this->fail('Valores invalidos');
        } catch (ValidationException $e) {
            $this->assertEquals(
                ['ncm', 'origemid', 'operacaoid', 'impostoid'],
                array_keys($e->getErrors())
            );
        }
    }

    public function testUpdate()
    {
        $tributacao = self::create();
        $tributacao->update();
        $this->assertTrue($tributacao->exists());
    }

    public function testDelete()
    {
        $tributacao = self::create();
        $tributacao->delete();
        $tributacao->loadByID();
        $this->assertFalse($tributacao->exists());
    }

    public function testPublish()
    {
        $tributacao = new Tributacao();
        $values = $tributacao->publish(app()->auth->provider);
        $allowed = [
            'id',
            'ncm',
            'cest',
            'origemid',
            'operacaoid',
            'impostoid',
        ];
        $this->assertEquals($allowed, array_keys($values));
    }

    public function testFromArray()
    {
        $oldTributacao = new Tributacao([
            'id' => 34,
            'ncm' => 'Teste ncm',
            'origemid' => 11,
            'operacaoid' => 11,
            'impostoid' => 11,
        ]);
        $tributacao = new Tributacao();
        $tributacao->fromArray($oldTributacao);
        $this->assertEquals($tributacao, $oldTributacao);

        $tributacao->fromArray(null);
        $this->assertEquals($tributacao, new Tributacao());
    }
}
