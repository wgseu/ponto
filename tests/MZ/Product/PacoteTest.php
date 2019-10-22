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

use MZ\Product\ProdutoTest;
use MZ\Product\GrupoTest;
use MZ\Product\PropriedadeTest;
use MZ\Exception\ValidationException;

class PacoteTest extends \MZ\Framework\TestCase
{
    /**
     * Build a valid pacote
     * @return Pacote
     */
    public static function build()
    {
        $last = Pacote::find([], ['id' => -1]);
        $id = $last->getID() + 1;
        $produto = ProdutoTest::create();
        $grupo = GrupoTest::create();
        $pacote = new Pacote();
        $pacote->setPacoteID($grupo->getProdutoID());
        $pacote->setProdutoID($produto->getID());
        $pacote->setGrupoID($grupo->getID());
        $pacote->setValor(12.3);
        return $pacote;
    }

    /**
     * Create a pacote on database
     * @return Pacote
     */
    public static function create()
    {
        $pacote = self::build();
        $pacote->insert();
        return $pacote;
    }

    public function testFind()
    {
        $pacote = self::create();
        $condition = ['grupoid' => $pacote->getGrupoID(), 'produtoid' => $pacote->getProdutoID()];
        $found_pacote = Pacote::find($condition);
        $this->assertEquals($pacote, $found_pacote);
        list($found_pacote) = Pacote::findAll($condition, [], 1);
        $this->assertEquals($pacote, $found_pacote);
        $this->assertEquals(1, Pacote::count($condition));
    }

    public function testFindProp()
    {
        $pacote = self::create();
        $prop = $pacote->findPropriedadeID();
        $this->assertEquals($pacote->getPropriedadeID(), $prop->getID());
    }

    public function testAdd()
    {
        $pacote = self::build();
        $pacote->insert();
        $this->assertTrue($pacote->exists());
        $pacote->delete();
    }

    public function testAddInvalid()
    {
        $pacote = self::build();

        $pacote->setQuantidadeMaxima(null);
        $pacote->setQuantidadeMinima(null);
        $pacote->setSelecionado(null);
        $pacote->setVisivel(null);
        try {
            $pacote->insert();
            $this->fail('Não cadastrar valores nulos');
        } catch (ValidationException $e) {
            $this->assertEquals(['quantidademinima', 'quantidademaxima', 'selecionado', 'visivel'], array_keys($e->getErrors()));
        }
        //--------------------------------
        $pacote = self::build();
        $pacote->setProdutoID(1);
        $pacote->setPropriedadeID(2);
        try {
            $pacote->insert();
            $this->fail('Não cadastrar com produto ID e propriedade ID');
        } catch (ValidationException $e) {
            $this->assertEquals(['produtoid'], array_keys($e->getErrors()));
        }
        //---------cadastrar 2 produtos (pacote.produto_id_existing)
        $produto = ProdutoTest::build();
        $produto->setDescricao('Queijo');
        $produto->insert();
        $pacote = self::build();
        $pacote->setProdutoID($produto->getID());
        $pacote->insert();
        try {
            $pacote->insert();
            $this->fail('o produto informado já está cadastrado');
        } catch (ValidationException $e) {
            $this->assertEquals(['produtoid'], array_keys($e->getErrors()));
        }
        //---------pacote.produto_id_package
        $produto = ProdutoTest::build();
        $produto->setTipo(Produto::TIPO_PACOTE);
        $produto->insert();
        $pacote = self::build();
        $pacote->setProdutoID($produto->getID());
        try {
            $pacote->insert();
            $this->fail('O produto do pacote não pode ser outro pacote');
        } catch (ValidationException $e) {
            $this->assertEquals(['produtoid'], array_keys($e->getErrors()));
        }
        //---------pacote.propriedade_id_existing
        $propriedade = PropriedadeTest::build();
        $propriedade->setNome('Grande');
        $propriedade->insert();
        $pacote = self::build();
        $pacote->setProdutoID(null);
        $pacote->setPropriedadeID($propriedade->getID());
        $pacote->insert();
        try {
            $pacote->insert();
            $this->fail('Propriedade já cadastrada');
        } catch (ValidationException $e) {
            $this->assertEquals(['propriedadeid'], array_keys($e->getErrors()));
        }
    }

    public function testInsertBlankFields()
    {
        $pacote = new Pacote();
        $this->expectException('\Exception');
        $pacote->insert();
    }

}
