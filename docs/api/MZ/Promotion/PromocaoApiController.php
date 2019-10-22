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

namespace MZ\Promotion;

use MZ\System\Permissao;
use MZ\Util\Filter;

/**
 * Informa se há descontos nos produtos em determinados dias da semana, o
 * preço pode subir ou descer e ser agendado para ser aplicado
 */
class PromocaoApiController extends \MZ\Core\ApiController
{
    /**
     * Find all Promoções
     * @Get("/api/promocoes", name="api_promocao_find")
     */
    public function find()
    {
        $this->needPermission([Permissao::NOME_CADASTROPRODUTOS]);
        $limit = max(1, min(100, $this->getRequest()->query->getInt('limit', 10)));
        $page = max(1, $this->getRequest()->query->getInt('page', 1));
        $condition = Filter::query($this->getRequest()->query->all());
        $order = $this->getRequest()->query->get('order', '');
        $count = Promocao::count($condition);
        $pager = new \Pager($count, $limit, $page);
        $promocoes = Promocao::findAll($condition, $order, $limit, $pager->offset);
        $itens = [];
        foreach ($promocoes as $promocao) {
            $itens[] = $promocao->publish(app()->auth->provider);
        }
        return $this->getResponse()->success(['items' => $itens, 'pages' => $pager->pageCount]);
    }

    /**
     * Create a new Promoção
     * @Post("/api/promocoes", name="api_promocao_add")
     */
    public function add()
    {
        $this->needPermission([Permissao::NOME_CADASTROPRODUTOS]);
        $localized = $this->getRequest()->query->getBoolean('localized', false);
        $promocao = new Promocao($this->getData());
        $promocao->filter(new Promocao(), app()->auth->provider, $localized);
        $promocao->insert();
        return $this->getResponse()->success(['item' => $promocao->publish(app()->auth->provider)]);
    }

    /**
     * Modify parts of an existing Promoção
     * @Patch("/api/promocoes/{id}", name="api_promocao_update", params={ "id": "\d+" })
     *
     * @param int $id Promoção id
     */
    public function modify($id)
    {
        $this->needPermission([Permissao::NOME_CADASTROPRODUTOS]);
        $old_promocao = Promocao::findOrFail(['id' => $id]);
        $localized = $this->getRequest()->query->getBoolean('localized', false);
        $data = $this->getData($old_promocao->toArray());
        $promocao = new Promocao($data);
        $promocao->filter($old_promocao, app()->auth->provider, $localized);
        $promocao->update();
        $old_promocao->clean($promocao);
        return $this->getResponse()->success(['item' => $promocao->publish(app()->auth->provider)]);
    }

    /**
     * Delete existing Promoção
     * @Delete("/api/promocoes/{id}", name="api_promocao_delete", params={ "id": "\d+" })
     *
     * @param int $id Promoção id to delete
     */
    public function delete($id)
    {
        $this->needPermission([Permissao::NOME_CADASTROPRODUTOS]);
        $promocao = Promocao::findOrFail(['id' => $id]);
        $promocao->delete();
        $promocao->clean(new Promocao());
        return $this->getResponse()->success([]);
    }
}
