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

use MZ\System\Permissao;
use MZ\Account\AuthenticationTest;

class FormaPagtoApiControllerTest extends \MZ\Framework\TestCase
{
    public function testFind()
    {
        AuthenticationTest::authProvider([Permissao::NOME_SISTEMA, Permissao::NOME_CADASTROFORMASPAGTO]);
        $forma_pagto = FormaPagtoTest::create();
        $expected = [
            'status' => 'ok',
            'items' => [
                $forma_pagto->publish(app()->auth->provider),
            ],
        ];
        $result = $this->get('/api/formas_de_pagamento', ['search' => $forma_pagto->getDescricao()]);
        $this->assertEquals($expected, \array_intersect_key($result, $expected));
    }

    public function testAdd()
    {
        AuthenticationTest::authProvider([Permissao::NOME_SISTEMA, Permissao::NOME_CADASTROFORMASPAGTO]);
        $forma_pagto = FormaPagtoTest::build();
        $forma_pagto->setAtiva('Y');
        $this->assertTrue($forma_pagto->isAtiva());
        //se o tipo for credito é parcelado
        $forma_pagto->setTipo(FormaPagto::TIPO_CREDITO);
        $this->assertTrue($forma_pagto->isParcelado());
        
        $this->assertEquals($forma_pagto->toArray(), (new FormaPagto($forma_pagto))->toArray());
        $this->assertEquals((new FormaPagto())->toArray(), (new FormaPagto(1))->toArray());
        $expected = [
            'status' => 'ok',
            'item' => $forma_pagto->publish(app()->auth->provider),
        ];
        $result = $this->post('/api/formas_de_pagamento', $forma_pagto->toArray());
        $expected['item']['id'] = $result['item']['id'] ?? null;
        $this->assertEquals($expected, \array_intersect_key($result, $expected));
    }

    public function testUpdate()
    {
        AuthenticationTest::authProvider([Permissao::NOME_SISTEMA, Permissao::NOME_CADASTROFORMASPAGTO]);
        $forma_pagto = FormaPagtoTest::create();
        $id = $forma_pagto->getID();
        $result = $this->patch('/api/formas_de_pagamento/' . $id, $forma_pagto->toArray());
        $forma_pagto->loadByID();
        $expected = [
            'status' => 'ok',
            'item' => $forma_pagto->publish(app()->auth->provider),
        ];
        $this->assertEquals($expected, \array_intersect_key($result, $expected));
    }

    public function testDelete()
    {
        AuthenticationTest::authProvider([Permissao::NOME_SISTEMA, Permissao::NOME_CADASTROFORMASPAGTO]);
        $forma_pagto = FormaPagtoTest::create();
        $id = $forma_pagto->getID();
        $result = $this->delete('/api/formas_de_pagamento/' . $id);
        $forma_pagto->loadByID();
        $expected = [ 'status' => 'ok', ];
        $this->assertEquals($expected, \array_intersect_key($result, $expected));
        $this->assertFalse($forma_pagto->exists());
    }
}
