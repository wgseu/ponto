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
namespace MZ\Company;

use MZ\System\Permissao;
use MZ\Account\AuthenticationTest;

class HorarioApiControllerTest extends \MZ\Framework\TestCase
{
    public function testFind()
    {
        $horario = HorarioTest::create();
        AuthenticationTest::authProvider([Permissao::NOME_SISTEMA, Permissao::NOME_ALTERARHORARIO]);
        $expected = [
            'status' => 'ok',
            'items' => [
                $horario->publish(app()->auth->provider),
            ],
        ];
        $result = $this->get('/api/horarios', ['search' => $horario->getMensagem()]);
        $this->assertEquals($expected, \array_intersect_key($result, $expected));
    }

    public function testAdd()
    {
        $horario = HorarioTest::build();
        AuthenticationTest::authProvider([Permissao::NOME_SISTEMA, Permissao::NOME_ALTERARHORARIO]);
        $expected = [
            'status' => 'ok',
            'item' => $horario->publish(app()->auth->provider),
        ];
        $result = $this->post('/api/horarios', $horario->toArray());
        $expected['item']['id'] = $result['item']['id'] ?? null;
        $this->assertEquals($expected, \array_intersect_key($result, $expected));
    }

    public function testUpdate()
    {
        $horario = HorarioTest::create();
        AuthenticationTest::authProvider([Permissao::NOME_SISTEMA, Permissao::NOME_ALTERARHORARIO]);
        $id = $horario->getID();
        $result = $this->patch('/api/horarios/' . $id, $horario->toArray());
        $horario->loadByID();
        $expected = [
            'status' => 'ok',
            'item' => $horario->publish(app()->auth->provider),
        ];
        $this->assertEquals($expected, \array_intersect_key($result, $expected));
    }

    public function testDelete()
    {
        $horario = HorarioTest::create();
        AuthenticationTest::authProvider([Permissao::NOME_SISTEMA, Permissao::NOME_ALTERARHORARIO]);
        $id = $horario->getID();
        $result = $this->delete('/api/horarios/' . $id);
        $horario->loadByID();
        $expected = [ 'status' => 'ok', ];
        $this->assertEquals($expected, \array_intersect_key($result, $expected));
        $this->assertFalse($horario->exists());
    }
}
