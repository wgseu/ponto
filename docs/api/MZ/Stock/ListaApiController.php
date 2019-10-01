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
 * Lista de compras de produtos
 */
class ListaApiController extends \MZ\Core\ApiController
{
    /**
     * Find all Listas de compras
     * @Get("/api/listas_de_compras", name="api_lista_find")
     */
    public function find()
    {
        $this->needPermission([Permissao::NOME_LISTACOMPRAS]);
        $limit = max(1, min(100, $this->getRequest()->query->getInt('limit', 10)));
        $page = max(1, $this->getRequest()->query->getInt('page', 1));
        $condition = Filter::query($this->getRequest()->query->all());
        $order = $this->getRequest()->query->get('order', '');
        $count = Lista::count($condition);
        $pager = new \Pager($count, $limit, $page);
        $listas_de_compras = Lista::findAll($condition, $order, $limit, $pager->offset);
        $itens = [];
        foreach ($listas_de_compras as $lista) {
            $itens[] = $lista->publish(app()->auth->provider);
        }
        return $this->getResponse()->success(['items' => $itens, 'pages' => $pager->pageCount]);
    }

    /**
     * Create a new Lista de compra
     * @Post("/api/listas_de_compras", name="api_lista_add")
     */
    public function add()
    {
        $this->needPermission([Permissao::NOME_LISTACOMPRAS]);
        $localized = $this->getRequest()->query->getBoolean('localized', false);
        $lista = new Lista($this->getData());
        $lista->filter(new Lista(), app()->auth->provider, $localized);
        $lista->insert();
        return $this->getResponse()->success(['item' => $lista->publish(app()->auth->provider)]);
    }

    /**
     * Modify parts of an existing Lista de compra
     * @Patch("/api/listas_de_compras/{id}", name="api_lista_update", params={ "id": "\d+" })
     *
     * @param int $id Lista de compra id
     */
    public function modify($id)
    {
        $this->needPermission([Permissao::NOME_LISTACOMPRAS]);
        $old_lista = Lista::findOrFail(['id' => $id]);
        $localized = $this->getRequest()->query->getBoolean('localized', false);
        $data = $this->getData($old_lista->toArray());
        $lista = new Lista($data);
        $lista->filter($old_lista, app()->auth->provider, $localized);
        $lista->update();
        $old_lista->clean($lista);
        return $this->getResponse()->success(['item' => $lista->publish(app()->auth->provider)]);
    }

    /**
     * Delete existing Lista de compra
     * @Delete("/api/listas_de_compras/{id}", name="api_lista_delete", params={ "id": "\d+" })
     *
     * @param int $id Lista de compra id to delete
     */
    public function delete($id)
    {
        $this->needPermission([Permissao::NOME_LISTACOMPRAS]);
        $lista = Lista::findOrFail(['id' => $id]);
        $lista->delete();
        $lista->clean(new Lista());
        return $this->getResponse()->success([]);
    }
}
