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
namespace MZ\Location;

use MZ\System\Permissao;
use MZ\Util\Filter;

/**
 * Bairro de uma cidade
 */
class BairroApiController extends \MZ\Core\ApiController
{
    /**
     * Find all Bairros
     * @Get("/api/bairros", name="api_bairro_find")
     */
    public function find()
    {
        $this->needPermission([Permissao::NOME_CADASTROBAIRROS]);
        $limit = max(1, min(100, $this->getRequest()->query->getInt('limit', 10)));
        $page = max(1, $this->getRequest()->query->getInt('page', 1));
        $condition = Filter::query($this->getRequest()->query->all());
        unset($condition['ordem']);
        $order = $this->getRequest()->query->get('order', '');
        $count = Bairro::count($condition);
        $pager = new \Pager($count, $limit, $page);
        $bairros = Bairro::findAll($condition, $order, $limit, $pager->offset);
        $itens = [];
        foreach ($bairros as $bairro) {
            $itens[] = $bairro->publish(app()->auth->provider);
        }
        return $this->getResponse()->success(['items' => $itens, 'pages' => $pager->pageCount]);
    }

    /**
     * Create a new Bairro
     * @Post("/api/bairros", name="api_bairro_add")
     */
    public function add()
    {
        $this->needPermission([Permissao::NOME_CADASTROBAIRROS]);
        $localized = $this->getRequest()->query->getBoolean('localized', false);
        $bairro = new Bairro($this->getData());
        $bairro->filter(new Bairro(), app()->auth->provider, $localized);
        $bairro->insert();
        return $this->getResponse()->success(['item' => $bairro->publish(app()->auth->provider)]);
    }

    /**
     * Modify parts of an existing Bairro
     * @Patch("/api/bairros/{id}", name="api_bairro_update", params={ "id": "\d+" })
     *
     * @param int $id Bairro id
     */
    public function modify($id)
    {
        $this->needPermission([Permissao::NOME_CADASTROBAIRROS]);
        $old_bairro = Bairro::findOrFail(['id' => $id]);
        $localized = $this->getRequest()->query->getBoolean('localized', false);
        $data = $this->getData($old_bairro->toArray());
        $bairro = new Bairro($data);
        $bairro->filter($old_bairro, app()->auth->provider, $localized);
        $bairro->update();
        $old_bairro->clean($bairro);
        return $this->getResponse()->success(['item' => $bairro->publish(app()->auth->provider)]);
    }


    /**
     * Delete existing Bairro
     * @Delete("/api/bairros/{id}", name="api_bairro_delete", params={ "id": "\d+" })
     *
     * @param int $id Bairro id to delete
     */
    public function delete($id)
    {
        $this->needPermission([Permissao::NOME_CADASTROBAIRROS]);
        $bairro = Bairro::findOrFail(['id' => $id]);
        $bairro->delete();
        $bairro->clean(new Bairro());
        return $this->getResponse()->success([]);
    }
}
