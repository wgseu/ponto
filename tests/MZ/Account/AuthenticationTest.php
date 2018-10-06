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

use MZ\Database\DB;
use MZ\System\Permissao;
use MZ\System\Sistema;

class AuthenticationTest extends \MZ\Framework\TestCase
{
    /**
     * @return \MZ\Provider\Prestador
     */
    public static function authProvider($permissions)
    {
        $funcao = \MZ\Provider\FuncaoTest::create($permissions);
        $prestador = \MZ\Provider\PrestadorTest::create($funcao);
        app()->getAuthentication()->login($prestador->findClienteID());
        return $prestador;
    }

    public function testClienteLogin()
    {
        app()->getAuthentication()->logout();
        $cliente = \MZ\Account\ClienteTest::create();
        app()->getAuthentication()->login($cliente);
        $this->assertTrue(app()->getAuthentication()->getUser()->exists());
    }

    public function testPrestadorLoginSemAcesso()
    {
        app()->getAuthentication()->logout();
        self::authProvider([]);
        $this->assertTrue(app()->getAuthentication()->getUser()->exists());
        $this->assertFalse(app()->getAuthentication()->getEmployee()->exists());
    }

    public function testPrestadorLogin()
    {
        app()->getAuthentication()->logout();
        self::authProvider([Permissao::NOME_SISTEMA]);
        $this->assertTrue(app()->getAuthentication()->getUser()->exists());
        $this->assertTrue(app()->getAuthentication()->getEmployee()->exists());
    }

    public function testAppStatus()
    {
        $expected = [
            'status' => 'ok',
            'info' => [
                'empresa' => [
                    'nome' => null,
                    'imagemurl' => null
                ],
            ],
            'versao' => Sistema::VERSAO,
            'validacao' => null,
            'autologout' => false,
            'acesso' => 'visitante',
            'permissoes' => []
        ];
        app()->getAuthentication()->logout();
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
}
