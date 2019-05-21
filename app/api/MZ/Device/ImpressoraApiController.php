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
namespace MZ\Device;

use MZ\System\Permissao;
use MZ\Util\Filter;

/**
 * Impressora para impressão de serviços e contas
 */
class ImpressoraApiController extends \MZ\Core\ApiController
{
    /**
     * Find all Impressoras
     * @Get("/api/impressoras", name="api_impressora_find")
     */
    public function find()
    {
        $this->needPermission([Permissao::NOME_CADASTROIMPRESSORAS]);
        $limit = max(1, min(100, $this->getRequest()->query->getInt('limit', 10)));
        $page = max(1, $this->getRequest()->query->getInt('page', 1));
        $condition = Filter::query($this->getRequest()->query->all());
        $order = $this->getRequest()->query->get('order', '');
        $count = Impressora::count($condition);
        $pager = new \Pager($count, $limit, $page);
        $impressoras = Impressora::findAll($condition, $order, $limit, $pager->offset);
        $itens = [];
        foreach ($impressoras as $impressora) {
            $itens[] = $impressora->publish(app()->auth->provider);
        }
        return $this->getResponse()->success(['items' => $itens, 'pages' => $pager->pageCount]);
    }

    /**
     * Create a new Impressora
     * @Post("/api/impressoras", name="api_impressora_add")
     */
    public function add()
    {
        $this->needPermission([Permissao::NOME_CADASTROIMPRESSORAS]);
        $localized = $this->getRequest()->query->getBoolean('localized', false);
        $impressora = new Impressora($this->getData());
        $impressora->filter(new Impressora(), app()->auth->provider, $localized);
        $impressora->insert();
        return $this->getResponse()->success(['item' => $impressora->publish(app()->auth->provider)]);
    }

    /**
     * Modify parts of an existing Impressora
     * @Patch("/api/impressoras/{id}", name="api_impressora_update", params={ "id": "\d+" })
     *
     * @param int $id Impressora id
     */
    public function modify($id)
    {
        $this->needPermission([Permissao::NOME_CADASTROIMPRESSORAS]);
        $old_impressora = Impressora::findOrFail(['id' => $id]);
        $localized = $this->getRequest()->query->getBoolean('localized', false);
        $data = $this->getData($old_impressora->toArray());
        $impressora = new Impressora($data);
        $impressora->filter($old_impressora, app()->auth->provider, $localized);
        $impressora->update();
        $old_impressora->clean($impressora);
        return $this->getResponse()->success(['item' => $impressora->publish(app()->auth->provider)]);
    }

    /**
     * Delete existing Impressora
     * @Delete("/api/impressoras/{id}", name="api_impressora_delete", params={ "id": "\d+" })
     *
     * @param int $id Impressora id to delete
     */
    public function delete($id)
    {
        $this->needPermission([Permissao::NOME_CADASTROIMPRESSORAS]);
        $impressora = Impressora::findOrFail(['id' => $id]);
        $impressora->delete();
        $impressora->clean(new Impressora());
        return $this->getResponse()->success([]);
    }
}
