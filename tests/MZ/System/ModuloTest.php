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
use MZ\Exception\ValidationException;

class ModuloTest extends \MZ\Framework\TestCase
{
    public function testFind()
    {
        $modulo = Modulo::find([]);
        $condition = ['nome' => $modulo->getNome()];
        $found_modulo = Modulo::find($condition);
        $this->assertEquals($modulo, $found_modulo);
        list($found_modulo) = Modulo::findAll($condition, [], 1);
        $this->assertEquals($modulo, $found_modulo);
        $this->assertEquals(1, Modulo::count($condition));
    }

    public function testUpdate()
    {
        $modulo = Modulo::find([]);
        $modulo->update();
        $this->assertTrue($modulo->exists());
    }

    public function testIsHabilitado()
    {
        $modulo = Modulo::find([]);
        $result = $modulo->isHabilitado();
        $this->assertTrue($result);
    }

    public function testValidate()
    {
        //nome não pode ser nulo
        $modulo = Modulo::find([]);
        $modulo->setNome(null);
        try {
            $modulo->update();
            $this->fail('nome não pode ser nulo');
        } catch (ValidationException $e) {
            $this->assertEquals(['nome'], array_keys($e->getErrors()));
        }
        //descricão não pode ser nula
        $modulo = Modulo::find([]);
        $modulo->setDescricao(null);
        try {
            $modulo->update();
            $this->fail('descrição não pode ser nula');
        } catch (ValidationException $e) {
            $this->assertEquals(['descricao'], array_keys($e->getErrors()));
        }
        //Valor de habilitado não é válido
        $modulo = Modulo::find([]);
        $modulo->setHabilitado(null);
        try {
            $modulo->update();
            $this->fail('descrição não pode ser nula');
        } catch (ValidationException $e) {
            $this->assertEquals(['habilitado'], array_keys($e->getErrors()));
        }
    }

    public function testTranslate()
    {
        $moduloInit = Modulo::find([]);
        $modulo = new Modulo();
        $modulo->setID(2);
        $modulo->setNome($moduloInit->getNome());
        $modulo->setDescricao('teste descrição');
        try {
            $modulo->update();
            $this->fail('Nome do modulo não pode ser o mesmo');
        } catch (ValidationException $e) {
            $this->assertEquals(['nome'], array_keys($e->getErrors()));
        }
    }

    public function testInsert()
    {
        $modulo = new Modulo();
        $modulo->setNome('teste nome');
        $modulo->setDescricao('teste descrição');
        $this->expectException('\Exception');
        $modulo->insert();
    }

    public function testDelete()
    {
        $modulo = Modulo::find([]);
        $this->expectException('\Exception');
        $modulo->delete();
    }

    public function testeFindByNome()
    {
        $modulo = Modulo::find([]);
        $found_modulo = Modulo::findByNome($modulo->getNome());
        $this->assertEquals($modulo->getNome(), $found_modulo->getNome());
    }
}
