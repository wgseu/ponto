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
namespace MZ\Payment;

use MZ\System\Permissao;
use MZ\Util\Filter;

/**
 * Allow application to serve system resources
 */
class CartaoApiController extends \MZ\Core\ApiController
{
    /**
     * Find all Cartões
     * @Get("/api/cartoes", name="api_cartao_find")
     */
    public function find()
    {
        $this->needPermission([Permissao::NOME_CADASTROCARTOES]);
        $limit = max(1, min(100, $this->getRequest()->query->getInt('limit', 10)));
        $page = max(1, $this->getRequest()->query->getInt('page', 1));
        $condition = Filter::query($this->getRequest()->query->all());
        $order = $this->getRequest()->query->get('order', '');
        $count = Cartao::count($condition);
        $pager = new \Pager($count, $limit, $page);
        $cartoes = Cartao::findAll($condition, $order, $limit, $pager->offset);
        $itens = [];
        foreach ($cartoes as $cartao) {
            $itens[] = $cartao->publish(app()->auth->provider);
        }
        return $this->getResponse()->success(['items' => $itens, 'pages' => $pager->pageCount]);
    }

    /**
     * Create a new Cartão
     * @Post("/api/cartoes", name="api_cartao_add")
     */
    public function add()
    {
        $this->needPermission([Permissao::NOME_CADASTROCARTOES]);
        $localized = $this->getRequest()->query->getBoolean('localized', false);
        $cartao = new Cartao($this->getData());
        $cartao->filter(new Cartao(), app()->auth->provider, $localized);
        $cartao->insert();
        return $this->getResponse()->success(['item' => $cartao->publish(app()->auth->provider)]);
    }

    /**
     * Modify parts of an existing Cartão
     * @Patch("/api/cartoes/{id}", name="api_cartao_update", params={ "id": "\d+" })
     * 
     * @param int $id Cartão id
     */
    public function modify($id)
    {
        $this->needPermission([Permissao::NOME_CADASTROCARTOES]);
        $old_cartao = Cartao::findOrFail(['id' => $id]);
        $localized = $this->getRequest()->query->getBoolean('localized', false);
        $data = array_merge($old_cartao->toArray(), $this->getData());
        $cartao = new Cartao($data);
        $cartao->filter($old_cartao, app()->auth->provider, $localized);
        $cartao->update();
        $old_cartao->clean($cartao);
        return $this->getResponse()->success(['item' => $cartao->publish(app()->auth->provider)]);
    }

    /**
     * Delete existing Cartão
     * @Delete("/api/cartoes/{id}", name="api_cartao_delete", params={ "id": "\d+" })
     * 
     * @param int $id Cartão id to delete
     */
    public function delete($id)
    {
        $this->needPermission([Permissao::NOME_CADASTROCARTOES]);
        $cartao = Cartao::findOrFail(['id' => $id]);
        $cartao->delete();
        $cartao->clean(new Cartao());
        return $this->getResponse()->success([]);
    }
}
