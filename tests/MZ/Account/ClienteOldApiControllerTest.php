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

use MZ\System\Permissao;
use MZ\Account\AuthenticationTest;
use MZ\System\Sistema;

class ClienteOldApiControllerTest extends \MZ\Framework\TestCase
{
    public function testFind()
    {
        AuthenticationTest::authProvider([Permissao::NOME_SISTEMA, Permissao::NOME_CADASTROCLIENTES]);
        $cliente = ClienteTest::create('Unique Customer Name');
        $expected = [
            'status' => 'ok',
            'clientes' => [
                $cliente->publish(app()->auth->provider),
            ],
        ];
        $result = $this->get('/app/cliente/procurar', ['search' => $cliente->getNomeCompleto()]);
        $this->assertEquals($expected, \array_intersect_key($result, $expected));
    }

    public function testAdd()
    {
        AuthenticationTest::authProvider([Permissao::NOME_SISTEMA, Permissao::NOME_CADASTROCLIENTES]);
        $cliente = ClienteTest::build();
        $expected = [
            'status' => 'ok',
            'cliente' => $cliente->publish(app()->auth->provider),
        ];
        $result = $this->post('/app/cliente/', ['cliente' => $cliente->toArray()], true);
        $expected['cliente']['id'] = $result['cliente']['id'] ?? null;
        $expected['cliente']['dataatualizacao'] = $result['cliente']['dataatualizacao'] ?? null;
        $expected['cliente']['datacadastro'] = $result['cliente']['datacadastro'] ?? null;
        $this->assertEquals($expected, \array_intersect_key($result, $expected));
    }

    public function testAppStatus()
    {
        AuthenticationTest::authProvider([]);
        $expected = [
            'status' => 'ok',
            'info' => [
                'empresa' => [
                    'nome' => 'Aleatorio',
                    'imagemurl' => null
                ],
                'moeda' => app()->system->currency->publish(app()->auth->provider),
            ],
            'versao' => Sistema::VERSAO,
            'validacao' => '',
            'autologout' => false,
            'acesso' => 'visitante',
            'permissoes' => []
        ];
        app()->auth->logout();
        $result = $this->get('/app/conta/status');
        $this->assertEquals($expected, \array_intersect_key($result, $expected));
    }

    public function testAppEntrar()
    {
        app()->getAuthentication()->logout();
        $dispositivo = \MZ\Device\DispositivoTest::create();
        $prestador = \MZ\Provider\PrestadorTest::create();
        $cliente = $prestador->findClienteID();
        $permissoes = \MZ\System\Acesso::getPermissoes($prestador->getFuncaoID());
        $senha = 'zA491aZ';
        $cliente->setSenha($senha);
        $cliente->update();
        $data = [
            'usuario' => $cliente->getLogin(),
            'senha' => $senha,
            'device' => $dispositivo->getNome(),
            'serial' => $dispositivo->getSerial(),
        ];
        $result = $this->post('/app/conta/entrar', $data, true);
        $dispositivo->delete();
        $expected = [
            'status' => 'ok',
            'info' => [
                'usuario' => [
                    'nome' => $cliente->getNome(),
                    'email' => $cliente->getEmail(),
                    'login' => $cliente->getLogin(),
                    'imagemurl' => $cliente->makeImagemURL(false, null),
                ],
            ],
            'funcionario' => intval($prestador->getID()),
            'versao' => Sistema::VERSAO,
            'cliente' => $cliente->getID(),
            'autologout' => false,
            'acesso' => 'funcionario',
            'permissoes' => $permissoes,
        ];
        $this->assertEquals($expected, \array_intersect_key($result, $expected));
    }

    public function testLogout()
    {
        AuthenticationTest::authProvider([Permissao::NOME_SISTEMA, Permissao::NOME_CADASTROCLIENTES]);
        $this->assertTrue(app()->auth->isLogin());
        $result = $this->get('/app/conta/sair');
        $expected = [ 'status' => 'ok', ];
        $this->assertEquals($expected, \array_intersect_key($result, $expected));
        $this->assertFalse(app()->auth->isLogin());
    }
}
