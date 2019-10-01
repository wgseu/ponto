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
 * Informa os produtos da lista de compras
 */
class RequisitoApiController extends \MZ\Core\ApiController
{
    /**
     * Find all Produtos das listas
     * @Get("/api/produtos_das_listas", name="api_requisito_find")
     */
    public function find()
    {
        $this->needPermission([Permissao::NOME_LISTACOMPRAS]);
        $limit = max(1, min(100, $this->getRequest()->query->getInt('limit', 10)));
        $page = max(1, $this->getRequest()->query->getInt('page', 1));
        $condition = Filter::query($this->getRequest()->query->all());
        $order = $this->getRequest()->query->get('order', '');
        $count = Requisito::count($condition);
        $pager = new \Pager($count, $limit, $page);
        $produtos_das_listas = Requisito::findAll($condition, $order, $limit, $pager->offset);
        $itens = [];
        foreach ($produtos_das_listas as $requisito) {
            $itens[] = $requisito->publish(app()->auth->provider);
        }
        return $this->getResponse()->success(['items' => $itens, 'pages' => $pager->pageCount]);
    }

    /**
     * Create a new Produtos da lista
     * @Post("/api/produtos_das_listas", name="api_requisito_add")
     */
    public function add()
    {
        $this->needPermission([Permissao::NOME_LISTACOMPRAS]);
        $localized = $this->getRequest()->query->getBoolean('localized', false);
        $requisito = new Requisito($this->getData());
        $requisito->filter(new Requisito(), app()->auth->provider, $localized);
        $requisito->insert();
        return $this->getResponse()->success(['item' => $requisito->publish(app()->auth->provider)]);
    }

    /**
     * Modify parts of an existing Produtos da lista
     * @Patch("/api/produtos_das_listas/{id}", name="api_requisito_update", params={ "id": "\d+" })
     *
     * @param int $id Produtos da lista id
     */
    public function modify($id)
    {
        $this->needPermission([Permissao::NOME_LISTACOMPRAS]);
        $old_requisito = Requisito::findOrFail(['id' => $id]);
        $localized = $this->getRequest()->query->getBoolean('localized', false);
        $data = $this->getData($old_requisito->toArray());
        $requisito = new Requisito($data);
        $requisito->filter($old_requisito, app()->auth->provider, $localized);
        $requisito->update();
        $old_requisito->clean($requisito);
        return $this->getResponse()->success(['item' => $requisito->publish(app()->auth->provider)]);
    }

    /**
     * Delete existing Produtos da lista
     * @Delete("/api/produtos_das_listas/{id}", name="api_requisito_delete", params={ "id": "\d+" })
     *
     * @param int $id Produtos da lista id to delete
     */
    public function delete($id)
    {
        $this->needPermission([Permissao::NOME_LISTACOMPRAS]);
        $requisito = Requisito::findOrFail(['id' => $id]);
        $requisito->delete();
        $requisito->clean(new Requisito());
        return $this->getResponse()->success([]);
    }
}
