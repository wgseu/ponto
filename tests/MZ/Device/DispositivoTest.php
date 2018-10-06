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
namespace MZ\Device;

use MZ\Environment\SetorTest;

class DispositivoTest extends \MZ\Framework\TestCase
{
    /**
     * Build a valid dispositivo
     * @param string $nome Dispositivo nome
     * @return Dispositivo
     */
    public static function build($nome = null)
    {
        $last = Dispositivo::find([], ['id' => -1]);
        $id = $last->getID() + 1;
        $setor = SetorTest::create();
        $dispositivo = new Dispositivo();
        $dispositivo->setSetorID($setor->getID());
        $dispositivo->setNome($nome ?: "Tablet {$id}");
        $dispositivo->setDescricao($nome ?: "Tablet {$id}");
        $dispositivo->setTipo(Dispositivo::TIPO_TABLET);
        $dispositivo->setSerial("{$id}8sdd7qw549{$id}");
        return $dispositivo;
    }

    /**
     * Create a dispositivo on database
     * @param string $nome Dispositivo nome
     * @return Dispositivo
     */
    public static function create($nome = null)
    {
        $dispositivo = self::build($nome);
        $dispositivo->insert();
        $dispositivo->authorize();
        return $dispositivo;
    }

    public function testInsert()
    {
        $dispositivo = self::create();
        $this->assertTrue($dispositivo->exists());
    }
}
