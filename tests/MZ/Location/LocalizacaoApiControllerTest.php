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
namespace MZ\Location;

use MZ\System\Permissao;
use MZ\Account\AuthenticationTest;
use MZ\Location\CidadeTest;
use MZ\Location\Localizacao;

class LocalizacaoApiControllerTest extends \MZ\Framework\TestCase
{
    public function testFind()
    {
        AuthenticationTest::authProvider([Permissao::NOME_SISTEMA, Permissao::NOME_CADASTROCLIENTES]);
        $localizacao = LocalizacaoTest::create();
        $expected = [
            'status' => 'ok',
            'items' => [
                $localizacao->publish(app()->auth->provider),
            ],
        ];
        $result = $this->get('/api/localizacoes', ['search' => $localizacao->getLogradouro()]);
        $this->assertEquals($expected, \array_intersect_key($result, $expected));
        //-------
        $localizacao = LocalizacaoTest::build();
        $localizacao->setTipo(Localizacao::TIPO_APARTAMENTO);
        $localizacao->setCondominio('Testinho');
        $localizacao->insert();
        $expected = [
                    'status' => 'ok',
                ];
        $result = $this->get('/api/localizacoes', ['typesearch' => $localizacao->getTipo(), 'tipo' => $localizacao->getTipo()]);
        $this->assertEquals($expected, \array_intersect_key($result, $expected));
    }

    public function testAdd()
    {
        AuthenticationTest::authProvider([Permissao::NOME_SISTEMA, Permissao::NOME_CADASTROCLIENTES]);
        $cidade = CidadeTest::create();
        $localizacao = LocalizacaoTest::build();
        $localizacao->setMostrar('Y');
        $this->assertTrue($localizacao->isMostrar());
        $this->assertEquals($localizacao->toArray(), (new Localizacao($localizacao))->toArray());
        $this->assertEquals((new Localizacao())->toArray(), (new Localizacao(1))->toArray());
        $bairro = $localizacao->findBairroID();
        $cidade = $bairro->findCidadeID();
        $estado = $cidade->findEstadoID();
        $data = $localizacao->toArray();
        $data['bairro'] = $bairro->getNome();
        $data['cidade'] = $cidade->getNome();
        $data['estadoid'] = $estado->getID();
        $expected = [
            'status' => 'ok',
            'item' => $localizacao->publish(app()->auth->provider),
        ];
        $result = $this->post('/api/localizacoes', $data);
        $expected['item']['id'] = $result['item']['id'] ?? null;
        $expected['item']['latitude'] = $result['item']['latitude'] ?? null;
        $expected['item']['longitude'] = $result['item']['longitude'] ?? null;
        $this->assertEquals($expected, \array_intersect_key($result, $expected));
    }

    public function testUpdate()
    {
        AuthenticationTest::authProvider([Permissao::NOME_SISTEMA, Permissao::NOME_CADASTROCLIENTES]);
        $localizacao = LocalizacaoTest::create();
        $id = $localizacao->getID();
        $bairro = $localizacao->findBairroID();
        $cidade = $bairro->findCidadeID();
        $estado = $cidade->findEstadoID();
        $data = $localizacao->toArray();
        $data['bairro'] = $bairro->getNome();
        $data['cidade'] = $cidade->getNome();
        $data['estadoid'] = $estado->getID();
        $result = $this->patch('/api/localizacoes/' . $id, $data);
        $localizacao->loadByID();
        $expected = [
            'status' => 'ok',
            'item' => $localizacao->publish(app()->auth->provider),
        ];
        $this->assertEquals($expected, \array_intersect_key($result, $expected));
    }

    public function testDelete()
    {
        AuthenticationTest::authProvider([Permissao::NOME_SISTEMA, Permissao::NOME_CADASTROCLIENTES]);
        $localizacao = LocalizacaoTest::create();
        $id = $localizacao->getID();
        $result = $this->delete('/api/localizacoes/' . $id);
        $localizacao->loadByID();
        $expected = [ 'status' => 'ok', ];
        $this->assertEquals($expected, \array_intersect_key($result, $expected));
        $this->assertFalse($localizacao->exists());
    }
}
