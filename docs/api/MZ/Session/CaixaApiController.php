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
 * Caixas de movimentação financeira
 */
class CaixaApiController extends \MZ\Core\ApiController
{
    /**
     * Find all Caixas
     * @Get("/api/caixas", name="api_caixa_find")
     */
    public function find()
    {
        $this->needPermission([Permissao::NOME_CADASTROCAIXAS]);
        $limit = max(1, min(100, $this->getRequest()->query->getInt('limit', 10)));
        $page = max(1, $this->getRequest()->query->getInt('page', 1));
        $condition = Filter::query($this->getRequest()->query->all());
        unset($condition['ordem']);
        $order = $this->getRequest()->query->get('order', '');
        $count = Caixa::count($condition);
        $pager = new \Pager($count, $limit, $page);
        $caixas = Caixa::findAll($condition, $order, $limit, $pager->offset);
        $itens = [];
        foreach ($caixas as $caixa) {
            $itens[] = $caixa->publish(app()->auth->provider);
        }
        return $this->getResponse()->success(['items' => $itens, 'pages' => $pager->pageCount]);
    }

    /**
     * Create a new Caixa
     * @Post("/api/caixas", name="api_caixa_add")
     */
    public function add()
    {
        $this->needPermission([Permissao::NOME_CADASTROCAIXAS]);
        $localized = $this->getRequest()->query->getBoolean('localized', false);
        $caixa = new Caixa($this->getData());
        $caixa->filter(new Caixa(), app()->auth->provider, $localized);
        $caixa->insert();
        return $this->getResponse()->success(['item' => $caixa->publish(app()->auth->provider)]);
    }

    /**
     * Modify parts of an existing Caixa
     * @Patch("/api/caixas/{id}", name="api_caixa_update", params={ "id": "\d+" })
     *
     * @param int $id Caixa id
     */
    public function modify($id)
    {
        $this->needPermission([Permissao::NOME_CADASTROCAIXAS]);
        $old_caixa = Caixa::findOrFail(['id' => $id]);
        $localized = $this->getRequest()->query->getBoolean('localized', false);
        $data = $this->getData($old_caixa->toArray());
        $caixa = new Caixa($data);
        $caixa->setID($old_caixa->getID());
        if (!app()->getSystem()->isFiscalVisible()) {
            $caixa->setNumeroInicial($old_caixa->getNumeroInicial());
            $caixa->setSerie($old_caixa->getSerie());
        }
        $caixa->filter($old_caixa, app()->auth->provider, $localized);
        $caixa->update();
        $old_caixa->clean($caixa);
        return $this->getResponse()->success(['item' => $caixa->publish(app()->auth->provider)]);
    }

    /**
     * Delete existing Caixa
     * @Delete("/api/caixas/{id}", name="api_caixa_delete", params={ "id": "\d+" })
     *
     * @param int $id Caixa id to delete
     */
    public function delete($id)
    {
        $this->needPermission([Permissao::NOME_CADASTROCAIXAS]);
        $caixa = Caixa::findOrFail(['id' => $id]);
        $caixa->delete();
        $caixa->clean(new Caixa());
        return $this->getResponse()->success([]);
    }
}
