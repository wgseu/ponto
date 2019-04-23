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
 * Informações sobre o produto, composição ou pacote
 */
class ProdutoApiController extends \MZ\Core\ApiController
{
    /**
     * Find all Produtos
     * @Get("/api/produtos", name="api_produto_find")
     */
    public function find()
    {
        $this->needPermission([Permissao::NOME_CADASTROPRODUTOS]);
        $limit = max(1, min(100, $this->getRequest()->query->getInt('limit', 10)));
        $page = max(1, $this->getRequest()->query->getInt('page', 1));
        $condition = Filter::query($this->getRequest()->query->all());
        $order = $this->getRequest()->query->get('order', '');
        $count = Produto::count($condition);
        $pager = new \Pager($count, $limit, $page);
        $produtos = Produto::findAll($condition, $order, $limit, $pager->offset);
        $itens = [];
        foreach ($produtos as $produto) {
            $itens[] = $produto->publish(app()->auth->provider);
        }
        return $this->getResponse()->success(['items' => $itens, 'pages' => $pager->pageCount]);
    }

    /**
     * Create a new Produto
     * @Post("/api/produtos", name="api_produto_add")
     */
    public function add()
    {
        $this->needPermission([Permissao::NOME_CADASTROPRODUTOS]);
        $localized = $this->getRequest()->query->getBoolean('localized', false);
        $produto = new Produto($this->getData());
        $produto->filter(new Produto(), app()->auth->provider, $localized);
        $produto->insert();
        return $this->getResponse()->success(['item' => $produto->publish(app()->auth->provider)]);
    }

    /**
     * Modify parts of an existing Produto
     * @Patch("/api/produtos/{id}", name="api_produto_update", params={ "id": "\d+" })
     *
     * @param int $id Produto id
     */
    public function modify($id)
    {
        $this->needPermission([Permissao::NOME_CADASTROPRODUTOS]);
        $old_produto = Produto::findOrFail(['id' => $id]);
        $localized = $this->getRequest()->query->getBoolean('localized', false);
        $data = $this->getData($old_produto->toArray());
        $produto = new Produto($data);
        $produto->filter($old_produto, app()->auth->provider, $localized);
        $produto->update();
        $old_produto->clean($produto);
        return $this->getResponse()->success(['item' => $produto->publish(app()->auth->provider)]);
    }

    /**
     * Delete existing Produto
     * @Delete("/api/produtos/{id}", name="api_produto_delete", params={ "id": "\d+" })
     *
     * @param int $id Produto id to delete
     */
    public function delete($id)
    {
        $this->needPermission([Permissao::NOME_CADASTROPRODUTOS]);
        $produto = Produto::findOrFail(['id' => $id]);
        $produto->delete();
        $produto->clean(new Produto());
        return $this->getResponse()->success([]);
    }
}