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
 * Endereço detalhado de um cliente
 */
class LocalizacaoApiController extends \MZ\Core\ApiController
{
    /**
     * Find all Localizações
     * @Get("/api/localizacoes", name="api_localizacao_find")
     */
    public function find()
    {
        $this->needPermission([Permissao::NOME_CADASTROCLIENTES]);
        $limit = max(1, min(100, $this->getRequest()->query->getInt('limit', 10)));
        $page = max(1, $this->getRequest()->query->getInt('page', 1));
        $condition = Filter::query($this->getRequest()->query->all());
        $order = $this->getRequest()->query->get('order', '');
        $count = Localizacao::count($condition);
        $pager = new \Pager($count, $limit, $page);
        $localizacoes = Localizacao::findAll($condition, $order, $limit, $pager->offset);
        $itens = [];
        foreach ($localizacoes as $localizacao) {
            $itens[] = $localizacao->publish(app()->auth->provider);
        }
        return $this->getResponse()->success(['items' => $itens, 'pages' => $pager->pageCount]);
    }

    /**
     * Create a new Localização
     * @Post("/api/localizacoes", name="api_localizacao_add")
     */
    public function add()
    {
        $this->needPermission([Permissao::NOME_CADASTROCLIENTES]);
        $localized = $this->getRequest()->query->getBoolean('localized', false);
        $localizacao = new Localizacao($this->getData());
        $localizacao->filter(new Localizacao(), app()->auth->provider, $localized);
        $localizacao->insert();
        return $this->getResponse()->success(['item' => $localizacao->publish(app()->auth->provider)]);
    }

    /**
     * Modify parts of an existing Localização
     * @Patch("/api/localizacoes/{id}", name="api_localizacao_update", params={ "id": "\d+" })
     *
     * @param int $id Localização id
     */
    public function modify($id)
    {
        $this->needPermission([Permissao::NOME_CADASTROCLIENTES]);
        $old_localizacao = Localizacao::findOrFail(['id' => $id]);
        $localized = $this->getRequest()->query->getBoolean('localized', false);
        $data = $this->getData($old_localizacao->toArray());
        $localizacao = new Localizacao($data);
        $localizacao->filter($old_localizacao, app()->auth->provider, $localized);
        $localizacao->update();
        $old_localizacao->clean($localizacao);
        if ($localizacao->getClienteID() == app()->getSystem()->getCompany()->getID() &&
            !app()->auth->has([Permissao::NOME_ALTERARCONFIGURACOES])
        ) {
            return $this->getResponse()->error();
        }
    }

    /**
     * Delete existing Localização
     * @Delete("/api/localizacoes/{id}", name="api_localizacao_delete", params={ "id": "\d+" })
     *
     * @param int $id Localização id to delete
     */
    public function delete($id)
    {
        $this->needPermission([Permissao::NOME_CADASTROCLIENTES]);
        $localizacao = Localizacao::findOrFail(['id' => $id]);
        $localizacao->delete();
        $localizacao->clean(new Localizacao());
        return $this->getResponse()->success([]);
    }
}
