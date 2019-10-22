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

namespace MZ\Stock;

use MZ\System\Permissao;
use MZ\Util\Filter;

/**
 * Compras realizadas em uma lista num determinado fornecedor
 */
class CompraApiController extends \MZ\Core\ApiController
{
    /**
     * Find all Compras
     * @Get("/api/compras", name="api_compra_find")
     */
    public function find()
    {
        $this->needPermission([Permissao::NOME_LISTACOMPRAS]);
        $limit = max(1, min(100, $this->getRequest()->query->getInt('limit', 10)));
        $page = max(1, $this->getRequest()->query->getInt('page', 1));
        $condition = Filter::query($this->getRequest()->query->all());
        $order = $this->getRequest()->query->get('order', '');
        $count = Compra::count($condition);
        $pager = new \Pager($count, $limit, $page);
        $compras = Compra::findAll($condition, $order, $limit, $pager->offset);
        $itens = [];
        foreach ($compras as $compra) {
            $itens[] = $compra->publish(app()->auth->provider);
        }
        return $this->getResponse()->success(['items' => $itens, 'pages' => $pager->pageCount]);
    }

    /**
     * Create a new Compra
     * @Post("/api/compras", name="api_compra_add")
     */
    public function add()
    {
        $this->needPermission([Permissao::NOME_LISTACOMPRAS]);
        $localized = $this->getRequest()->query->getBoolean('localized', false);
        $compra = new Compra($this->getData());
        $compra->filter(new Compra(), app()->auth->provider, $localized);
        $compra->insert();
        return $this->getResponse()->success(['item' => $compra->publish(app()->auth->provider)]);
    }

    /**
     * Modify parts of an existing Compra
     * @Patch("/api/compras/{id}", name="api_compra_update", params={ "id": "\d+" })
     *
     * @param int $id Compra id
     */
    public function modify($id)
    {
        $this->needPermission([Permissao::NOME_LISTACOMPRAS]);
        $old_compra = Compra::findOrFail(['id' => $id]);
        $localized = $this->getRequest()->query->getBoolean('localized', false);
        $data = $this->getData($old_compra->toArray());
        $compra = new Compra($data);
        $compra->filter($old_compra, app()->auth->provider, $localized);
        $compra->update();
        $old_compra->clean($compra);
        return $this->getResponse()->success(['item' => $compra->publish(app()->auth->provider)]);
    }

    /**
     * Delete existing Compra
     * @Delete("/api/compras/{id}", name="api_compra_delete", params={ "id": "\d+" })
     *
     * @param int $id Compra id to delete
     */
    public function delete($id)
    {
        $this->needPermission([Permissao::NOME_LISTACOMPRAS]);
        $compra = Compra::findOrFail(['id' => $id]);
        $compra->delete();
        $compra->clean(new Compra());
        return $this->getResponse()->success([]);
    }
}
