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
 * Informa a lista de produtos disponíveis nos fornecedores
 */
class CatalogoApiController extends \MZ\Core\ApiController
{
    /**
     * Find all Catálogos de produtos
     * @Get("/api/catalogos_de_produtos", name="api_catalogo_find")
     */
    public function find()
    {
        $this->needPermission([Permissao::NOME_CADASTROFORNECEDORES]);
        $limit = max(1, min(100, $this->getRequest()->query->getInt('limit', 10)));
        $page = max(1, $this->getRequest()->query->getInt('page', 1));
        $condition = Filter::query($this->getRequest()->query->all());
        $order = $this->getRequest()->query->get('order', '');
        $count = Catalogo::count($condition);
        $pager = new \Pager($count, $limit, $page);
        $catalogos_de_produtos = Catalogo::findAll($condition, $order, $limit, $pager->offset);
        $itens = [];
        foreach ($catalogos_de_produtos as $catalogo) {
            $itens[] = $catalogo->publish(app()->auth->provider);
        }
        return $this->getResponse()->success(['items' => $itens, 'pages' => $pager->pageCount]);
    }

    /**
     * Create a new Catálogo de produtos
     * @Post("/api/catalogos_de_produtos", name="api_catalogo_add")
     */
    public function add()
    {
        $this->needPermission([Permissao::NOME_CADASTROFORNECEDORES]);
        $localized = $this->getRequest()->query->getBoolean('localized', false);
        $catalogo = new Catalogo($this->getData());
        $catalogo->filter(new Catalogo(), app()->auth->provider, $localized);
        $catalogo->insert();
        return $this->getResponse()->success(['item' => $catalogo->publish(app()->auth->provider)]);
    }

    /**
     * Modify parts of an existing Catálogo de produtos
     * @Patch("/api/catalogos_de_produtos/{id}", name="api_catalogo_update", params={ "id": "\d+" })
     *
     * @param int $id Catálogo de produtos id
     */
    public function modify($id)
    {
        $this->needPermission([Permissao::NOME_CADASTROFORNECEDORES]);
        $old_catalogo = Catalogo::findOrFail(['id' => $id]);
        $localized = $this->getRequest()->query->getBoolean('localized', false);
        $data = $this->getData($old_catalogo->toArray());
        $catalogo = new Catalogo($data);
        $catalogo->filter($old_catalogo, app()->auth->provider, $localized);
        $catalogo->update();
        $old_catalogo->clean($catalogo);
        return $this->getResponse()->success(['item' => $catalogo->publish(app()->auth->provider)]);
    }

    /**
     * Delete existing Catálogo de produtos
     * @Delete("/api/catalogos_de_produtos/{id}", name="api_catalogo_delete", params={ "id": "\d+" })
     *
     * @param int $id Catálogo de produtos id to delete
     */
    public function delete($id)
    {
        $this->needPermission([Permissao::NOME_CADASTROFORNECEDORES]);
        $catalogo = Catalogo::findOrFail(['id' => $id]);
        $catalogo->delete();
        $catalogo->clean(new Catalogo());
        return $this->getResponse()->success([]);
    }
}
