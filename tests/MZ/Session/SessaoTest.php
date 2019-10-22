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

namespace MZ\Session;

use MZ\Database\DB;
use MZ\Exception\ValidationException;
use MZ\Session\MovimentacaoTest;

class SessaoTest extends \MZ\Framework\TestCase
{
    /**
     * Build a valid sessão
     * @return Sessao
     */
    public static function build()
    {
        $sessao = new Sessao();
        $sessao->setDataInicio('2016-12-25 12:15:00');
        $sessao->setAberta('Y');
        return $sessao;
    }

    /**
     * Create a sessão on database
     * @return Sessao
     */
    public static function create($force = false)
    {
        $sessao = self::build();
        $aberta = Sessao::findByAberta();
        if ($aberta->exists() && !$force) {
            return $aberta;
        }
        $sessao->insert();
        return $sessao;
    }

    /**
     * Fecha a sessão
     * @param Sessao $sessao
     */
    public static function close($sessao)
    {
        $sessao->setAberta('N');
        $sessao->setDataTermino(DB::now());
        $sessao->update();
    }

    public function testFromArray()
    {
        $old_sessao = new Sessao([
            'id' => 123,
            'datainicio' => '2016-12-25 12:15:00',
            'datatermino' => '2016-12-25 12:15:00',
            'aberta' => 'Y',
        ]);
        $sessao = new Sessao();
        $sessao->fromArray($old_sessao);
        $this->assertEquals($sessao, $old_sessao);
        $sessao->fromArray(null);
        $this->assertEquals($sessao, new Sessao());
    }

    public function testFilter()
    {
        $old_sessao = new Sessao([
            'id' => 1234,
            'datainicio' => '2016-12-23 12:15:00',
            'datatermino' => '2016-12-23 12:15:00',
            'aberta' => 'Y',
        ]);
        $sessao = new Sessao([
            'id' => 321,
            'datainicio' => '23/12/2016 12:15',
            'datatermino' => '23/12/2016 12:15',
            'aberta' => 'Y',
        ]);
        $sessao->filter($old_sessao, app()->auth->provider, true);
        $this->assertEquals($old_sessao, $sessao);
    }

    public function testPublish()
    {
        $sessao = new Sessao();
        $values = $sessao->publish(app()->auth->provider);
        $allowed = [
            'id',
            'datainicio',
            'datatermino',
            'aberta',
        ];
        $this->assertEquals($allowed, array_keys($values));
    }

    public function testInsert()
    {
        $sessao = new Sessao();
        $sessao->setDataInicio(null);
        try {
            $sessao->insert();
            $this->fail('Não deveria ter cadastrado a sessão');
        } catch (ValidationException $e) {
            $this->assertEquals(
                [
                    'datainicio',
                    'datatermino',
                    'aberta',
                ],
                array_keys($e->getErrors())
            );
        }
        $sessao->setDataInicio('2016-12-25 12:15:00');
        $sessao->setAberta('Y');
        $sessao->insert();
        // tenta abrir duas sessões
        try {
            $outra_sessao = new Sessao();
            $outra_sessao->setDataInicio('2016-12-25 12:15:00');
            $outra_sessao->setAberta('Y');
            $outra_sessao->insert();
            $this->fail('Não deveria ter cadastrado outra sessão');
        } catch (ValidationException $e) {
            $this->assertEquals(
                [
                    'aberta',
                ],
                array_keys($e->getErrors())
            );
        }
        // fecha para não interferir nos outros testes
        self::close($sessao);
    }

    public function testUpdate()
    {
        $sessao = new Sessao();
        $sessao->setDataInicio('2016-12-26 12:15:00');
        $sessao->setAberta('Y');
        $sessao->insert();
        $sessao->update();
        $found_sessao = Sessao::findByID($sessao->getID());
        $this->assertEquals($sessao, $found_sessao);
        // fecha para não interferir nos outros testes
        self::close($sessao);
    }

