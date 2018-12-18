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


class FuncionalidadeTest extends \MZ\Framework\TestCase
{
    public function testFind()
    {
        $funcionalidade = Funcionalidade::find([], ['id' => -1]);
        $funcionalidade = Funcionalidade::findByNome($funcionalidade->getNome());
        $condition = ['descricao' => $funcionalidade->getDescricao()];
        $found_funcionalidade = Funcionalidade::find($condition);
        $this->assertEquals($funcionalidade, $found_funcionalidade);
        list($found_funcionalidade) = Funcionalidade::findAll($condition, [], 1);
        $this->assertEquals($funcionalidade, $found_funcionalidade);
        $this->assertEquals(1, Funcionalidade::count($condition));
    }

    public function testOther()
    {
        $funcionalidade = Funcionalidade::find([], ['id' => -1]);
        $funcionalidade_copy = new Funcionalidade();
        $funcionalidade_copy->fromArray($funcionalidade);
        $funcionalidade_copy->filter($funcionalidade, app()->auth->provider);
        $funcionalidade_copy->clean($funcionalidade);
        $this->assertEquals($funcionalidade, $funcionalidade_copy);
        $this->assertEquals($funcionalidade->toArray(), $funcionalidade_copy->validate());
    }
}
