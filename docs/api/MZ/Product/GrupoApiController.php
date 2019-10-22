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

namespace MZ\Product;

use MZ\System\Permissao;
use MZ\Util\Filter;

/**
 * Grupos de pacotes, permite criar grupos como Tamanho, Sabores para
 * formações de produtos
 */
class GrupoApiController extends \MZ\Core\ApiController
{
    /**
     * Find all Grupos
     * @Get("/api/grupos", name="api_grupo_find")
     */
    public function find()
    {
        $this->needPermission([Permissao::NOME_CADASTROPRODUTOS]);
        $limit = max(1, min(100, $this->getRequest()->query->getInt('limit', 10)));
        $page = max(1, $this->getRequest()->query->getInt('page', 1));
        $condition = Filter::query($this->getRequest()->query->all());
        $order = $this->getRequest()->query->get('order', '');
        $count = Grupo::count($condition);
        $pager = new \Pager($count, $limit, $page);
        $grupos = Grupo::findAll($condition, $order, $limit, $pager->offset);
        $itens = [];
        foreach ($grupos as $grupo) {
            $itens[] = $grupo->publish(app()->auth->provider);
        }
        return $this->getResponse()->success(['items' => $itens, 'pages' => $pager->pageCount]);
    }

    /**
     * Create a new Grupo
     * @Post("/api/grupos", name="api_grupo_add")
     */
    public function add()
    {
        $this->needPermission([Permissao::NOME_CADASTROPRODUTOS]);
        $localized = $this->getRequest()->query->getBoolean('localized', false);
        $grupo = new Grupo($this->getData());
        $grupo->filter(new Grupo(), app()->auth->provider, $localized);
        $grupo->insert();
        return $this->getResponse()->success(['item' => $grupo->publish(app()->auth->provider)]);
    }

    /**
     * Modify parts of an existing Grupo
     * @Patch("/api/grupos/{id}", name="api_grupo_update", params={ "id": "\d+" })
     *
     * @param int $id Grupo id
     */
    public function modify($id)
    {
        $this->needPermission([Permissao::NOME_CADASTROPRODUTOS]);
        $old_grupo = Grupo::findOrFail(['id' => $id]);
        $localized = $this->getRequest()->query->getBoolean('localized', false);
        $data = $this->getData($old_grupo->toArray());
        $grupo = new Grupo($data);
        $grupo->filter($old_grupo, app()->auth->provider, $localized);
        $grupo->update();
        $old_grupo->clean($grupo);
        return $this->getResponse()->success(['item' => $grupo->publish(app()->auth->provider)]);
    }

    /**
     * Delete existing Grupo
     * @Delete("/api/grupos/{id}", name="api_grupo_delete", params={ "id": "\d+" })
     *
     * @param int $id Grupo id to delete
     */
    public function delete($id)
    {
        $this->needPermission([Permissao::NOME_CADASTROPRODUTOS]);
        $grupo = Grupo::findOrFail(['id' => $id]);
        $grupo->delete();
        $grupo->clean(new Grupo());
        return $this->getResponse()->success([]);
    }
}
