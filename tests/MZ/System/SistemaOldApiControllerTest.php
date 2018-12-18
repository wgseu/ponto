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
namespace MZ\System;

use MZ\System\Permissao;
use MZ\Account\AuthenticationTest;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use MZ\Integrator\IFood;
use MZ\Integrator\Kromax;

class SistemaOldApiControllerTest extends \MZ\Framework\TestCase
{
    public function testBackup()
    {
        AuthenticationTest::authProvider([Permissao::NOME_SISTEMA, Permissao::NOME_BACKUP]);
        $reponse = $this->get('/gerenciar/sistema/backup');
        $this->assertEquals('application/zip', $reponse->headers->get('Content-Type'));
    }

    public function testRestore()
    {
        AuthenticationTest::authProvider([Permissao::NOME_SISTEMA, Permissao::NOME_RESTAURACAO]);
        $file = new UploadedFile(
            dirname(dirname(__DIR__)) . '/resources/backup.zip',
            'backup.zip',
            null,
            null,
            true
        );
        $files = ['zipfile' => $file];
        $result = $this->post('/gerenciar/sistema/restore', [], true, $files);
        $this->assertEquals(['status' => 'ok'], $result);
    }

    public function testTasks()
    {
        AuthenticationTest::authProvider([Permissao::NOME_SISTEMA, Permissao::NOME_ENTREGAPEDIDOS]);
        $integracao = Integracao::findByAcessoURL(IFood::NAME);
        $integracao->setAtivo('Y');
        $integracao->update();
        $result = $this->get('/gerenciar/sistema/tarefa');
        $expected = ['status' => 'ok'];
        $this->assertEquals($expected, \array_intersect_key($result, $expected));
        $integracao->setAtivo('N');
        $integracao->update();
    }

    public function testUpgrade()
    {
        AuthenticationTest::authProvider([Permissao::NOME_SISTEMA, Permissao::NOME_ALTERARCONFIGURACOES]);
        $result = $this->get('/app/sistema/upgrade');
        $expected = ['status' => 'ok'];
        $this->assertEquals($expected, \array_intersect_key($result, $expected));
    }
}
