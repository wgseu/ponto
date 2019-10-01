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

class ValorNutricionalApiControllerTest extends \MZ\Framework\TestCase
{
    public function testFind()
    {
        AuthenticationTest::authProvider([Permissao::NOME_SISTEMA, Permissao::NOME_CADASTROPRODUTOS]);
        $valor_nutricional = ValorNutricionalTest::create();
        $expected = [
            'status' => 'ok',
            'items' => [
                $valor_nutricional->publish(app()->auth->provider),
            ],
        ];
        $result = $this->get('/api/valores_nutricionais', ['search' => $valor_nutricional->getNome()]);
        $this->assertEquals($expected, \array_intersect_key($result, $expected));
    }

    public function testAdd()
    {
        AuthenticationTest::authProvider([Permissao::NOME_SISTEMA, Permissao::NOME_CADASTROPRODUTOS]);
        $valor_nutricional = ValorNutricionalTest::build();
        $this->assertEquals($valor_nutricional->toArray(), (new ValorNutricional($valor_nutricional))->toArray());
        $this->assertEquals((new ValorNutricional())->toArray(), (new ValorNutricional(1))->toArray());
        $expected = [
            'status' => 'ok',
            'item' => $valor_nutricional->publish(app()->auth->provider),
        ];
        $result = $this->post('/api/valores_nutricionais', $valor_nutricional->toArray());
        $result['item']['quantidade'] = floatval($result['item']['quantidade'] ?? null);

        $expected['item']['id'] = $result['item']['id'] ?? null;
        $expected['item']['valordiario'] = $result['item']['valordiario'] ?? null;
        $this->assertEquals($expected, \array_intersect_key($result, $expected));
    }

    public function testUpdate()
    {
        AuthenticationTest::authProvider([Permissao::NOME_SISTEMA, Permissao::NOME_CADASTROPRODUTOS]);
        $valor_nutricional = ValorNutricionalTest::create();
        $id = $valor_nutricional->getID();
        $result = $this->patch('/api/valores_nutricionais/' . $id, $valor_nutricional->toArray());
        $valor_nutricional->loadByID();
        $expected = [
            'status' => 'ok',
            'item' => $valor_nutricional->publish(app()->auth->provider),
        ];
        $this->assertEquals($expected, \array_intersect_key($result, $expected));
    }

    public function testDelete()
    {
        AuthenticationTest::authProvider([Permissao::NOME_SISTEMA, Permissao::NOME_CADASTROPRODUTOS]);
        $valor_nutricional = ValorNutricionalTest::create();
        $id = $valor_nutricional->getID();
        $result = $this->delete('/api/valores_nutricionais/' . $id);
        $valor_nutricional->loadByID();
        $expected = [ 'status' => 'ok', ];
        $this->assertEquals($expected, \array_intersect_key($result, $expected));
        $this->assertFalse($valor_nutricional->exists());
    }
}