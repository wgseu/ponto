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

namespace MZ\Location;

use MZ\System\Permissao;
use MZ\Util\Filter;

/**
 * Zonas de um bairro
 */
class ZonaApiController extends \MZ\Core\ApiController
{
    /**
     * Find all Zonas
     * @Get("/api/zonas", name="api_zona_find")
     */
    public function find()
    {
        $this->needPermission([Permissao::NOME_CADASTROBAIRROS]);
        $limit = max(1, min(100, $this->getRequest()->query->getInt('limit', 10)));
        $page = max(1, $this->getRequest()->query->getInt('page', 1));
        $condition = Filter::query($this->getRequest()->query->all());
        $order = $this->getRequest()->query->get('order', '');
        $count = Zona::count($condition);
        $pager = new \Pager($count, $limit, $page);
        $zonas = Zona::findAll($condition, $order, $limit, $pager->offset);
        $itens = [];
        foreach ($zonas as $zona) {
            $itens[] = $zona->publish(app()->auth->provider);
        }
        return $this->getResponse()->success(['items' => $itens, 'pages' => $pager->pageCount]);
    }

    /**
     * Create a new Zona
     * @Post("/api/zonas", name="api_zona_add")
     */
    public function add()
    {
        $this->needPermission([Permissao::NOME_CADASTROBAIRROS]);
        $localized = $this->getRequest()->query->getBoolean('localized', false);
        $zona = new Zona($this->getData());
        $zona->filter(new Zona(), app()->auth->provider, $localized);
        $zona->insert();
        return $this->getResponse()->success(['item' => $zona->publish(app()->auth->provider)]);
    }

    /**
     * Modify parts of an existing Zona
     * @Patch("/api/zonas/{id}", name="api_zona_update", params={ "id": "\d+" })
     *
     * @param int $id Zona id
     */
    public function modify($id)
    {
        $this->needPermission([Permissao::NOME_CADASTROBAIRROS]);
        $old_zona = Zona::findOrFail(['id' => $id]);
        $localized = $this->getRequest()->query->getBoolean('localized', false);
        $data = $this->getData($old_zona->toArray());
        $zona = new Zona($data);
        $zona->filter($old_zona, app()->auth->provider, $localized);
        $zona->update();
        $old_zona->clean($zona);
        return $this->getResponse()->success(['item' => $zona->publish(app()->auth->provider)]);
    }

    /**
     * Delete existing Zona
     * @Delete("/api/zonas/{id}", name="api_zona_delete", params={ "id": "\d+" })
     *
     * @param int $id Zona id to delete
     */
    public function delete($id)
    {
        $this->needPermission([Permissao::NOME_CADASTROBAIRROS]);
        $zona = Zona::findOrFail(['id' => $id]);
        $zona->delete();
        $zona->clean(new Zona());
        return $this->getResponse()->success([]);
    }
}
