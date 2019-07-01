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

class IntegracaoTest extends \MZ\Framework\TestCase
{
    /**
     * Build a valid integração
     * @param string $nome Integração nome
     * @return Integracao
     */
    public static function build($nome = null)
    {
        $last = Integracao::find([], ['id' => -1]);
        $id = $last->getID() + 1;
        $integracao = new Integracao();
        $integracao->setNome($nome ? :"Integração {$id}");
        $integracao->setAcessoURL("url_{$id}");
        $integracao->setAtivo('N');
        return $integracao;
    }

    /**
     * Create a integração on database
     * @param string $nome Integração nome
     * @return Integracao
     */
    public static function create($nome = null)
    {
        $integracao = self::build($nome);
        $integracao->insert();
        return $integracao;
    }

    public function testFind()
    {
        $integracao = self::create();
        $condition = ['nome' => $integracao->getNome()];
        $found_integracao = Integracao::find($condition);
        $this->assertEquals($integracao, $found_integracao);
        list($found_integracao) = Integracao::findAll($condition, [], 1);
        $this->assertEquals($integracao, $found_integracao);
        $this->assertEquals(1, Integracao::count($condition));
    }

    public function testFindByNome()
    {
        $integracao = self::create();

        $integracaoFound = Integracao::findByNome($integracao->getNome());
        $this->assertInstanceOf(get_class($integracao), $integracaoFound);
    }

    public function testAdd()
    {
        $integracao = self::build();
        $integracao->insert();
        $this->assertTrue($integracao->exists());
    }

    public function testIsAtivo()
    {
        $integracao = self::create();
        $this->assertFalse($integracao->isAtivo());
        //----------
        $integracao->setAtivo('Y');
        $integracao->update();
        $this->assertTrue($integracao->isAtivo());
    }

    public function testAddInvalid()
    {
        $integracao = self::build();
        $integracao->setNome(null);
        $integracao->setAcessoURL(null);
        $integracao->setAtivo('T');
        try {
            $integracao->insert();
            $this->fail('Não inserir valores nulos');
        } catch (ValidationException $e) {
            $this->assertEquals(['nome', 'acessourl', 'ativo'], array_keys($e->getErrors()));
        }
    }

    public function testTranslate()
    {
        $integracao = self::create();
        try {
            $integracao->insert();
            $this->fail('Fk duplicada');
        } catch (ValidationException $e) {
            $this->assertEquals(['acessourl'], array_keys($e->getErrors()));
        }
        //-----------
        $integracao = self::create();
        try {
            $integracao->setAcessoURL('testeurl');
            $integracao->insert();
            $this->fail('Fk duplicada');
        } catch (ValidationException $e) {
            $this->assertEquals(['nome'], array_keys($e->getErrors()));
        }
    }

    public function testMakeDataURL()
    {
        $integracao = new Integracao();
        $data = $integracao->makeDataURL(true);
        $this->assertEquals('/static/doc/.json', $data);
        $integracao->setAcessoURL('teste');
        $this->assertEquals('/static/doc/integracao/teste.json', $integracao->makeDataURL());
    }

    public function testMakeIconeURL()
    {
        $integracao = new Integracao();
        $this->assertEquals('/static/img/integracao.png', $integracao->makeIconeURL(true));

        $integracao->setIconeURL('imagem.png');
        $this->assertEquals('/static/img/integracao/imagem.png', $integracao->makeIconeURL());
    }

    public function testClean()
    {
        $old = new Integracao();
        $old->setIconeURL('teste.png');

        $integracao = new Integracao();
        $integracao->setIconeURL('teste1.png');
        $integracao->clean($old);
        $this->assertEquals($old, $integracao);
    }

    public function testUpdate()
    {
        $integracao = self::create();
        $integracao->update();
        $this->assertTrue($integracao->exists());
    }

    public function testDelete()
    {
        $integracao = self::create();
        $integracao->delete();
        $integracao->loadByID();
        $this->assertFalse($integracao->exists());
    }
}
