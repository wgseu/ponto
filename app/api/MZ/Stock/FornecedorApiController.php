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
 * Fornecedores de produtos
 */
class FornecedorApiController extends \MZ\Core\ApiController
{
    /**
     * Find all Fornecedores
     * @Get("/api/fornecedores", name="api_fornecedor_find")
     */
    public function find()
    {
        $this->needPermission([Permissao::NOME_CADASTROFORNECEDORES]);
        $limit = max(1, min(100, $this->getRequest()->query->getInt('limit', 10)));
        $page = max(1, $this->getRequest()->query->getInt('page', 1));
        $condition = Filter::query($this->getRequest()->query->all());
        $order = $this->getRequest()->query->get('order', '');
        $count = Fornecedor::count($condition);
        $pager = new \Pager($count, $limit, $page);
        $fornecedores = Fornecedor::findAll($condition, $order, $limit, $pager->offset);
        $itens = [];
        foreach ($fornecedores as $fornecedor) {
            $itens[] = $fornecedor->publish(app()->auth->provider);
        }
        return $this->getResponse()->success(['items' => $itens, 'pages' => $pager->pageCount]);
    }

    /**
     * Create a new Fornecedor
     * @Post("/api/fornecedores", name="api_fornecedor_add")
     */
    public function add()
    {
        $this->needPermission([Permissao::NOME_CADASTROFORNECEDORES]);
        $localized = $this->getRequest()->query->getBoolean('localized', false);
        $fornecedor = new Fornecedor($this->getData());
        $fornecedor->filter(new Fornecedor(), app()->auth->provider, $localized);
        $fornecedor->insert();
        return $this->getResponse()->success(['item' => $fornecedor->publish(app()->auth->provider)]);
    }

    /**
     * Modify parts of an existing Fornecedor
     * @Patch("/api/fornecedores/{id}", name="api_fornecedor_update", params={ "id": "\d+" })
     *
     * @param int $id Fornecedor id
     */
    public function modify($id)
    {
        $this->needPermission([Permissao::NOME_CADASTROFORNECEDORES]);
        $old_fornecedor = Fornecedor::findOrFail(['id' => $id]);
        $localized = $this->getRequest()->query->getBoolean('localized', false);
        $data = $this->getData($old_fornecedor->toArray());
        $fornecedor = new Fornecedor($data);
        $fornecedor->filter($old_fornecedor, app()->auth->provider, $localized);
        $fornecedor->update();
        $old_fornecedor->clean($fornecedor);
        return $this->getResponse()->success(['item' => $fornecedor->publish(app()->auth->provider)]);
    }

    /**
     * Delete existing Fornecedor
     * @Delete("/api/fornecedores/{id}", name="api_fornecedor_delete", params={ "id": "\d+" })
     *
     * @param int $id Fornecedor id to delete
     */
    public function delete($id)
    {
        $this->needPermission([Permissao::NOME_CADASTROFORNECEDORES]);
        $fornecedor = Fornecedor::findOrFail(['id' => $id]);
        $fornecedor->delete();
        $fornecedor->clean(new Fornecedor());
        return $this->getResponse()->success([]);
    }
}
