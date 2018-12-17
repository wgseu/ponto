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

class SistemaPageControllerTest extends \MZ\Framework\TestCase
{
    public function testManage()
    {
        AuthenticationTest::authOwner();
        $result = $this->get('/gerenciar/');
        $this->assertEquals(200, $result->getStatusCode());
        AuthenticationTest::authProvider([Permissao::NOME_SISTEMA, Permissao::NOME_PAGAMENTO]);
        $result = $this->get('/gerenciar/');
        $this->assertEquals(200, $result->getStatusCode());
        AuthenticationTest::authProvider([Permissao::NOME_SISTEMA]);
        $result = $this->get('/gerenciar/');
        $this->assertEquals(200, $result->getStatusCode());
    }

    public function testDisplay()
    {
        AuthenticationTest::authProvider([Permissao::NOME_SISTEMA, Permissao::NOME_ALTERARCONFIGURACOES]);
        $result = $this->get('/gerenciar/sistema/');
        $this->assertEquals(200, $result->getStatusCode());
    }

    public function testAdvancedSettings()
    {
        AuthenticationTest::authProvider([Permissao::NOME_SISTEMA, Permissao::NOME_ALTERARCONFIGURACOES]);
        $result = $this->get('/gerenciar/sistema/avancado');
        $this->assertEquals(200, $result->getStatusCode());
        $data = ['mapskey' => 'key123', 'dropboxtoken' => 'token123'];
        $this->post('/gerenciar/sistema/avancado', $data, true);
        $this->assertEquals($data['mapskey'], get_string_config('Site', 'Maps.API'));
        $this->assertEquals($data['dropboxtoken'], get_string_config('Sistema', 'Dropbox.AccessKey'));
    }

    public function testMailSettings()
    {
        AuthenticationTest::authProvider([Permissao::NOME_SISTEMA, Permissao::NOME_ALTERARCONFIGURACOES]);
        $result = $this->get('/gerenciar/sistema/email');
        $this->assertEquals(200, $result->getStatusCode());
        $data = ['porta' => 465];
        $this->post('/gerenciar/sistema/email', $data, true);
        $this->assertEquals($data['porta'], get_int_config('Email', 'Porta'));
    }

    public function testInvoiceSettings()
    {
        AuthenticationTest::authProvider([Permissao::NOME_SISTEMA, Permissao::NOME_ALTERARCONFIGURACOES]);
        $result = $this->get('/gerenciar/sistema/fiscal');
        $this->assertEquals(200, $result->getStatusCode());
        $data = ['fiscal_timeout' => 25];
        $this->post('/gerenciar/sistema/fiscal', $data, true);
        $this->assertEquals($data['fiscal_timeout'], get_int_config('Sistema', 'Fiscal.Timeout'));
    }

    public function testPrintingSettings()
    {
        AuthenticationTest::authProvider([Permissao::NOME_SISTEMA, Permissao::NOME_ALTERARCONFIGURACOES]);
        $result = $this->get('/gerenciar/sistema/impressao');
        $this->assertEquals(200, $result->getStatusCode());
        $data = ['secao' => 'Cupom', 'chave' => 'Perguntar', 'marcado' => 'Y'];
        $this->post('/gerenciar/sistema/impressao', $data, true);
        $this->assertEquals($data['marcado'] == 'Y', is_boolean_config($data['secao'], $data['chave']));
        $data = ['secao' => 'Cupom', 'chave' => 'Perguntar', 'marcado' => 'N'];
        $this->post('/gerenciar/sistema/impressao', $data, true);
        $this->assertEquals($data['marcado'] == 'Y', is_boolean_config($data['secao'], $data['chave']));
    }

    public function testLayoutSettings()
    {
        AuthenticationTest::authProvider([Permissao::NOME_SISTEMA, Permissao::NOME_ALTERARCONFIGURACOES]);
        $result = $this->get('/gerenciar/sistema/layout');
        $this->assertEquals(200, $result->getStatusCode());
        $data = ['bemvindo' => 'Seja bem-vindo!'];
        $this->post('/gerenciar/sistema/layout', $data, true);
        $this->assertEquals($data['bemvindo'], get_string_config('Site', 'Text.BemVindo'));
    }

    public function testOptionsSettings()
    {
        AuthenticationTest::authProvider([Permissao::NOME_SISTEMA, Permissao::NOME_ALTERARCONFIGURACOES]);
        $result = $this->get('/gerenciar/sistema/opcoes');
        $this->assertEquals(200, $result->getStatusCode());
        $data = ['secao' => 'Sistema', 'chave' => 'Fiscal.Mostrar', 'marcado' => 'Y'];
        $this->post('/gerenciar/sistema/opcoes', $data, true);
        $this->assertEquals($data['marcado'] == 'Y', is_boolean_config($data['secao'], $data['chave']));
        $data = ['secao' => 'Sistema', 'chave' => 'Fiscal.Mostrar', 'marcado' => 'N'];
        $this->post('/gerenciar/sistema/opcoes', $data, true);
        $this->assertEquals($data['marcado'] == 'Y', is_boolean_config($data['secao'], $data['chave']));
    }
}
