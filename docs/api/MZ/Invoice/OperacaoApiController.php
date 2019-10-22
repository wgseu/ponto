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
 * Código Fiscal de Operações e Prestações (CFOP)
 */
class OperacaoApiController extends \MZ\Core\ApiController
{
    /**
     * Find all Operações
     * @Get("/api/operacoes", name="api_operacao_find")
     */
    public function find()
    {
        $this->needPermission([Permissao::NOME_CADASTROPRODUTOS]);
        $limit = max(1, min(100, $this->getRequest()->query->getInt('limit', 10)));
        $page = max(1, $this->getRequest()->query->getInt('page', 1));
        $condition = Filter::query($this->getRequest()->query->all());
        $order = $this->getRequest()->query->get('order', '');
        $count = Operacao::count($condition);
        $pager = new \Pager($count, $limit, $page);
        $operacoes = Operacao::findAll($condition, $order, $limit, $pager->offset);
        $itens = [];
        foreach ($operacoes as $operacao) {
            $itens[] = $operacao->publish(app()->auth->provider);
        }
        return $this->getResponse()->success(['items' => $itens, 'pages' => $pager->pageCount]);
    }

    /**
     * Create a new Operação
     * @Post("/api/operacoes", name="api_operacao_add")
     */
    public function add()
    {
        $this->needPermission([Permissao::NOME_CADASTROPRODUTOS]);
        $localized = $this->getRequest()->query->getBoolean('localized', false);
        $operacao = new Operacao($this->getData());
        $operacao->filter(new Operacao(), app()->auth->provider, $localized);
        $operacao->insert();
        return $this->getResponse()->success(['item' => $operacao->publish(app()->auth->provider)]);
    }

    /**
     * Modify parts of an existing Operação
     * @Patch("/api/operacoes/{id}", name="api_operacao_update", params={ "id": "\d+" })
     *
     * @param int $id Operação id
     */
    public function modify($id)
    {
        $this->needPermission([Permissao::NOME_CADASTROPRODUTOS]);
        $old_operacao = Operacao::findOrFail(['id' => $id]);
        $localized = $this->getRequest()->query->getBoolean('localized', false);
        $data = $this->getData($old_operacao->toArray());
        $operacao = new Operacao($data);
        $operacao->filter($old_operacao, app()->auth->provider, $localized);
        $operacao->update();
        $old_operacao->clean($operacao);
        return $this->getResponse()->success(['item' => $operacao->publish(app()->auth->provider)]);
    }

    /**
     * Delete existing Operação
     * @Delete("/api/operacoes/{id}", name="api_operacao_delete", params={ "id": "\d+" })
     *
     * @param int $id Operação id to delete
     */
    public function delete($id)
    {
        $this->needPermission([Permissao::NOME_CADASTROPRODUTOS]);
        $operacao = Operacao::findOrFail(['id' => $id]);
        $operacao->delete();
        $operacao->clean(new Operacao());
        return $this->getResponse()->success([]);
    }
}
