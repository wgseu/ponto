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
namespace MZ\Wallet;

use MZ\System\Permissao;
use MZ\Util\Filter;

/**
 * Informa uma conta bancária ou uma carteira financeira
 */
class CarteiraApiController extends \MZ\Core\ApiController
{
    /**
     * Find all Carteiras
     * @Get("/api/carteiras", name="api_carteira_find")
     */
    public function find()
    {
        $this->needPermission([Permissao::NOME_CADASTROCARTEIRAS]);
        $limit = max(1, min(100, $this->getRequest()->query->getInt('limit', 10)));
        $page = max(1, $this->getRequest()->query->getInt('page', 1));
        $condition = Filter::query($this->getRequest()->query->all());
        unset($condition['ordem']);
        $order = $this->getRequest()->query->get('order', '');
        $count = Carteira::count($condition);
        $pager = new \Pager($count, $limit, $page);
        $carteiras = Carteira::findAll($condition, $order, $limit, $pager->offset);
        $itens = [];
        foreach ($carteiras as $carteira) {
            $itens[] = $carteira->publish(app()->auth->provider);
        }
        return $this->getResponse()->success(['items' => $itens, 'pages' => $pager->pageCount]);
    }

    /**
     * Create a new Carteira
     * @Post("/api/carteiras", name="api_carteira_add")
     */
    public function add()
    {
        $this->needPermission([Permissao::NOME_CADASTROCARTEIRAS]);
        $localized = $this->getRequest()->query->getBoolean('localized', false);
        $carteira = new Carteira($this->getData());
        $carteira->filter(new Carteira(), app()->auth->provider, $localized);
        $carteira->insert();
        return $this->getResponse()->success(['item' => $carteira->publish(app()->auth->provider)]);
    }

    /**
     * Modify parts of an existing Carteira
     * @Patch("/api/carteiras/{id}", name="api_carteira_update", params={ "id": "\d+" })
     *
     * @param int $id Carteira id
     */
    public function modify($id)
    {
        $this->needPermission([Permissao::NOME_CADASTROCARTEIRAS]);
        $old_carteira = Carteira::findOrFail(['id' => $id]);
        $localized = $this->getRequest()->query->getBoolean('localized', false);
        $data = $this->getData($old_carteira->toArray());
        $carteira = new Carteira($data);
        $carteira->filter($old_carteira, app()->auth->provider, $localized);
        $carteira->update();
        $old_carteira->clean($carteira);
        return $this->getResponse()->success(['item' => $carteira->publish(app()->auth->provider)]);
    }

    /**
     * Delete existing Carteira
     * @Delete("/api/carteiras/{id}", name="api_carteira_delete", params={ "id": "\d+" })
     *
     * @param int $id Carteira id to delete
     */
    public function delete($id)
    {
        $this->needPermission([Permissao::NOME_CADASTROCARTEIRAS]);
        $carteira = Carteira::findOrFail(['id' => $id]);
        $carteira->delete();
        $carteira->clean(new Carteira());
        return $this->getResponse()->success([]);
    }
}
