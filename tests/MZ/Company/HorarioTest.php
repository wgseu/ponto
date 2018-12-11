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

class HorarioTest extends \MZ\Framework\TestCase
{
    /**
     * Build a valid horário
     * @param string $mensagem Horário mensagem
     * @return Horario
     */
    public static function build($mensagem = null)
    {
        $last = Horario::find([], ['id' => -1]);
        $id = $last->getID() + 1;
        $horario = new Horario();
        $horario->setInicio(10000 + $id);
        $horario->setFim(20000 + $id);
        return $horario;
    }

    /**
     * Create a horário on database
     * @param string $mensagem Horário mensagem
     * @return Horario
     */
    public static function create($mensagem = null)
    {
        $horario = self::build($mensagem);
        $horario->insert();
        return $horario;
    }

    public function testFind()
    {
        $horario = self::create();
        $condition = ['inicio' => $horario->getInicio()];
        $found_horario = Horario::find($condition);
        $this->assertEquals($horario, $found_horario);
        list($found_horario) = Horario::findAll($condition, [], 1);
        $this->assertEquals($horario, $found_horario);
        $this->assertEquals(1, Horario::count($condition));
    }

    public function testAdd()
    {
        $horario = self::build();
        $horario->insert();
        $this->assertTrue($horario->exists());
    }

    public function testUpdate()
    {
        $horario = self::create();
        $horario->update();
        $this->assertTrue($horario->exists());
    }

    public function testDelete()
    {
        $horario = self::create();
        $horario->delete();
        $horario->loadByID();
        $this->assertFalse($horario->exists());
    }
}
