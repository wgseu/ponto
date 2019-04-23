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
namespace MZ\Environment;

use MZ\System\Permissao;
use MZ\Util\Filter;

/**
 * Informa detalhadamente um bem da empresa
 */
class PatrimonioApiController extends \MZ\Core\ApiController
{
    /**
     * Find all Patrimônios
     * @Get("/api/patrimonios", name="api_patrimonio_find")
     */
    public function find()
    {
        $this->needPermission([Permissao::NOME_CADASTROPATRIMONIO]);
        $limit = max(1, min(100, $this->getRequest()->query->getInt('limit', 10)));
        $page = max(1, $this->getRequest()->query->getInt('page', 1));
        $condition = Filter::query($this->getRequest()->query->all());
        $order = $this->getRequest()->query->get('order', '');
        $count = Patrimonio::count($condition);
        $pager = new \Pager($count, $limit, $page);
        $patrimonios = Patrimonio::findAll($condition, $order, $limit, $pager->offset);
        $itens = [];
        foreach ($patrimonios as $patrimonio) {
            $itens[] = $patrimonio->publish(app()->auth->provider);
        }
        return $this->getResponse()->success(['items' => $itens, 'pages' => $pager->pageCount]);
    }

    /**
     * Create a new Patrimônio
     * @Post("/api/patrimonios", name="api_patrimonio_add")
     */
    public function add()
    {
        $this->needPermission([Permissao::NOME_CADASTROPATRIMONIO]);
        $localized = $this->getRequest()->query->getBoolean('localized', false);
        $patrimonio = new Patrimonio($this->getData());
        $patrimonio->filter(new Patrimonio(), app()->auth->provider, $localized);
        $patrimonio->insert();
        return $this->getResponse()->success(['item' => $patrimonio->publish(app()->auth->provider)]);
    }

    /**
     * Modify parts of an existing Patrimônio
     * @Patch("/api/patrimonios/{id}", name="api_patrimonio_update", params={ "id": "\d+" })
     *
     * @param int $id Patrimônio id
     */
    public function modify($id)
    {
        $this->needPermission([Permissao::NOME_CADASTROPATRIMONIO]);
        $old_patrimonio = Patrimonio::findOrFail(['id' => $id]);
        $localized = $this->getRequest()->query->getBoolean('localized', false);
        $data = $this->getData($old_patrimonio->toArray());
        $patrimonio = new Patrimonio($data);
        $patrimonio->filter($old_patrimonio, app()->auth->provider, $localized);
        $patrimonio->update();
        $old_patrimonio->clean($patrimonio);
        return $this->getResponse()->success(['item' => $patrimonio->publish(app()->auth->provider)]);
    }

    /**
     * Delete existing Patrimônio
     * @Delete("/api/patrimonios/{id}", name="api_patrimonio_delete", params={ "id": "\d+" })
     *
     * @param int $id Patrimônio id to delete
     */
    public function delete($id)
    {
        $this->needPermission([Permissao::NOME_CADASTROPATRIMONIO]);
        $patrimonio = Patrimonio::findOrFail(['id' => $id]);
        $patrimonio->delete();
        $patrimonio->clean(new Patrimonio());
        return $this->getResponse()->success([]);
    }
}
