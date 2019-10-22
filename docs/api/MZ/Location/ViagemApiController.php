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
 * Registro de viagem de uma entrega ou compra de insumos
 */
class ViagemApiController extends \MZ\Core\ApiController
{
    /**
     * Find all Viagens
     * @Get("/api/viagens", name="api_viagem_find")
     */
    public function find()
    {
        $this->needPermission([Permissao::NOME_PAGAMENTO]);
        $limit = max(1, min(100, $this->getRequest()->query->getInt('limit', 10)));
        $page = max(1, $this->getRequest()->query->getInt('page', 1));
        $condition = Filter::query($this->getRequest()->query->all());
        $order = $this->getRequest()->query->get('order', '');
        $count = Viagem::count($condition);
        $pager = new \Pager($count, $limit, $page);
        $viagens = Viagem::findAll($condition, $order, $limit, $pager->offset);
        $itens = [];
        foreach ($viagens as $viagem) {
            $itens[] = $viagem->publish(app()->auth->provider);
        }
        return $this->getResponse()->success(['items' => $itens, 'pages' => $pager->pageCount]);
    }

    /**
     * Create a new Viagem
     * @Post("/api/viagens", name="api_viagem_add")
     */
    public function add()
    {
        $this->needPermission([Permissao::NOME_PAGAMENTO]);
        $localized = $this->getRequest()->query->getBoolean('localized', false);
        $viagem = new Viagem($this->getData());
        $viagem->filter(new Viagem(), app()->auth->provider, $localized);
        $viagem->insert();
        return $this->getResponse()->success(['item' => $viagem->publish(app()->auth->provider)]);
    }

    /**
     * Modify parts of an existing Viagem
     * @Patch("/api/viagens/{id}", name="api_viagem_update", params={ "id": "\d+" })
     *
     * @param int $id Viagem id
     */
    public function modify($id)
    {
        $this->needPermission([Permissao::NOME_PAGAMENTO]);
        $old_viagem = Viagem::findOrFail(['id' => $id]);
        $localized = $this->getRequest()->query->getBoolean('localized', false);
        $data = $this->getData($old_viagem->toArray());
        $viagem = new Viagem($data);
        $viagem->filter($old_viagem, app()->auth->provider, $localized);
        $viagem->update();
        $old_viagem->clean($viagem);
        return $this->getResponse()->success(['item' => $viagem->publish(app()->auth->provider)]);
    }

    /**
     * Delete existing Viagem
     * @Delete("/api/viagens/{id}", name="api_viagem_delete", params={ "id": "\d+" })
     *
     * @param int $id Viagem id to delete
     */
    public function delete($id)
    {
        $this->needPermission([Permissao::NOME_PAGAMENTO]);
        $viagem = Viagem::findOrFail(['id' => $id]);
        $viagem->delete();
        $viagem->clean(new Viagem());
        return $this->getResponse()->success([]);
    }
}
