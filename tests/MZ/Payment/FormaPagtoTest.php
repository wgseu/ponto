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

class FormaPagtoTest extends \MZ\Framework\TestCase
{
    /**
     * Build a valid forma de pagamento
     * @param string $descricao Forma de pagamento descrição
     * @return FormaPagto
     */
    public static function build($descricao = null)
    {
        $last = FormaPagto::find([], ['id' => -1]);
        $id = $last->getID() + 1;
        $carteira = CarteiraTest::create();
        $forma_pagto = new FormaPagto();
        $forma_pagto->setTipo(FormaPagto::TIPO_DINHEIRO);
        $forma_pagto->setCarteiraID($carteira->getID());
        $forma_pagto->setJuros(0);
        $forma_pagto->setDescricao($descricao ?: "Forma de pagamento {$id}");
        $forma_pagto->setAtiva('Y');
        return $forma_pagto;
    }

    /**
     * Create a forma de pagamento on database
     * @param string $descricao Forma de pagamento descrição
     * @return FormaPagto
     */
    public static function create($descricao = null)
    {
        $forma_pagto = self::build($descricao);
        $forma_pagto->insert();
        return $forma_pagto;
    }

    public function testFind()
    {
        $forma_pagto = self::create();
        $condition = ['descricao' => $forma_pagto->getDescricao()];
        $found_forma_pagto = FormaPagto::find($condition);
        $this->assertEquals($forma_pagto, $found_forma_pagto);
        list($found_forma_pagto) = FormaPagto::findAll($condition, [], 1);
        $this->assertEquals($forma_pagto, $found_forma_pagto);
        $this->assertEquals(1, FormaPagto::count($condition));
    }

    public function testAdd()
    {
        $forma_pagto = self::build();
        $forma_pagto->insert();
        $this->assertTrue($forma_pagto->exists());
    }

    public function testUpdate()
    {
        $forma_pagto = self::create();
        $forma_pagto->update();
        $this->assertTrue($forma_pagto->exists());
    }

    public function testDelete()
    {
        $forma_pagto = self::create();
        $forma_pagto->delete();
        $forma_pagto->loadByID();
        $this->assertFalse($forma_pagto->exists());
    }
}
