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

use MZ\Provider\PrestadorTest;
use MZ\Exception\ValidationException;

class AuditoriaTest extends \MZ\Framework\TestCase
{
    /**
     * Build a valid auditoria
     * @param string $descricao Auditoria descrição
     * @return Auditoria
     */
    public static function build($descricao = null)
    {
        $last = Auditoria::find([], ['id' => -1]);
        $id = $last->getID() + 1;
        $prestador = PrestadorTest::create();
        $prestador = PrestadorTest::create();
        $auditoria = new Auditoria();
        $auditoria->setPrestadorID($prestador->getID());
        $auditoria->setAutorizadorID($prestador->getID());
        $auditoria->setTipo(Auditoria::TIPO_FINANCEIRO);
        $auditoria->setPrioridade(Auditoria::PRIORIDADE_BAIXA);
        $auditoria->setDescricao($descricao ?: "Auditoria {$id}");
        $auditoria->setDataHora('2016-12-25 12:15:00');
        return $auditoria;
    }

    /**
     * Create a auditoria on database
     * @param string $descricao Auditoria descrição
     * @return Auditoria
     */
    public static function create($descricao = null)
    {
        $auditoria = self::build($descricao);
        $auditoria->insert();
        return $auditoria;
    }

    public function testFind()
    {
        $auditoria = self::create();
        $condition = ['descricao' => $auditoria->getDescricao()];
        $found_auditoria = Auditoria::find($condition);
        $this->assertEquals($auditoria, $found_auditoria);
        list($found_auditoria) = Auditoria::findAll($condition, [], 1);
        $this->assertEquals($auditoria, $found_auditoria);
        $this->assertEquals(1, Auditoria::count($condition));
    }

    public function testAdd()
    {
        $auditoria = self::build();
        $auditoria->insert();
        $this->assertTrue($auditoria->exists());
    }

    public function testAddInvalid()
    {
        $auditoria = self::build();
        $auditoria->setPrestadorID(null);
        $auditoria->setAutorizadorID(null);
        $auditoria->setTipo('Tipo inválido');
        $auditoria->setPrioridade('Inválido');
        $auditoria->setDescricao(null);
        try {
            $auditoria->insert();
            $this->fail('Valores inválidos');
        } catch (ValidationException $e) {
            $this->assertEquals(['prestadorid', 'autorizadorid', 'tipo', 'prioridade', 'descricao'], array_keys($e->getErrors()));
        }
    }

    public function testFinds()
    {
        $auditoria = self::create();

        $permissao = $auditoria->findPermissaoID();
        $this->assertEquals($auditoria->getPermissaoID(), $permissao->getID());

        $prestador = $auditoria->findPrestadorID();
        $this->assertEquals($auditoria->getPrestadorID(), $prestador->getID());

        $autorizador = $auditoria->findAutorizadorID();
        $this->assertEquals($auditoria->getAutorizadorID(), $autorizador->getID());
    }

    public function testGetOptions()
    {
        $auditoria = new Auditoria(['tipo' => Auditoria::TIPO_ADMINISTRATIVO]);
        $options = Auditoria::getTipoOptions();
        $this->assertEquals(Auditoria::getTipoOptions($auditoria->getTipo()), $options[$auditoria->getTipo()]);
    }

    public function testGetPrioridade()
    {
        $auditoria = new Auditoria(['prioridade' => Auditoria::PRIORIDADE_MEDIA]);
        $options = Auditoria::getPrioridadeOptions();
        $this->assertEquals(Auditoria::getPrioridadeOptions($auditoria->getPrioridade()), $options[$auditoria->getPrioridade()]);
    }

    public function testUpdate()
    {
        $auditoria = self::create();
        $auditoria->update();
        $this->assertTrue($auditoria->exists());
    }

    public function testDelete()
    {
        $auditoria = self::create();
        $auditoria->delete();
        $auditoria->loadByID();
        $this->assertFalse($auditoria->exists());
    }
}
