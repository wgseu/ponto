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

namespace MZ\Session;

use MZ\System\Permissao;
use MZ\Util\Filter;

/**
 * Movimentação do caixa, permite abrir diversos caixas na conta de
 * operadores
 */
class MovimentacaoApiController extends \MZ\Core\ApiController
{
    /**
     * Find all Movimentações
     * @Get("/api/movimentacoes", name="api_movimentacao_find")
     */
    public function find()
    {
        $this->needPermission([Permissao::NOME_ABRIRCAIXA]);
        $limit = max(1, min(100, $this->getRequest()->query->getInt('limit', 10)));
        $page = max(1, $this->getRequest()->query->getInt('page', 1));
        $condition = Filter::query($this->getRequest()->query->all());
        unset($condition['ordem']);
        $order = $this->getRequest()->query->get('order', '');
        $count = Movimentacao::count($condition);
        $pager = new \Pager($count, $limit, $page);
        $movimentacoes = Movimentacao::findAll($condition, $order, $limit, $pager->offset);
        $itens = [];
        foreach ($movimentacoes as $movimentacao) {
            $itens[] = $movimentacao->publish(app()->auth->provider);
        }
        return $this->getResponse()->success(['items' => $itens, 'pages' => $pager->pageCount]);
    }

    /**
     * Create a new Movimentação
     * @Post("/api/movimentacoes", name="api_movimentacao_add")
     */
    public function add()
    {
        $this->needPermission([Permissao::NOME_ABRIRCAIXA]);
        $localized = $this->getRequest()->query->getBoolean('localized', false);
        $movimentacao = new Movimentacao($this->getData());
        $movimentacao->filter(new Movimentacao(), app()->auth->provider, $localized);
        $movimentacao->insert();
        return $this->getResponse()->success(['item' => $movimentacao->publish(app()->auth->provider)]);
    }

    /**
     * Modify parts of an existing Movimentação
     * @Patch("/api/movimentacoes/{id}", name="api_movimentacao_update", params={ "id": "\d+" })
     *
     * @param int $id Movimentação id
     */
    public function modify($id)
    {
        $this->needPermission([Permissao::NOME_ABRIRCAIXA]);
        $old_movimentacao = Movimentacao::findOrFail(['id' => $id]);
        $localized = $this->getRequest()->query->getBoolean('localized', false);
        $data = $this->getData($old_movimentacao->toArray());
        $movimentacao = new Movimentacao($data);
        $movimentacao->filter($old_movimentacao, app()->auth->provider, $localized);
        $movimentacao->update();
        $old_movimentacao->clean($movimentacao);
        return $this->getResponse()->success(['item' => $movimentacao->publish(app()->auth->provider)]);
    }

    /**
     * Delete existing Movimentação
     * @Delete("/api/movimentacoes/{id}", name="api_movimentacao_delete", params={ "id": "\d+" })
     *
     * @param int $id Movimentação id to delete
     */
    public function delete($id)
    {
        $this->needPermission([Permissao::NOME_ABRIRCAIXA]);
        $movimentacao = Movimentacao::findOrFail(['id' => $id]);
        $movimentacao->delete();
        $movimentacao->clean(new Movimentacao());
        return $this->getResponse()->success([]);
    }
}
