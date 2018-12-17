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

use MZ\Database\DB;
use MZ\System\Permissao;
use MZ\Account\AuthenticationTest;

class ServicoTest extends \MZ\Framework\TestCase
{
    public static function build($descricao = null)
    {
        $last = Servico::find([], ['id' => -1]);
        $id = $last->getID() + 1;
        $servico = new Servico();
        $servico->setNome($descricao ?: "Serviço #{$id}");
        $servico->setDescricao($descricao ?: "Descrição do serviço #{$id}");
        $servico->setTipo(Servico::TIPO_TAXA);
        $servico->setObrigatorio('N');
        $servico->setValor(12.3);
        $servico->setIndividual('Y');
        $servico->setAtivo('Y');
        return $servico;
    }

    public static function create($descricao = null)
    {
        $servico = self::build($descricao);
        $servico->insert();
        return $servico;
    }

    public function testFind()
    {
        AuthenticationTest::authProvider([Permissao::NOME_SISTEMA, Permissao::NOME_CADASTROSERVICOS]);
        $servico = self::create('Serviço find');
        $servico_sec = self::create('Serviço find second');
        $expected = [
            'status' => 'ok',
            'items' => [
                $servico->publish(app()->auth->provider),
                $servico_sec->publish(app()->auth->provider)
            ],
            'pages' => 1
        ];
        $result = $this->get('/api/servicos', ['search' => $servico->getNome()]);
        $this->assertEquals($expected, $result);
    }

    public function testAdd()
    {
        AuthenticationTest::authProvider([Permissao::NOME_SISTEMA, Permissao::NOME_CADASTROSERVICOS]);
        $servico = self::build();
        $expected = [
            'status' => 'ok',
            'item' => $servico->publish(app()->auth->provider),
        ];
        $result = $this->post('/api/servicos', $servico->toArray());
        $expected['item']['id'] = $result['item']['id'] ?? null;
        $this->assertEquals($expected, $result);
    }

    public function testUpdate()
    {
        AuthenticationTest::authProvider([Permissao::NOME_SISTEMA, Permissao::NOME_CADASTROSERVICOS]);
        $servico = self::create();
        $id = $servico->getID();
        $result = $this->patch('/api/servicos/' . $id, $servico->toArray());
        $servico->loadByID();
        $expected = [
            'status' => 'ok',
            'item' => $servico->publish(app()->auth->provider),
        ];
        $this->assertEquals($expected, \array_intersect_key($result, $expected));
    }

    public function testDelete()
    {
        AuthenticationTest::authProvider([Permissao::NOME_SISTEMA, Permissao::NOME_CADASTROSERVICOS]);
        $servico = self::create();
        $id = $servico->getID();
        $result = $this->delete('/api/servicos/' . $id);
        $servico->loadByID();
        $expected = [ 'status' => 'ok', ];
        $this->assertEquals($expected, \array_intersect_key($result, $expected));
        $this->assertFalse($servico->exists());
    }
}