    public function testDelete()
    {
        $sessao = new Sessao();
        $sessao->setDataInicio('2016-12-20 12:15:00');
        $sessao->setAberta('Y');
        $sessao->insert();
        $sessao->delete();
        $sessao->clean(new Sessao());
        $found_sessao = Sessao::findByID($sessao->getID());
        $this->assertEquals(new Sessao(), $found_sessao);
        $sessao->setID('');
        $this->expectException('\Exception');
        $sessao->delete();
    }

    public function testFind()
    {
        $sessao = new Sessao();
        $sessao->setDataInicio('2016-11-25 12:15:00');
        $sessao->setAberta('Y');
        $sessao->insert();
        // fecha para não interferir nos outros testes
        self::close($sessao);
        $found_sessao = Sessao::findByID($sessao->getID());
        $this->assertEquals($sessao, $found_sessao);

        $sessao_sec = new Sessao();
        $sessao_sec->setDataInicio('2016-11-25 12:15:00');
        $sessao_sec->setAberta('Y');
        $sessao_sec->insert();
        // fecha para não interferir nos outros testes
        self::close($sessao);

        $sessoes = Sessao::findAll(['datainicio' => '2016-11-25 12:15:00'], [], 2, 0);
        $this->assertEquals([$sessao, $sessao_sec], $sessoes);

        $count = Sessao::count(['datainicio' => '2016-11-25 12:15:00']);
        $this->assertEquals(2, $count);
        self::close($sessao);
    }

    public function testDtTerminoInvalid()
    {
        $sessao = self::build();
        $sessao->setDataTermino(DB::now());
        try {
            $sessao->insert();
            $this->fail('Não salvar com sessão aberta e data termino informada');
        } catch (ValidationException $e) {
            $this->assertEquals(['datatermino', 'aberta'], array_keys($e->getErrors()));
        }
    }

    public function testAddSessaoAbertaInvalid()
    {
        $sessao = self::build();
        $sessao->setAberta('T');
        $sessao->setDataTermino(DB::now());
        try {
            $sessao->insert();
            $this->fail('Valor para aberta invalido');
        } catch (ValidationException $e) {
            $this->assertEquals(['aberta'], array_keys($e->getErrors()));
        }
        //-------!$this->isAberta() && $count > 0
        $movimentacao = MovimentacaoTest::create();

        $sessao = $movimentacao->findSessaoID();
        $sessao->setAberta('N');
        $sessao->setDataTermino(DB::now());
        try {
            $sessao->update();
            $this->fail('Sessão em uso em uma movimentação');
        } catch (ValidationException $e) {
            $this->assertEquals(['aberta'], array_keys($e->getErrors()));
        }
        MovimentacaoTest::close($movimentacao);
    }

    public function testReabrirSessao()
    {
        $sessao = self::create();
        self::close($sessao);
        try {
            $sessao->setDataTermino(null);
            $sessao->setAberta('Y');
            $sessao->update();
            $this->fail('Não reabrir uma sessão fechada');
        } catch (ValidationException $e) {
            $this->assertEquals(['datatermino', 'aberta'], array_keys($e->getErrors()));
        }
    }

    public function testDtInicio()
    {
        $sessao = self::create();
        try {
            $sessao->setDataInicio(DB::now());
            $sessao->update();
            $this->fail('Não alterar a data de abertura da sessão');
        } catch (ValidationException $e) {
            $this->assertEquals(['datainicio'], array_keys($e->getErrors()));
        }
        $sessaoClose = Sessao::find(['id' => $sessao->getID()]);
        self::close($sessaoClose);
    }

    public function testFindLastAberta()
    {
        $sessao = self::create();
        $lastSessao = Sessao::findLastAberta();
        $this->assertInstanceOf(get_class($sessao), $lastSessao);
        self::close($sessao);
        self::close($lastSessao);
    }

    public function testFindAbertaInvalid()
    {
        $this->expectException("\Exception");
        Sessao::findByAberta(true);
    }

}
