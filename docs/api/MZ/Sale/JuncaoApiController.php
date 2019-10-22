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

namespace MZ\Sale;

use MZ\System\Permissao;
use MZ\Util\Filter;

/**
 * Junções de mesas, informa quais mesas estão juntas ao pedido
 */
class JuncaoApiController extends \MZ\Core\ApiController
{
    /**
     * Find all Junções
     * @Get("/api/juncoes", name="api_juncao_find")
     */
    public function find()
    {
        $this->needPermission([Permissao::NOME_MUDARDEMESA]);
        $limit = max(1, min(100, $this->getRequest()->query->getInt('limit', 10)));
        $page = max(1, $this->getRequest()->query->getInt('page', 1));
        $condition = Filter::query($this->getRequest()->query->all());
        $order = $this->getRequest()->query->get('order', '');
        $count = Juncao::count($condition);
        $pager = new \Pager($count, $limit, $page);
        $juncoes = Juncao::findAll($condition, $order, $limit, $pager->offset);
        $itens = [];
        foreach ($juncoes as $juncao) {
            $itens[] = $juncao->publish(app()->auth->provider);
        }
        return $this->getResponse()->success(['items' => $itens, 'pages' => $pager->pageCount]);
    }

    /**
     * Create a new Junção
     * @Post("/api/juncoes", name="api_juncao_add")
     */
    public function add()
    {
        $this->needPermission([Permissao::NOME_MUDARDEMESA]);
        $localized = $this->getRequest()->query->getBoolean('localized', false);
        $juncao = new Juncao($this->getData());
        $juncao->filter(new Juncao(), app()->auth->provider, $localized);
        $juncao->insert();
        return $this->getResponse()->success(['item' => $juncao->publish(app()->auth->provider)]);
    }

    /**
     * Modify parts of an existing Junção
     * @Patch("/api/juncoes/{id}", name="api_juncao_update", params={ "id": "\d+" })
     *
     * @param int $id Junção id
     */
    public function modify($id)
    {
        $this->needPermission([Permissao::NOME_MUDARDEMESA]);
        $old_juncao = Juncao::findOrFail(['id' => $id]);
        $localized = $this->getRequest()->query->getBoolean('localized', false);
        $data = $this->getData($old_juncao->toArray());
        $juncao = new Juncao($data);
        $juncao->filter($old_juncao, app()->auth->provider, $localized);
        $juncao->update();
        $old_juncao->clean($juncao);
        return $this->getResponse()->success(['item' => $juncao->publish(app()->auth->provider)]);
    }

    /**
     * Delete existing Junção
     * @Delete("/api/juncoes/{id}", name="api_juncao_delete", params={ "id": "\d+" })
     *
     * @param int $id Junção id to delete
     */
    public function delete($id)
    {
        $this->needPermission([Permissao::NOME_MUDARDEMESA]);
        $juncao = Juncao::findOrFail(['id' => $id]);
        $juncao->delete();
        $juncao->clean(new Juncao());
        return $this->getResponse()->success([]);
    }
}
