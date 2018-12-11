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

    public function testAdd()
    {
        $classificacao = self::build();
        $classificacao->insert();
        $this->assertTrue($classificacao->exists());
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
