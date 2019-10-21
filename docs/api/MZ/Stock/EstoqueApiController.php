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
 * Estoque de produtos por setor
 */
class EstoqueApiController extends \MZ\Core\ApiController
{
    /**
     * Find all Estoques
     * @Get("/api/estoques", name="api_estoque_find")
     */
    public function find()
    {
        $this->needPermission([Permissao::NOME_ESTOQUE]);
        $limit = max(1, min(100, $this->getRequest()->query->getInt('limit', 10)));
        $page = max(1, $this->getRequest()->query->getInt('page', 1));
        $condition = Filter::query($this->getRequest()->query->all());
        unset($condition['ordem']);
        $order = $this->getRequest()->query->get('order', '');
        $count = Estoque::count($condition);
        $pager = new \Pager($count, $limit, $page);
        $estoques = Estoque::findAll($condition, $order, $limit, $pager->offset);
        $itens = [];
        foreach ($estoques as $estoque) {
            $itens[] = $estoque->publish(app()->auth->provider);
        }
        return $this->getResponse()->success(['items' => $itens, 'pages' => $pager->pageCount]);
    }

    /**
     * Create a new Estoque
     * @Post("/api/estoques", name="api_estoque_add")
     */
    public function add()
    {
        $this->needPermission([Permissao::NOME_ESTOQUE]);
        $localized = $this->getRequest()->query->getBoolean('localized', false);
        $data = $this->getData();
        $estoque = new Estoque($data);
        $old_estoque = new Estoque();
        $old_estoque->setPrestadorID($estoque->getPrestadorID());
        $estoque->filter($old_estoque, app()->auth->provider, $localized);
        $prestador_id = $data['prestadorid'] ?? null;
        $prestador = \MZ\Provider\Prestador::findByID($prestador_id);
        $estoque->setPrestadorID($prestador->getID());
        $estoque->insert();
        return $this->getResponse()->success(['item' => $estoque->publish(app()->auth->provider)]);
    }

    /**
     * Modify parts of an existing Estoque
     * @Patch("/api/estoques/{id}", name="api_estoque_update", params={ "id": "\d+" })
     *
     * @param int $id Estoque id
     */
    public function modify($id)
    {
        $this->needPermission([Permissao::NOME_ESTOQUE]);
        $old_estoque = Estoque::findOrFail(['id' => $id]);
        $localized = $this->getRequest()->query->getBoolean('localized', false);
        $data = $this->getData($old_estoque->toArray());
        $estoque = new Estoque($data);
        $estoque->filter($old_estoque, app()->auth->provider, $localized);
        $estoque->update();
        $old_estoque->clean($estoque);
        return $this->getResponse()->success(['item' => $estoque->publish(app()->auth->provider)]);
    }

    /**
     * Delete existing Estoque
     * @Delete("/api/estoques/{id}", name="api_estoque_delete", params={ "id": "\d+" })
     *
     * @param int $id Estoque id to delete
     */
    public function delete($id)
    {
        $this->needPermission([Permissao::NOME_ESTOQUE]);
        $estoque = Estoque::findOrFail(['id' => $id]);
        $estoque->delete();
        $estoque->clean(new Estoque());
        return $this->getResponse()->success([]);
    }

    /**
     * Cancel Estoques
     * @Get("/api/estoques/cancelar", name="api_estoque_cancel")
     */
    public function cancel()
    {
        $this->needPermission([Permissao::NOME_ESTOQUE]);
        $id = $this->getRequest()->query->getInt('id', null);
        $estoque = Estoque::findOrFail(['id' => $id]);
        $estoque->cancelar();
        return $this->getResponse()->success(['item' => $estoque->publish(app()->auth->provider)]);
    }
}