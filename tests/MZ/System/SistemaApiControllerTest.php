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

class SistemaApiControllerTest extends \MZ\Framework\TestCase
{
    public function testFind()
    {
        AuthenticationTest::authProvider([Permissao::NOME_SISTEMA, Permissao::NOME_ALTERARCONFIGURACOES]);
        $sistema = Sistema::find(['id' => '1']);
        $expected = [
            'status' => 'ok',
            'items' => [
                $sistema->publish(app()->auth->provider),
            ],
        ];
        $result = $this->get('/api/sistemas', ['search' => $sistema->getVersaoDB()]);
        $this->assertEquals($expected, \array_intersect_key($result, $expected));
    }

    public function testUpdate()
    {
        AuthenticationTest::authProvider([Permissao::NOME_SISTEMA, Permissao::NOME_ALTERARCONFIGURACOES]);
        $sistema = Sistema::find(['id' => '1']);
        $id = $sistema->getID();
        $result = $this->patch('/api/sistemas/' . $id, $sistema->toArray());
        $sistema->loadByID();
        $expected = [
            'status' => 'ok',
            'item' => $sistema->publish(app()->auth->provider),
        ];
        $this->assertEquals($expected, \array_intersect_key($result, $expected));
    }

    public function testAdvanced()
    {
       AuthenticationTest::authProvider([Permissao::NOME_SISTEMA, Permissao::NOME_ALTERARCONFIGURACOES]);
       $sistema = Sistema::find(['id' => 1]);
       $result = $this->post('/api/sistemas/advanced', ['mapskey' => '1111', 'dropboxtoken' => '3EGB6']);
       $expected = [
           'status' => 'ok'
       ];
       $this->assertEquals($expected, \array_intersect_key($result, $expected));
    }

    public function testAdvancedException()
    {
        AuthenticationTest::authProvider([Permissao::NOME_ALTERARCONFIGURACOES]);
        $sistema = Sistema::find(['id' => 1]);
        $result = $this->post('/api/sistemas/advanced', ['mapskey' => null, 'dropboxtoken' => '3EGB6']);
        $expected = [
            'status' => 'error'
        ];
        $this->assertEquals($expected, \array_intersect_key($result, $expected));

    }

    public function testMail()
    {
        AuthenticationTest::authProvider([Permissao::NOME_SISTEMA, Permissao::NOME_ALTERARCONFIGURACOES]);
        $result = $this->post('/api/sistemas/mail', 
        [
            'porta' => 365, 
            'destinatario' => 'teste@grandchef.com',
            'servidor' => 'grandchef',
            'encriptacao' => 'MD5',
            'usuario' => 'admin',
            'senha' => '123456'
        ]);
        $expected = [
            'status' => 'ok'
        ];
        $this->assertEquals($expected, \array_intersect_key($result, $expected));
    }

    public function testMailExpection()
    {
        AuthenticationTest::authProvider([Permissao::NOME_SISTEMA, Permissao::NOME_ALTERARCONFIGURACOES]);
        $result = $this->post('/api/sistemas/mail', 
        [
            'porta' => 65536, 
            'destinatario' => 'teste@grandchef.com',
            'servidor' => 'grandchef'    
        ]);
        $expected = [
            'status' => 'error'
        ];
        $this->assertEquals($expected, \array_intersect_key($result, $expected));
    }

    public function testInvoice()
    {
        AuthenticationTest::authProvider([Permissao::NOME_SISTEMA, Permissao::NOME_ALTERARCONFIGURACOES]);
        $sistema = Sistema::find(['id' => 1]);
        $result = $this->post('/api/sistemas/invoice', ['sistema' => $sistema->toArray(), 'fiscal_timeout' => 40]);
        $expected = [
            'status' => 'ok'
        ];
        $this->assertEquals($expected, \array_intersect_key($result, $expected));
    }

    public function testPrinting()
    {
        AuthenticationTest::authProvider([Permissao::NOME_SISTEMA, Permissao::NOME_ALTERARCONFIGURACOES]);
        $result = $this->post('/api/sistemas/printing', ['chave' => 'Garcom', 'secao' => 'Imprimir']);
        $expected = [
            'status' => 'ok'
        ];
        $this->assertEquals($expected, \array_intersect_key($result, $expected));
    }

    public function testOptions()
    {
        AuthenticationTest::authProvider([Permissao::NOME_SISTEMA, Permissao::NOME_ALTERARCONFIGURACOES]);
        $result = $this->post('/api/sistemas/options', ['chave' => 'Tela.Cheia', 'secao' => 'Vendas', 'marcado' => 'Y']);
        $expected = [
            'status' => 'ok'
        ];
        $this->assertEquals($expected, \array_intersect_key($result, $expected));
    }
}
