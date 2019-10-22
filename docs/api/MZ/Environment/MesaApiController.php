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

namespace MZ\Environment;

use MZ\System\Permissao;
use MZ\Util\Filter;

/**
 * Mesas para lançamento de pedidos
 */
class MesaApiController extends \MZ\Core\ApiController
{
    /**
     * Find all Mesas
     * @Get("/api/mesas", name="api_mesa_find")
     */
    public function find()
    {
        $this->needPermission([Permissao::NOME_CADASTROMESAS]);
        $limit = max(1, min(100, $this->getRequest()->query->getInt('limit', 10)));
        $page = max(1, $this->getRequest()->query->getInt('page', 1));
        $condition = Filter::query($this->getRequest()->query->all());
        $order = $this->getRequest()->query->get('order', '');
        $count = Mesa::count($condition);
        $pager = new \Pager($count, $limit, $page);
        $mesas = Mesa::findAll($condition, $order, $limit, $pager->offset);
        $itens = [];
        foreach ($mesas as $mesa) {
            $itens[] = $mesa->publish(app()->auth->provider);
        }
        return $this->getResponse()->success(['items' => $itens, 'pages' => $pager->pageCount]);
    }

    /**
     * Create a new Mesa
     * @Post("/api/mesas", name="api_mesa_add")
     */
    public function add()
    {
        $this->needPermission([Permissao::NOME_CADASTROMESAS]);
        $localized = $this->getRequest()->query->getBoolean('localized', false);
        $mesa = new Mesa($this->getData());
        $mesa->filter(new Mesa(), app()->auth->provider, $localized);
        $mesa->insert();
        return $this->getResponse()->success(['item' => $mesa->publish(app()->auth->provider)]);
    }

    /**
     * Modify parts of an existing Mesa
     * @Patch("/api/mesas/{id}", name="api_mesa_update", params={ "id": "\d+" })
     *
     * @param int $id Mesa id
     */
    public function modify($id)
    {
        $this->needPermission([Permissao::NOME_CADASTROMESAS]);
        $old_mesa = Mesa::findOrFail(['id' => $id]);
        $localized = $this->getRequest()->query->getBoolean('localized', false);
        $data = $this->getData($old_mesa->toArray());
        $mesa = new Mesa($data);
        $mesa->filter($old_mesa, app()->auth->provider, $localized);
        $mesa->update();
        $old_mesa->clean($mesa);
        return $this->getResponse()->success(['item' => $mesa->publish(app()->auth->provider)]);
    }

    /**
     * Delete existing Mesa
     * @Delete("/api/mesas/{id}", name="api_mesa_delete", params={ "id": "\d+" })
     *
     * @param int $id Mesa id to delete
     */
    public function delete($id)
    {
        $this->needPermission([Permissao::NOME_CADASTROMESAS]);
        $mesa = Mesa::findOrFail(['id' => $id]);
        $mesa->delete();
        $mesa->clean(new Mesa());
        return $this->getResponse()->success([]);
    }
}
