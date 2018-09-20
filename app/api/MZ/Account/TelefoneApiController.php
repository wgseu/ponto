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
use MZ\Util\Filter;

/**
 * Allow application to serve system resources
 */
class TelefoneApiController extends \MZ\Core\ApiController
{
    /**
     * Find all Telefones
     */
    public function find()
    {
        $this->needPermission(Permissao::NOME_CADASTROCLIENTES);
        $limit = max(1, min(100, $this->getRequest()->query->getInt('limit', 10)));
        $page = max(1, $this->getRequest()->query->getInt('page', 1));
        $condition = Filter::query($this->getRequest()->query->all());
        $order = $this->getRequest()->query->get('order', '');
        $count = Telefone::count($condition);
        $pager = new \Pager($count, $limit, $page);
        $telefones = Telefone::findAll($condition, $order, $limit, $pager->offset);
        $itens = [];
        foreach ($telefones as $telefone) {
            $itens[] = $telefone->publish();
        }
        return $this->getResponse()->success(['items' => $itens, 'pages' => $pager->pageCount]);
    }

    /**
     * Create a new Telefone
     */
    public function add()
    {
        $this->needPermission(Permissao::NOME_CADASTROCLIENTES);
        $telefone = new Telefone($this->getJsonParams());
        $telefone->filter(new Telefone());
        $telefone->insert();
        $message = _t('telefone.registered', $telefone->getNumero());
        return $this->getResponse()->success(['item' => $telefone->publish()], $message);
    }

    /**
     * Update an existing Telefone
     * @param int $id Telefone id to update
     */
    public function update($id)
    {
        $this->needPermission(Permissao::NOME_CADASTROCLIENTES);
        $old_telefone = Telefone::findOrFail(['id' => $id]);
        $telefone = new Telefone($this->getJsonParams());
        $telefone->filter($old_telefone);
        $telefone->update();
        $old_telefone->clean($telefone);
        $message = _t('telefone.updated', $telefone->getNumero());
        return $this->getResponse()->success(['item' => $telefone->publish()], $message);
    }

    /**
     * Delete existing Telefone
     * @param int $id Telefone id to delete
     */
    public function delete($id)
    {
        $this->needPermission(Permissao::NOME_CADASTROCLIENTES);
        $telefone = Telefone::findOrFail(['id' => $id]);
        $telefone->delete();
        $telefone->clean(new Telefone());
        $message = _t('telefone.deleted', $telefone->getNumero());
        return $this->getResponse()->success([], $message);
    }

    /**
     * Get URL patterns associated with callback for use into router
     * @return array List of routes
     */
    public static function getRoutes()
    {
        return [
            [
                'name' => 'api_telefone_find',
                'path' => '/api/telefones',
                'method' => 'GET',
                'controller' => 'find',
            ],
            [
                'name' => 'api_telefone_add',
                'path' => '/api/telefones',
                'method' => 'POST',
                'controller' => 'add',
            ],
            [
                'name' => 'api_telefone_update',
                'path' => '/api/telefones/{id}',
                'method' => 'PUT',
                'requirements' => ['id' => '\d+'],
                'controller' => 'update',
            ],
            [
                'name' => 'api_telefone_delete',
                'path' => '/api/telefones/{id}',
                'method' => 'DELETE',
                'requirements' => ['id' => '\d+'],
                'controller' => 'delete',
            ],
        ];
    }
}
