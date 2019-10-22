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
 * Setor de impressão e de estoque
 */
class SetorApiController extends \MZ\Core\ApiController
{
    /**
     * Find all Setores
     * @Get("/api/setores", name="api_setor_find")
     */
    public function find()
    {
        $this->needPermission([Permissao::NOME_ESTOQUE]);
        $limit = max(1, min(100, $this->getRequest()->query->getInt('limit', 10)));
        $page = max(1, $this->getRequest()->query->getInt('page', 1));
        $condition = Filter::query($this->getRequest()->query->all());
        unset($condition['ordem']);
        $order = $this->getRequest()->query->get('order', '');
        $count = Setor::count($condition);
        $pager = new \Pager($count, $limit, $page);
        $setores = Setor::findAll($condition, $order, $limit, $pager->offset);
        $itens = [];
        foreach ($setores as $setor) {
            $itens[] = $setor->publish(app()->auth->provider);
        }
        return $this->getResponse()->success(['items' => $itens, 'pages' => $pager->pageCount]);
    }

    /**
     * Create a new Setor
     * @Post("/api/setores", name="api_setor_add")
     */
    public function add()
    {
        $this->needPermission([Permissao::NOME_ESTOQUE]);
        $localized = $this->getRequest()->query->getBoolean('localized', false);
        $setor = new Setor($this->getData());
        $setor->filter(new Setor(), app()->auth->provider, $localized);
        $setor->insert();
        return $this->getResponse()->success(['item' => $setor->publish(app()->auth->provider)]);
    }

    /**
     * Modify parts of an existing Setor
     * @Patch("/api/setores/{id}", name="api_setor_update", params={ "id": "\d+" })
     *
     * @param int $id Setor id
     */
    public function modify($id)
    {
        $this->needPermission([Permissao::NOME_ESTOQUE]);
        $old_setor = Setor::findOrFail(['id' => $id]);
        $localized = $this->getRequest()->query->getBoolean('localized', false);
        $data = $this->getData($old_setor->toArray());
        $setor = new Setor($data);
        $setor->filter($old_setor, app()->auth->provider, $localized);
        $setor->update();
        $old_setor->clean($setor);
        return $this->getResponse()->success(['item' => $setor->publish(app()->auth->provider)]);
    }

    /**
     * Delete existing Setor
     * @Delete("/api/setores/{id}", name="api_setor_delete", params={ "id": "\d+" })
     *
     * @param int $id Setor id to delete
     */
    public function delete($id)
    {
        $this->needPermission([Permissao::NOME_ESTOQUE]);
        $setor = Setor::findOrFail(['id' => $id]);
        $setor->delete();
        $setor->clean(new Setor());
        return $this->getResponse()->success([]);
    }
}
