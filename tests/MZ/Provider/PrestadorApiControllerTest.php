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
namespace MZ\Provider;

use MZ\System\Permissao;
use MZ\Account\AuthenticationTest;
use MZ\Account\Authentication;
use MZ\Account\ClienteTest;

class PrestadorApiControllerTest extends \MZ\Framework\TestCase
{
    public function testFind()
    {
        AuthenticationTest::authProvider([Permissao::NOME_SISTEMA, Permissao::NOME_CADASTROPRESTADORES]);
        $cliente = ClienteTest::build();
        $cliente->setCPF('09066112964');
        $cliente->insert();
        $prestador = PrestadorTest::build();
        $prestador->setClienteID($cliente->getID());
        $prestador->insert();
        $expected = [
            'status' => 'ok',
            'items' => [
                $prestador->publish(app()->auth->provider),
            ],
        ];
        $result = $this->get('/api/prestadores', ['search' => $cliente->getEmail()]);
        $this->assertEquals($expected, \array_intersect_key($result, $expected));
        //--------
        $result = $this->get('/api/prestadores', ['search' => $prestador->getID()]);
        $this->assertEquals($expected, \array_intersect_key($result, $expected));
        //-------Manda cpf
        $result = $this->get('/api/prestadores', ['search' => '09066112964']);
        $this->assertEquals($expected, \array_intersect_key($result, $expected));
        //-------Manda telefone
        $result = $this->get('/api/prestadores', ['search' => '46999014146']);
        //--------------
        $result = $this->get('/api/prestadores', ['search' => 'aleatorio']);
    }

    public function testAdd()
    {
        AuthenticationTest::authProvider([Permissao::NOME_SISTEMA, Permissao::NOME_CADASTROPRESTADORES]);
        $prestador = PrestadorTest::build();
        $this->assertEquals($prestador->toArray(), (new Prestador($prestador))->toArray());
        $this->assertEquals((new Prestador())->toArray(), (new Prestador(1))->toArray());
        $expected = [
            'status' => 'ok',
            'item' => $prestador->publish(app()->auth->provider),
        ];
        $result = $this->post('/api/prestadores', $prestador->toArray());
        $expected['item']['id'] = $result['item']['id'] ?? null;
        $this->assertEquals($expected, \array_intersect_key($result, $expected));
    }

    public function testUpdate()
    {
        AuthenticationTest::authProvider([Permissao::NOME_SISTEMA, Permissao::NOME_CADASTROPRESTADORES]);
        $prestador = PrestadorTest::create();
        $id = $prestador->getID();
        $result = $this->patch('/api/prestadores/' . $id, $prestador->toArray());
        $prestador->loadByID();
        $expected = [
            'status' => 'ok',
            'item' => $prestador->publish(app()->auth->provider),
        ];
        $this->assertEquals($expected, \array_intersect_key($result, $expected));
    }

    public function testDelete()
    {
        AuthenticationTest::authOwner();
        $prestador = PrestadorTest::create();
        $id = $prestador->getID();
        $result = $this->delete('/api/prestadores/' . $id);
        $prestador->loadByID();
        $expected = [ 'status' => 'ok', ];
        $this->assertEquals($expected, \array_intersect_key($result, $expected));
        $this->assertFalse($prestador->exists());
    }
}
