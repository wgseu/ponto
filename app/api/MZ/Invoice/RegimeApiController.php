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
 * Regimes tributários
 */
class RegimeApiController extends \MZ\Core\ApiController
{
    /**
     * Find all Regimes
     * @Get("/api/regimes", name="api_regime_find")
     */
    public function find()
    {
        $this->needPermission([Permissao::NOME_ALTERARCONFIGURACOES]);
        $limit = max(1, min(100, $this->getRequest()->query->getInt('limit', 10)));
        $page = max(1, $this->getRequest()->query->getInt('page', 1));
        $condition = Filter::query($this->getRequest()->query->all());
        $order = $this->getRequest()->query->get('order', '');
        $count = Regime::count($condition);
        $pager = new \Pager($count, $limit, $page);
        $regimes = Regime::findAll($condition, $order, $limit, $pager->offset);
        $itens = [];
        foreach ($regimes as $regime) {
            $itens[] = $regime->publish(app()->auth->provider);
        }
        return $this->getResponse()->success(['items' => $itens, 'pages' => $pager->pageCount]);
    }

    /**
     * Create a new Regime
     * @Post("/api/regimes", name="api_regime_add")
     */
    public function add()
    {
        $this->needPermission([Permissao::NOME_ALTERARCONFIGURACOES]);
        $localized = $this->getRequest()->query->getBoolean('localized', false);
        $regime = new Regime($this->getData());
        $regime->filter(new Regime(), app()->auth->provider, $localized);
        $regime->insert();
        return $this->getResponse()->success(['item' => $regime->publish(app()->auth->provider)]);
    }

    /**
     * Modify parts of an existing Regime
     * @Patch("/api/regimes/{id}", name="api_regime_update", params={ "id": "\d+" })
     *
     * @param int $id Regime id
     */
    public function modify($id)
    {
        $this->needPermission([Permissao::NOME_ALTERARCONFIGURACOES]);
        $old_regime = Regime::findOrFail(['id' => $id]);
        $localized = $this->getRequest()->query->getBoolean('localized', false);
        $data = $this->getData($old_regime->toArray());
        $regime = new Regime($data);
        $regime->filter($old_regime, app()->auth->provider, $localized);
        $regime->update();
        $old_regime->clean($regime);
        return $this->getResponse()->success(['item' => $regime->publish(app()->auth->provider)]);
    }

    /**
     * Delete existing Regime
     * @Delete("/api/regimes/{id}", name="api_regime_delete", params={ "id": "\d+" })
     *
     * @param int $id Regime id to delete
     */
    public function delete($id)
    {
        $this->needPermission([Permissao::NOME_ALTERARCONFIGURACOES]);
        $regime = Regime::findOrFail(['id' => $id]);
        $regime->delete();
        $regime->clean(new Regime());
        return $this->getResponse()->success([]);
    }
}
