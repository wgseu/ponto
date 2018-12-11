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

use MZ\Location\EstadoTest;

class CidadeTest extends \MZ\Framework\TestCase
{
    /**
     * Build a valid cidade
     * @param string $nome Cidade nome
     * @return Cidade
     */
    public static function build($nome = null)
    {
        $last = Cidade::find([], ['id' => -1]);
        $id = $last->getID() + 1;
        $estado = EstadoTest::create();
        $cidade = new Cidade();
        $cidade->setEstadoID($estado->getID());
        $cidade->setNome($nome ?: "Cidade {$id}");
        return $cidade;
    }

    /**
     * Create a cidade on database
     * @param string $nome Cidade nome
     * @return Cidade
     */
    public static function create($nome = null)
    {
        $cidade = self::build($nome);
        $cidade->insert();
        return $cidade;
    }

    public function testFind()
    {
        $cidade = self::create();
        $condition = ['nome' => $cidade->getNome()];
        $found_cidade = Cidade::find($condition);
        $this->assertEquals($cidade, $found_cidade);
        list($found_cidade) = Cidade::findAll($condition, [], 1);
        $this->assertEquals($cidade, $found_cidade);
        $this->assertEquals(1, Cidade::count($condition));
    }

    public function testAdd()
    {
        $cidade = self::build();
        $cidade->insert();
        $this->assertTrue($cidade->exists());
    }

    public function testUpdate()
    {
        $cidade = self::create();
        $cidade->update();
        $this->assertTrue($cidade->exists());
    }

    public function testDelete()
    {
        $cidade = self::create();
        $cidade->delete();
        $cidade->loadByID();
        $this->assertFalse($cidade->exists());
    }
}
