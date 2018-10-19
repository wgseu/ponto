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

class AcessoTest extends \MZ\Framework\TestCase
{
    /**
     * @return Acesso[]
     */
    public static function create($funcao, $permissions)
    {
        $result = [];
        foreach ($permissions as $permissao_nome) {
            $permissao = Permissao::findByNome($permissao_nome);
            $acesso = new Acesso();
            $acesso->setPermissaoID($permissao->getID());
            $acesso->setFuncaoID($funcao->getID());
            $acesso->insert();
            $result[] = $acesso;
        }
        return $result;
    }

    public function testPublish()
    {
        $acesso = new Acesso();
        $values = $acesso->publish();
        $allowed = [
            'id',
            'funcaoid',
            'permissaoid',
        ];
        $this->assertEquals($allowed, array_keys($values));
    }
}