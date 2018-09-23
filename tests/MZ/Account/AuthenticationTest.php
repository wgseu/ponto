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
}
