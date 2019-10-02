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
 * Informações de um páis com sua moeda e língua nativa
 */
class PaisApiController extends \MZ\Core\ApiController
{
    /**
     * Find all Paises
     * @Get("/api/paises", name="api_pais_find")
     */
    public function find()
    {
        $this->needPermission([Permissao::NOME_CADASTROPAISES]);
        $limit = max(1, min(100, $this->getRequest()->query->getInt('limit', 10)));
        $page = max(1, $this->getRequest()->query->getInt('page', 1));
        $condition = Filter::query($this->getRequest()->query->all());
        unset($condition['ordem']);
        $order = $this->getRequest()->query->get('order', '');
        $count = Pais::count($condition);
        $pager = new \Pager($count, $limit, $page);
        $paises = Pais::findAll($condition, $order, $limit, $pager->offset);
        $itens = [];
        foreach ($paises as $pais) {
            $itens[] = $pais->publish(app()->auth->provider);
        }
        return $this->getResponse()->success(['items' => $itens, 'pages' => $pager->pageCount]);
    }

    /**
     * Create a new País
     * @Post("/api/paises", name="api_pais_add")
     */
    public function add()
    {
        $this->needPermission([Permissao::NOME_CADASTROPAISES]);
        $localized = $this->getRequest()->query->getBoolean('localized', false);
        $pais = new Pais($this->getData());
        $pais->filter(new Pais(), app()->auth->provider, $localized);
        $pais->insert();
        return $this->getResponse()->success(['item' => $pais->publish(app()->auth->provider)]);
    }

    /**
     * Modify parts of an existing País
     * @Patch("/api/paises/{id}", name="api_pais_update", params={ "id": "\d+" })
     *
     * @param int $id País id
     */
    public function modify($id)
    {
        $this->needPermission([Permissao::NOME_CADASTROPAISES]);
        $old_pais = Pais::findOrFail(['id' => $id]);
        $localized = $this->getRequest()->query->getBoolean('localized', false);
        $data = $this->getData($old_pais->toArray());
        $pais = new Pais($data);
        $pais->filter($old_pais, app()->auth->provider, $localized);
        $pais->update();
        $old_pais->clean($pais);
        return $this->getResponse()->success(['item' => $pais->publish(app()->auth->provider)]);
    }

    /**
     * Delete existing País
     * @Delete("/api/paises/{id}", name="api_pais_delete", params={ "id": "\d+" })
     *
     * @param int $id País id to delete
     */
    public function delete($id)
    {
        $this->needPermission([Permissao::NOME_CADASTROPAISES]);
        $pais = Pais::findOrFail(['id' => $id]);
        $pais->delete();
        $pais->clean(new Pais());
        return $this->getResponse()->success([]);
    }
}
