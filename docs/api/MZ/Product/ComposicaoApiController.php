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
 * Informa as propriedades da composição de um produto composto
 */
class ComposicaoApiController extends \MZ\Core\ApiController
{
    /**
     * Find all Composições
     * @Get("/api/composicoes", name="api_composicao_find")
     */
    public function find()
    {
        $this->needPermission([Permissao::NOME_CADASTROPRODUTOS]);
        $limit = max(1, min(100, $this->getRequest()->query->getInt('limit', 10)));
        $page = max(1, $this->getRequest()->query->getInt('page', 1));
        $condition = Filter::query($this->getRequest()->query->all());
        $order = $this->getRequest()->query->get('order', '');
        $count = Composicao::count($condition);
        $pager = new \Pager($count, $limit, $page);
        $composicoes = Composicao::findAll($condition, $order, $limit, $pager->offset);
        $itens = [];
        foreach ($composicoes as $composicao) {
            $itens[] = $composicao->publish(app()->auth->provider);
        }
        return $this->getResponse()->success(['items' => $itens, 'pages' => $pager->pageCount]);
    }

    /**
     * Create a new Composição
     * @Post("/api/composicoes", name="api_composicao_add")
     */
    public function add()
    {
        $this->needPermission([Permissao::NOME_CADASTROPRODUTOS]);
        $localized = $this->getRequest()->query->getBoolean('localized', false);
        $composicao = new Composicao($this->getData());
        $composicao->filter(new Composicao(), app()->auth->provider, $localized);
        $composicao->insert();
        return $this->getResponse()->success(['item' => $composicao->publish(app()->auth->provider)]);
    }

    /**
     * Modify parts of an existing Composição
     * @Patch("/api/composicoes/{id}", name="api_composicao_update", params={ "id": "\d+" })
     *
     * @param int $id Composição id
     */
    public function modify($id)
    {
        $this->needPermission([Permissao::NOME_CADASTROPRODUTOS]);
        $old_composicao = Composicao::findOrFail(['id' => $id]);
        $localized = $this->getRequest()->query->getBoolean('localized', false);
        $data = $this->getData($old_composicao->toArray());
        $composicao = new Composicao($data);
        $composicao->filter($old_composicao, app()->auth->provider, $localized);
        $composicao->update();
        $old_composicao->clean($composicao);
        return $this->getResponse()->success(['item' => $composicao->publish(app()->auth->provider)]);
    }

    /**
     * Delete existing Composição
     * @Delete("/api/composicoes/{id}", name="api_composicao_delete", params={ "id": "\d+" })
     *
     * @param int $id Composição id to delete
     */
    public function delete($id)
    {
        $this->needPermission([Permissao::NOME_CADASTROPRODUTOS]);
        $composicao = Composicao::findOrFail(['id' => $id]);
        $composicao->delete();
        $composicao->clean(new Composicao());
        return $this->getResponse()->success([]);
    }
}
