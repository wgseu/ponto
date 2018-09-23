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

use MZ\System\Servidor;
use MZ\Database\DB;

class SistemaTest extends \MZ\Framework\TestCase
{
    public function testFromArray()
    {
        $old_sistema = new Sistema([
            'id' => 'Sistema',
            'servidorid' => 123,
            'licenca' => 'Sistema',
            'dispositivos' => 123,
            'guid' => 'Sistema',
            'ultimobackup' => '2016-12-25 12:15:00',
            'fusohorario' => 'Sistema',
            'versaodb' => 'Sistema',
        ]);
        $sistema = new Sistema();
        $sistema->fromArray($old_sistema);
        $this->assertEquals($sistema, $old_sistema);
        $sistema->fromArray(null);
        $this->assertEquals($sistema, new Sistema());
    }

    public function testFilter()
    {
        $old_sistema = new Sistema([
            'id' => 'Sistema filter',
            'servidorid' => 1234,
            'licenca' => ' Sistema <script>filter</script> ',
            'dispositivos' => 1234,
            'guid' => 'Sistema filter',
            'ultimobackup' => '2016-12-23 12:15:00',
            'fusohorario' => 'Sistema filter',
            'versaodb' => 'Sistema filter',
        ]);
        $sistema = new Sistema([
            'id' => 321,
            'servidorid' => '1.234',
            'licenca' => ' Sistema <script>filter</script> ',
            'dispositivos' => '1.234',
            'guid' => ' Sistema <script>filter</script> ',
            'ultimobackup' => '23/12/2016 12:15',
            'fusohorario' => ' Sistema <script>filter</script> ',
            'versaodb' => ' Sistema <script>filter</script> ',
        ]);
        $sistema->filter($old_sistema, true);
        $this->assertEquals($old_sistema, $sistema);
    }

    public function testPublish()
    {
        $sistema = new Sistema();
        $values = $sistema->publish();
        $allowed = [
            'id',
            'servidorid',
            'dispositivos',
            'ultimobackup',
            'fusohorario',
            'versaodb',
        ];
        $this->assertEquals($allowed, array_keys($values));
    }

    public function testUpdate()
    {
        $servidor = Servidor::find([]);
        $sistema = Sistema::findByID('1');
        $sistema->setServidorID($servidor->getID());
        $sistema->update();
        $found_sistema = Sistema::findByID($sistema->getID());
        $this->assertEquals($sistema, $found_sistema);
        $sistema->setID('');
        $this->expectException('\Exception');
        $sistema->update();
    }

    public function testFind()
    {
        $sistema = Sistema::findByID('1');
        $found_sistema = Sistema::find(['id' => $sistema->getID()]);
        $this->assertEquals($sistema, $found_sistema);
        $count = Sistema::count();
        $this->assertEquals(1, $count);
    }
}
