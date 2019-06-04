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
namespace MZ\Product;

use MZ\System\Permissao;
use MZ\Account\AuthenticationTest;

class PacoteApiControllerTest extends \MZ\Framework\TestCase
{
    public function testFind()
    {
        AuthenticationTest::authProvider([Permissao::NOME_SISTEMA, Permissao::NOME_CADASTROPRODUTOS]);
        $pacote = PacoteTest::create();
        $expected = [
            'status' => 'ok',
            'items' => [
                $pacote->publish(app()->auth->provider),
            ],
        ];
        $result = $this->get('/api/pacotes', ['search' => $pacote->getProdutoID()]);
        $this->assertEquals($expected, \array_intersect_key($result, $expected));
    }

    public function testAdd()
    {
        AuthenticationTest::authProvider([Permissao::NOME_SISTEMA, Permissao::NOME_CADASTROPRODUTOS]);
        $pacote = PacoteTest::build();
        $pacote->setVisivel('Y');
        $this->assertTrue($pacote->isVisivel());
        $this->assertEquals($pacote->toArray(), (new Pacote($pacote))->toArray());
        $this->assertEquals((new Pacote())->toArray(), (new Pacote(1))->toArray());
        $expected = [
            'status' => 'ok',
            'item' => $pacote->publish(app()->auth->provider),
        ];
        $result = $this->post('/api/pacotes', $pacote->toArray());
        $expected['item']['id'] = $result['item']['id'] ?? null;
        $this->assertEquals($expected, \array_intersect_key($result, $expected));
    }

    public function testUpdate()
    {
        AuthenticationTest::authProvider([Permissao::NOME_SISTEMA, Permissao::NOME_CADASTROPRODUTOS]);
        $pacote = PacoteTest::create();
        $id = $pacote->getID();
        $result = $this->patch('/api/pacotes/' . $id, $pacote->toArray());
        $pacote->loadByID();
        $expected = [
            'status' => 'ok',
            'item' => $pacote->publish(app()->auth->provider),
        ];
        $this->assertEquals($expected, \array_intersect_key($result, $expected));
    }

    public function testDelete()
    {
        AuthenticationTest::authProvider([Permissao::NOME_SISTEMA, Permissao::NOME_CADASTROPRODUTOS]);
        $pacote = PacoteTest::create();
        $id = $pacote->getID();
        $result = $this->delete('/api/pacotes/' . $id);
        $pacote->loadByID();
        $expected = [ 'status' => 'ok', ];
        $this->assertEquals($expected, \array_intersect_key($result, $expected));
        $this->assertFalse($pacote->exists());
    }
}
