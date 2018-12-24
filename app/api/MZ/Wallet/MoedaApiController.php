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
 * Moedas financeiras de um país
 */
class MoedaApiController extends \MZ\Core\ApiController
{
    /**
     * Find all Moedas
     * @Get("/api/moedas", name="api_moeda_find")
     */
    public function find()
    {
        $limit = max(1, min(100, $this->getRequest()->query->getInt('limit', 10)));
        $page = max(1, $this->getRequest()->query->getInt('page', 1));
        $condition = Filter::query($this->getRequest()->query->all());
        if (!app()->auth->has([Permissao::NOME_CADASTROMOEDAS])) {
            $condition['ativa'] = 'Y';
        }
        $order = $this->getRequest()->query->get('order', '');
        $count = Moeda::count($condition);
        $pager = new \Pager($count, $limit, $page);
        $moedas = Moeda::findAll($condition, $order, $limit, $pager->offset);
        $itens = [];
        foreach ($moedas as $moeda) {
            $itens[] = $moeda->publish(app()->auth->provider);
        }
        return $this->getResponse()->success(['items' => $itens, 'pages' => $pager->pageCount]);
    }

    /**
     * Create a new Moeda
     * @Post("/api/moedas", name="api_moeda_add")
     */
    public function add()
    {
        $this->needPermission([Permissao::NOME_CADASTROMOEDAS]);
        $localized = $this->getRequest()->query->getBoolean('localized', false);
        $moeda = new Moeda($this->getData());
        $moeda->filter(new Moeda(), app()->auth->provider, $localized);
        $moeda->insert();
        return $this->getResponse()->success(['item' => $moeda->publish(app()->auth->provider)]);
    }

    /**
     * Modify parts of an existing Moeda
     * @Patch("/api/moedas/{id}", name="api_moeda_update", params={ "id": "\d+" })
     *
     * @param int $id Moeda id
     */
    public function modify($id)
    {
        $this->needPermission([Permissao::NOME_CADASTROMOEDAS]);
        $old_moeda = Moeda::findOrFail(['id' => $id]);
        $localized = $this->getRequest()->query->getBoolean('localized', false);
        $data = $this->getData($old_moeda->toArray());
        $moeda = new Moeda($data);
        $moeda->filter($old_moeda, app()->auth->provider, $localized);
        $moeda->update();
        $old_moeda->clean($moeda);
        return $this->getResponse()->success(['item' => $moeda->publish(app()->auth->provider)]);
    }

    /**
     * Delete existing Moeda
     * @Delete("/api/moedas/{id}", name="api_moeda_delete", params={ "id": "\d+" })
     *
     * @param int $id Moeda id to delete
     */
    public function delete($id)
    {
        $this->needPermission([Permissao::NOME_CADASTROMOEDAS]);
        $moeda = Moeda::findOrFail(['id' => $id]);
        $moeda->delete();
        $moeda->clean(new Moeda());
        return $this->getResponse()->success([]);
    }
}
