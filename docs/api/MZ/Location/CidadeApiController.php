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
 * Cidade de um estado, contém bairros
 */
class CidadeApiController extends \MZ\Core\ApiController
{
    /**
     * Find all Cidades
     * @Get("/api/cidades", name="api_cidade_find")
     */
    public function find()
    {
        $this->needPermission([Permissao::NOME_CADASTROCIDADES]);
        $limit = max(1, min(100, $this->getRequest()->query->getInt('limit', 10)));
        $page = max(1, $this->getRequest()->query->getInt('page', 1));
        $condition = Filter::query($this->getRequest()->query->all());
        unset($condition['ordem']);
        $order = $this->getRequest()->query->get('order', '');
        $count = Cidade::count($condition);
        $pager = new \Pager($count, $limit, $page);
        $cidades = Cidade::findAll($condition, $order, $limit, $pager->offset);
        $itens = [];
        foreach ($cidades as $cidade) {
            $itens[] = $cidade->publish(app()->auth->provider);
        }
        return $this->getResponse()->success(['items' => $itens, 'pages' => $pager->pageCount]);
    }

    /**
     * Create a new Cidade
     * @Post("/api/cidades", name="api_cidade_add")
     */
    public function add()
    {
        $this->needPermission([Permissao::NOME_CADASTROCIDADES]);
        $localized = $this->getRequest()->query->getBoolean('localized', false);
        $cidade = new Cidade($this->getData());
        $cidade->filter(new Cidade(), app()->auth->provider, $localized);
        $cidade->insert();
        return $this->getResponse()->success(['item' => $cidade->publish(app()->auth->provider)]);
    }

    /**
     * Modify parts of an existing Cidade
     * @Patch("/api/cidades/{id}", name="api_cidade_update", params={ "id": "\d+" })
     *
     * @param int $id Cidade id
     */
    public function modify($id)
    {
        $this->needPermission([Permissao::NOME_CADASTROCIDADES]);
        $old_cidade = Cidade::findOrFail(['id' => $id]);
        $localized = $this->getRequest()->query->getBoolean('localized', false);
        $data = $this->getData($old_cidade->toArray());
        $cidade = new Cidade($data);
        $cidade->filter($old_cidade, app()->auth->provider, $localized);
        $cidade->update();
        $old_cidade->clean($cidade);
        return $this->getResponse()->success(['item' => $cidade->publish(app()->auth->provider)]);
    }

    /**
     * Delete existing Cidade
     * @Delete("/api/cidades/{id}", name="api_cidade_delete", params={ "id": "\d+" })
     *
     * @param int $id Cidade id to delete
     */
    public function delete($id)
    {
        $this->needPermission([Permissao::NOME_CADASTROCIDADES]);
        $cidade = Cidade::findOrFail(['id' => $id]);
        $cidade->delete();
        $cidade->clean(new Cidade());
        return $this->getResponse()->success([]);
    }
}
