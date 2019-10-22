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
 * Informa qual a categoria dos produtos e permite a rápida localização dos
 * mesmos
 */
class CategoriaApiController extends \MZ\Core\ApiController
{
    /**
     * Find all Categorias
     * @Get("/api/categorias", name="api_categoria_find")
     */
    public function find()
    {
        $this->needPermission([Permissao::NOME_CADASTROPRODUTOS]);
        $limit = max(1, min(100, $this->getRequest()->query->getInt('limit', 10)));
        $page = max(1, $this->getRequest()->query->getInt('page', 1));
        $condition = Filter::values($this->getRequest()->query->all());
        unset($condition['ordem']);
        $order = $this->getRequest()->query->get('order', '');
        $count = Categoria::count($condition);
        $pager = new \Pager($count, $limit, $page);
        $categorias = Categoria::findAll($condition, $order, $limit, $pager->offset);
        $itens = [];
        foreach ($categorias as $categoria) {
            $itens[] = $categoria->publish(app()->auth->provider);
        }
        return $this->getResponse()->success(['items' => $itens, 'pages' => $pager->pageCount]);
    }

    /**
     * Create a new Categoria
     * @Post("/api/categorias", name="api_categoria_add")
     */
    public function add()
    {
        $this->needPermission([Permissao::NOME_CADASTROPRODUTOS]);
        $localized = $this->getRequest()->query->getBoolean('localized', false);
        $categoria = new Categoria($this->getData());
        $categoria->filter(new Categoria(), app()->auth->provider, $localized);
        $categoria->insert();
        return $this->getResponse()->success(['item' => $categoria->publish(app()->auth->provider)]);
    }

    /**
     * Modify parts of an existing Categoria
     * @Patch("/api/categorias/{id}", name="api_categoria_update", params={ "id": "\d+" })
     *
     * @param int $id Categoria id
     */
    public function modify($id)
    {
        $this->needPermission([Permissao::NOME_CADASTROPRODUTOS]);
        $old_categoria = Categoria::findOrFail(['id' => $id]);
        $localized = $this->getRequest()->query->getBoolean('localized', false);
        $data = $this->getData($old_categoria->toArray());
        $categoria = new Categoria($data);
        $categoria->filter($old_categoria, app()->auth->provider, $localized);
        $categoria->update();
        $old_categoria->clean($categoria);
        return $this->getResponse()->success(['item' => $categoria->publish(app()->auth->provider)]);
    }

    /**
     * Delete existing Categoria
     * @Delete("/api/categorias/{id}", name="api_categoria_delete", params={ "id": "\d+" })
     *
     * @param int $id Categoria id to delete
     */
    public function delete($id)
    {
        $this->needPermission([Permissao::NOME_CADASTROPRODUTOS]);
        $categoria = Categoria::findOrFail(['id' => $id]);
        $categoria->delete();
        $categoria->clean(new Categoria());
        return $this->getResponse()->success([]);
    }
}
