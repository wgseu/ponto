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
 * Endereços de ruas e avenidas com informação de CEP
 */
class EnderecoApiController extends \MZ\Core\ApiController
{
    /**
     * Find all Endereços
     * @Get("/api/enderecos", name="api_endereco_find")
     */
    public function find()
    {
        $this->needPermission([Permissao::NOME_CADASTROBAIRROS]);
        $limit = max(1, min(100, $this->getRequest()->query->getInt('limit', 10)));
        $page = max(1, $this->getRequest()->query->getInt('page', 1));
        $condition = Filter::query($this->getRequest()->query->all());
        $order = $this->getRequest()->query->get('order', '');
        $count = Endereco::count($condition);
        $pager = new \Pager($count, $limit, $page);
        $enderecos = Endereco::findAll($condition, $order, $limit, $pager->offset);
        $itens = [];
        foreach ($enderecos as $endereco) {
            $itens[] = $endereco->publish(app()->auth->provider);
        }
        return $this->getResponse()->success(['items' => $itens, 'pages' => $pager->pageCount]);
    }

    /**
     * Create a new Endereço
     * @Post("/api/enderecos", name="api_endereco_add")
     */
    public function add()
    {
        $this->needPermission([Permissao::NOME_CADASTROBAIRROS]);
        $localized = $this->getRequest()->query->getBoolean('localized', false);
        $endereco = new Endereco($this->getData());
        $endereco->filter(new Endereco(), app()->auth->provider, $localized);
        $endereco->insert();
        return $this->getResponse()->success(['item' => $endereco->publish(app()->auth->provider)]);
    }

    /**
     * Modify parts of an existing Endereço
     * @Patch("/api/enderecos/{id}", name="api_endereco_update", params={ "id": "\d+" })
     *
     * @param int $id Endereço id
     */
    public function modify($id)
    {
        $this->needPermission([Permissao::NOME_CADASTROBAIRROS]);
        $old_endereco = Endereco::findOrFail(['id' => $id]);
        $localized = $this->getRequest()->query->getBoolean('localized', false);
        $data = $this->getData($old_endereco->toArray());
        $endereco = new Endereco($data);
        $endereco->filter($old_endereco, app()->auth->provider, $localized);
        $endereco->update();
        $old_endereco->clean($endereco);
        return $this->getResponse()->success(['item' => $endereco->publish(app()->auth->provider)]);
    }

    /**
     * Delete existing Endereço
     * @Delete("/api/enderecos/{id}", name="api_endereco_delete", params={ "id": "\d+" })
     *
     * @param int $id Endereço id to delete
     */
    public function delete($id)
    {
        $this->needPermission([Permissao::NOME_CADASTROBAIRROS]);
        $endereco = Endereco::findOrFail(['id' => $id]);
        $endereco->delete();
        $endereco->clean(new Endereco());
        return $this->getResponse()->success([]);
    }
}
