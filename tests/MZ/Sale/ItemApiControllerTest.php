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
namespace MZ\Sale;

use MZ\System\Permissao;
use MZ\Account\AuthenticationTest;
use MZ\Device\DispositivoTest;
use MZ\Device\ImpressoraTest;
use MZ\Device\Dispositivo;

class ItemApiControllerTest extends \MZ\Framework\TestCase
{
    public function testJobs()
    {
        AuthenticationTest::authProvider([Permissao::NOME_SISTEMA, Permissao::NOME_PEDIDOMESA]);
        $item = ItemTest::create();
        $dispositivo = Dispositivo::find([]);
        if (!$dispositivo->exists()) {
            $dispositivo = DispositivoTest::create();
        }
        $impressora = ImpressoraTest::create();
        $data = [
            'device' => $dispositivo->getNome(),
            'serial' => $dispositivo->getSerial(),
        ];
        $expected = [
            'status' => 'ok',
        ];
        $this->assertEquals(Item::ESTADO_ADICIONADO, $item->getEstado());
        $result = $this->patch('/api/itens/jobs/' . $item->getPedidoID(), $data);
        $item->loadByID();
        $this->assertEquals(Item::ESTADO_ENVIADO, $item->getEstado());
        $this->assertEquals($expected, \array_intersect_key($result, $expected));
    }

    public function testUncheck()
    {
        AuthenticationTest::authProvider([Permissao::NOME_SISTEMA, Permissao::NOME_PEDIDOMESA]);
        $item = ItemTest::create();
        $dispositivo = Dispositivo::find([]);
        if (!$dispositivo->exists()) {
            $dispositivo = DispositivoTest::create();
        }
        $impressora = ImpressoraTest::create();
        $data = [
            'device' => $dispositivo->getNome(),
            'serial' => $dispositivo->getSerial(),
        ];
        $this->patch('/api/itens/jobs/' . $item->getPedidoID(), $data);
        $item->loadByID();
        $this->assertEquals(Item::ESTADO_ENVIADO, $item->getEstado());
        $data = ['affected' => [$item->getID()]];
        $this->patch('/api/itens/uncheck', $data);
        $item->loadByID();
        $this->assertEquals(Item::ESTADO_ADICIONADO, $item->getEstado());
    }
}
