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
use MZ\Exception\ValidationException;
use MZ\System\Permissao;
use MZ\Account\AuthenticationTest;

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

    public function testFinds()
    {
        $cidade = self::create();

        $estado = $cidade->findEstadoID();
        $this->assertEquals($cidade->getEstadoID(), $estado->getID());

        $cidByEstadoIDNome = $cidade->findByEstadoIDNome($estado->getID(), $cidade->getNome());
        $this->assertInstanceOf(get_class($cidade), $cidByEstadoIDNome);

        $cidByCEP = $cidade->findByCEP($cidade->getCEP());
        $this->assertInstanceOf(get_class($cidade), $cidByCEP);
    }

    public function testFindOrInsert()
    {
        $cidade = self::create();

        $cidadeFound = Cidade::findOrInsert($cidade->getEstadoID(), $cidade->getNome());
        $this->assertInstanceOf(get_class($cidade), $cidadeFound);

        $cidade2 = self::build();
        AuthenticationTest::authProvider([Permissao::NOME_SISTEMA, Permissao::NOME_CADASTROCIDADES]);
        $cidadeFound2 = Cidade::findOrInsert($cidade2->getEstadoID(), $cidade2->getNome());
        $this->assertInstanceOf(get_class($cidade2), $cidadeFound2);

        try {
            $cidade3 = self::build();
            $cidade3->setNome(null);
            AuthenticationTest::authProvider([Permissao::NOME_SISTEMA, Permissao::NOME_CADASTROCIDADES]);
            $cidadeFound3 = Cidade::findOrInsert($cidade3->getEstadoID(), $cidade3->getNome());
            $this->fail('Não cadastrar');
        } catch (ValidationException $e) {
            $this->assertEquals(['cidade'], array_keys($e->getErrors()));
        }
    }


    public function testAdd()
    {
        $cidade = self::build();
        $cidade->insert();
        $this->assertTrue($cidade->exists());
    }

    public function testAddInvalid()
    {
        $cidade = self::build();
        $cidade->setEstadoID(null);
        $cidade->setNome(null);
        $cidade->setCEP('kkk65777645');
        try {
            $cidade->insert();
            $this->fail('Não cadastrar com valores nulos');
        } catch (ValidationException $e) {
            $this->assertEquals(['estadoid', 'nome', 'cep'], array_keys($e->getErrors()));
        }
    }

    public function testTranslate()
    {
        $cidade = self::build();
        $cidade->setNome('Test');
        $cidade->setEstadoID(15);
        $cidade->insert();
        try {
            $cidade->insert();
            $this->fail('Não cadastrar fk duplicada');
        } catch (ValidationException $e) {
            $this->assertEquals(['estadoid', 'nome'], array_keys($e->getErrors()));
        }
        //--------------------
        $cidade = self::build();
        $cidade->setCEP('87710000');
        $cidade->insert();
        try {
            $cidade->insert();
            $this->fail('fk duplicada');
        } catch (ValidationException $e) {
            $this->assertEquals(['cep'], array_keys($e->getErrors()));
        }
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
