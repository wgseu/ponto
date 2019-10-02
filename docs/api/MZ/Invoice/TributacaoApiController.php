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
namespace MZ\Invoice;

use MZ\System\Permissao;
use MZ\Util\Filter;

/**
 * Informação tributária dos produtos
 */
class TributacaoApiController extends \MZ\Core\ApiController
{
    /**
     * Find all Tributações
     * @Get("/api/tributacoes", name="api_tributacao_find")
     */
    public function find()
    {
        $this->needPermission([Permissao::NOME_CADASTROPRODUTOS]);
        $limit = max(1, min(100, $this->getRequest()->query->getInt('limit', 10)));
        $page = max(1, $this->getRequest()->query->getInt('page', 1));
        $condition = Filter::query($this->getRequest()->query->all());
        $order = $this->getRequest()->query->get('order', '');
        $count = Tributacao::count($condition);
        $pager = new \Pager($count, $limit, $page);
        $tributacoes = Tributacao::findAll($condition, $order, $limit, $pager->offset);
        $itens = [];
        foreach ($tributacoes as $tributacao) {
            $itens[] = $tributacao->publish(app()->auth->provider);
        }
        return $this->getResponse()->success(['items' => $itens, 'pages' => $pager->pageCount]);
    }

    /**
     * Create a new Tributação
     * @Post("/api/tributacoes", name="api_tributacao_add")
     */
    public function add()
    {
        $this->needPermission([Permissao::NOME_CADASTROPRODUTOS]);
        $localized = $this->getRequest()->query->getBoolean('localized', false);
        $tributacao = new Tributacao($this->getData());
        $tributacao->filter(new Tributacao(), app()->auth->provider, $localized);
        $tributacao->insert();
        return $this->getResponse()->success(['item' => $tributacao->publish(app()->auth->provider)]);
    }

    /**
     * Modify parts of an existing Tributação
     * @Patch("/api/tributacoes/{id}", name="api_tributacao_update", params={ "id": "\d+" })
     *
     * @param int $id Tributação id
     */
    public function modify($id)
    {
        $this->needPermission([Permissao::NOME_CADASTROPRODUTOS]);
        $old_tributacao = Tributacao::findOrFail(['id' => $id]);
        $localized = $this->getRequest()->query->getBoolean('localized', false);
        $data = $this->getData($old_tributacao->toArray());
        $tributacao = new Tributacao($data);
        $tributacao->filter($old_tributacao, app()->auth->provider, $localized);
        $tributacao->update();
        $old_tributacao->clean($tributacao);
        return $this->getResponse()->success(['item' => $tributacao->publish(app()->auth->provider)]);
    }

    /**
     * Delete existing Tributação
     * @Delete("/api/tributacoes/{id}", name="api_tributacao_delete", params={ "id": "\d+" })
     *
     * @param int $id Tributação id to delete
     */
    public function delete($id)
    {
        $this->needPermission([Permissao::NOME_CADASTROPRODUTOS]);
        $tributacao = Tributacao::findOrFail(['id' => $id]);
        $tributacao->delete();
        $tributacao->clean(new Tributacao());
        return $this->getResponse()->success([]);
    }
}
