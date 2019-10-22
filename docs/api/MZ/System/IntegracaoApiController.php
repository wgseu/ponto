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

use MZ\System\Permissao;
use MZ\Util\Filter;

/**
 * Informa quais integrações estão disponíveis
 */
class IntegracaoApiController extends \MZ\Core\ApiController
{
    /**
     * Find all Integrações
     * @Get("/api/integracoes", name="api_integracao_find")
     */
    public function find()
    {
        $this->needPermission([Permissao::NOME_ALTERARCONFIGURACOES]);
        $limit = max(1, min(100, $this->getRequest()->query->getInt('limit', 10)));
        $page = max(1, $this->getRequest()->query->getInt('page', 1));
        $condition = Filter::query($this->getRequest()->query->all());
        unset($condition['ordem']);
        $order = $this->getRequest()->query->get('order', '');
        $count = Integracao::count($condition);
        $pager = new \Pager($count, $limit, $page);
        $integracoes = Integracao::findAll($condition, $order, $limit, $pager->offset);
        $itens = [];
        foreach ($integracoes as $integracao) {
            $itens[] = $integracao->publish(app()->auth->provider);
        }
        return $this->getResponse()->success(['items' => $itens, 'pages' => $pager->pageCount]);
    }

    /**
     * Modify parts of an existing Integração
     * @Patch("/api/integracoes/{id}", name="api_integracao_update", params={ "id": "\d+" })
     *
     * @param int $id Integração id
     */
    public function modify($id)
    {
        $this->needPermission([Permissao::NOME_ALTERARCONFIGURACOES]);
        $old_integracao = Integracao::findOrFail(['id' => $id]);
        $localized = $this->getRequest()->query->getBoolean('localized', false);
        $data = $this->getData($old_integracao->toArray());
        $integracao = new Integracao($data);
        $integracao->filter($old_integracao, app()->auth->provider, $localized);
        $integracao->update();
        $old_integracao->clean($integracao);
        return $this->getResponse()->success(['item' => $integracao->publish(app()->auth->provider)]);
    }
}
