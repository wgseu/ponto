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
namespace MZ\Account;

use MZ\System\Permissao;
use MZ\Database\DB;
use MZ\Util\Filter;
use MZ\Core\ApiController;

/**
 * Contas a pagar e ou receber
 */
class ContaApiController extends \MZ\Core\ApiController
{
    /**
     * Find all Contas
     * @Get("/api/contas", name="api_conta_find")
     */
    public function find()
    {
        $this->needPermission([Permissao::NOME_CADASTROCONTAS]);
        $limit = max(1, min(100, $this->getRequest()->query->getInt('limit', 10)));
        $page = max(1, $this->getRequest()->query->getInt('page', 1));
        $condition = Filter::query($this->getRequest()->query->all());
        unset($condition['ordem']);
        $order = $this->getRequest()->query->get('order', '');
        $count = Conta::count($condition);
        $pager = new \Pager($count, $limit, $page);
        $contas = Conta::findAll($condition, $order, $limit, $pager->offset);
        $itens = [];
        foreach ($contas as $conta) {
            $itens[] = $conta->publish(app()->auth->provider);
        }
        return $this->getResponse()->success(['items' => $itens, 'pages' => $pager->pageCount]);
    }

    /**
     * Create a new Conta
     * @Post("/api/contas", name="api_conta_add")
     */
    public function add()
    {
        $this->needPermission([Permissao::NOME_CADASTROCONTAS]);
        $localized = $this->getRequest()->query->getBoolean('localized', false);
        $conta = new Conta($this->getData());
        $conta->setID(null);
        $conta->setVencimento(DB::now());
        $conta->setDataEmissao(DB::now());
        $conta->filter(new Conta(), app()->auth->provider, $localized);
        $conta->insert();
        return $this->getResponse()->success(['item' => $conta->publish(app()->auth->provider)]);
    }

    /**
     * Modify parts of an existing Conta
     * @Patch("/api/contas/{id}", name="api_conta_update", params={ "id": "\d+" })
     *
     * @param int $id Conta id
     */
    public function modify($id)
    {
        $this->needPermission([Permissao::NOME_CADASTROCONTAS]);
        $old_conta = Conta::findOrFail(['id' => $id]);
        $localized = $this->getRequest()->query->getBoolean('localized', false);
        $data = $this->getData($old_conta->toArray());
        $conta = new Conta($data);
        $conta->filter($old_conta, app()->auth->provider, $localized);
        $conta->update();
        $old_conta->clean($conta);
        return $this->getResponse()->success(['item' => $conta->publish(app()->auth->provider)]);
    }

    /**
     * Delete existing Conta
     * @Delete("/api/contas/{id}", name="api_conta_delete", params={ "id": "\d+" })
     *
     * @param int $id Conta id to delete
     */
    public function delete($id)
    {
        $this->needPermission([Permissao::NOME_CADASTROCONTAS]);
        $conta = Conta::findOrFail(['id' => $id]);
        $conta->delete();
        $conta->clean(new Conta());
        return $this->getResponse()->success([]);
    }
}
