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

namespace MZ\Payment;

use MZ\System\Permissao;
use MZ\Util\Filter;

/**
 * Pagamentos de contas e pedidos
 */
class PagamentoApiController extends \MZ\Core\ApiController
{
    /**
     * Find all Pagamentos
     * @Get("/api/pagamentos", name="api_pagamento_find")
     */
    public function find()
    {
        $this->needPermission([Permissao::NOME_PAGAMENTO]);
        $limit = max(1, min(100, $this->getRequest()->query->getInt('limit', 10)));
        $page = max(1, $this->getRequest()->query->getInt('page', 1));
        $condition = Filter::query($this->getRequest()->query->all());
        unset($condition['ordem']);
        $order = $this->getRequest()->query->get('order', '');
        $count = Pagamento::count($condition);
        $pager = new \Pager($count, $limit, $page);
        $pagamentos = Pagamento::findAll($condition, $order, $limit, $pager->offset);
        $itens = [];
        foreach ($pagamentos as $pagamento) {
            $itens[] = $pagamento->publish(app()->auth->provider);
        }
        return $this->getResponse()->success(['items' => $itens, 'pages' => $pager->pageCount]);
    }

    /**
     * Create a new Pagamento
     * @Post("/api/pagamentos", name="api_pagamento_add")
     */
    public function add()
    {
        $this->needPermission([Permissao::NOME_PAGAMENTO]);
        $localized = $this->getRequest()->query->getBoolean('localized', false);
        $pagamento = new Pagamento($this->getData());
        $pagamento->filter(new Pagamento(), app()->auth->provider, $localized);
        $pagamento->insert();
        return $this->getResponse()->success(['item' => $pagamento->publish(app()->auth->provider)]);
    }

    /**
     * Modify parts of an existing Pagamento
     * @Patch("/api/pagamentos/{id}", name="api_pagamento_update", params={ "id": "\d+" })
     *
     * @param int $id Pagamento id
     */
    public function modify($id)
    {
        $this->needPermission([Permissao::NOME_PAGAMENTO]);
        $old_pagamento = Pagamento::findOrFail(['id' => $id]);
        $localized = $this->getRequest()->query->getBoolean('localized', false);
        $data = $this->getData($old_pagamento->toArray());
        $pagamento = new Pagamento($data);
        $pagamento->filter($old_pagamento, app()->auth->provider, $localized);
        $pagamento->update();
        $old_pagamento->clean($pagamento);
        return $this->getResponse()->success(['item' => $pagamento->publish(app()->auth->provider)]);
    }

    /**
     * Delete existing Pagamento
     * @Delete("/api/pagamentos/{id}", name="api_pagamento_delete", params={ "id": "\d+" })
     *
     * @param int $id Pagamento id to delete
     */
    public function delete($id)
    {
        $this->needPermission([Permissao::NOME_PAGAMENTO]);
        $pagamento = Pagamento::findOrFail(['id' => $id]);
        $pagamento->delete();
        $pagamento->clean(new Pagamento());
        return $this->getResponse()->success([]);
    }
}
