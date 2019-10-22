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

namespace MZ\Account;

use MZ\Exception\ValidationException;

class ClassificacaoTest extends \MZ\Framework\TestCase
{
    /**
     * Build a valid classificação
     * @param string $descricao Classificação descrição
     * @return Classificacao
     */
    public static function build($descricao = null)
    {
        $last = Classificacao::find([], ['id' => -1]);
        $id = $last->getID() + 1;
        $classificacao = new Classificacao();
        // $classificacao->setClassificacaoID(1);
        $classificacao->setDescricao($descricao ?: "Classificação {$id}");
        return $classificacao;
    }

    /**
     * Create a classificação on database
     * @param string $descricao Classificação descrição
     * @return Classificacao
     */
    public static function create($descricao = null)
    {
        $classificacao = self::build($descricao);
        $classificacao->insert();
        return $classificacao;
    }

    public function testFind()
    {
        $classificacao = self::create();
        $condition = ['descricao' => $classificacao->getDescricao()];
        $found_classificacao = Classificacao::find($condition);
        $this->assertEquals($classificacao, $found_classificacao);
        list($found_classificacao) = Classificacao::findAll($condition, [], 1);
        $this->assertEquals($classificacao, $found_classificacao);
        $this->assertEquals(1, Classificacao::count($condition));
    }

    public function testFindByDescricao()
    {
        $classificacao = self::create();
        $classificacaoFind = Classificacao::findByDescricao($classificacao->getDescricao());
        $this->assertEquals($classificacao->getDescricao(), $classificacaoFind->getDescricao());
    }

    public function testAdd()
    {
        $classificacao = self::build();
        $classificacao->insert();
        $this->assertTrue($classificacao->exists());
    }

    public function testAddInvalid()
    {
        $classificacao = self::build();
        $classificacao->setDescricao(null);

        try {
            $classificacao->insert();
            $this->fail('Não pode cadastra com valores null');
        } catch (ValidationException $e) {
            $this->assertEquals(['descricao'], array_keys($e->getErrors()));
        }
        //-----------------------------------
        //Tenta cadastra uma subclassificacao com uma classificacao que não existe
        $classificacao = self::build();
        $classificacao->setDescricao('Teste 1');
        $classificacao->insert();

        $subClassificacao = self::build();
        $subClassificacao->setDescricao('SubClass');
        //a subC estara "associada" com a Classificacao 54
        $subClassificacao->setClassificacaoID(54);
        try {
            $subClassificacao->insert();
            $this->fail('A classificacao pai não existe');
        } catch (ValidationException $e) {
            $this->assertEquals(['classificacaoid'], array_keys($e->getErrors()));
        }
        //-----------------------------------
        $classificacao = self::build();
        $classificacao->setDescricao('Teste vários níveis');
        $classificacao->insert();

        //criar a subClassificacao
        $subClassificacao = self::build();
        $subClassificacao->setDescricao('subClassificacao');
        $subClassificacao->setClassificacaoID($classificacao->getID());
        $subClassificacao->insert();

        //criar uma $subClassificacao da $subClassificacao
        $subSubClassificacao = self::build();
        $subSubClassificacao->setDescricao('testinho 1');
        $subSubClassificacao->setClassificacaoID($subClassificacao->getID());
        try {
            $subSubClassificacao->insert();
            $this->fail('Não cadastrar mais de 1 nível');
        } catch (ValidationException $e) {
            $this->assertEquals(['classificacaoid'], array_keys($e->getErrors()));
        }
    }

    public function testUpdateInvalid()
    {
        $classificacao_pai = self::build();
        $classificacao_pai->setDescricao('Teste');
        $classificacao_pai->insert();

        //criando outra classificacao 
        $classificacao = self::build();
        $classificacao->setID($classificacao_pai->getID());
        $classificacao->setClassificacaoID($classificacao_pai->getID());
        $classificacao->setDescricao('Testinho');

        try {
            $classificacao->update();
            $this->fail('Erro');
        } catch (ValidationException $e) {
            $this->assertEquals(['classificacaoid'], array_keys($e->getErrors()));
        }
    }

    public function testTranslate()
    {
        $classificacao = self::build();
        $classificacao->insert();

        try {
            $classificacao->insert();
            $this->fail('Não cadastrar descricao duplicada');
        } catch (ValidationException $e) {
            $this->assertEquals(['descricao'], array_keys($e->getErrors()));
        }
    }

    public function testMakeIconeURL()
    {
        $classificacao = new Classificacao();
        $this->assertEquals('/static/img/classificacao.png', $classificacao->makeIconeURL(true));
        $classificacao->setIconeURL('imagem.png');
        $this->assertEquals('/static/img/classificacao/imagem.png', $classificacao->makeIconeURL());
    }

    public function testClean()
    {
        $old_classificacao = new Classificacao();
        $old_classificacao->setIconeURL('classificacaoFake.png');

        $classificacao = new Classificacao();
        $classificacao->setIconeURL('classificacaoFake2.png');
        $classificacao->clean($old_classificacao);
        $this->assertEquals($old_classificacao, $classificacao);
    }

    public function testUpdate()
    {
        $classificacao = self::create();
        $classificacao->update();
        $this->assertTrue($classificacao->exists());
    }

    public function testDelete()
    {
        $classificacao = self::create();
        $classificacao->delete();
        $classificacao->loadByID();
        $this->assertFalse($classificacao->exists());
    }
}
