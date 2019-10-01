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
use MZ\Provider\Prestador;
use MZ\Exception\AuthorizationException;
use Symfony\Component\HttpFoundation\Response;
use MZ\Util\Filter;

/**
 * Telefones dos clientes, apenas o telefone principal deve ser único por
 * cliente
 */
class TelefoneApiController extends \MZ\Core\ApiController
{
    /**
     * Find all Telefones
     * @Get("/api/telefones", name="api_telefone_find")
     */
    public function find()
    {
        $this->needPermission([Permissao::NOME_CADASTROCLIENTES]);
        $limit = max(1, min(100, $this->getRequest()->query->getInt('limit', 10)));
        $page = max(1, $this->getRequest()->query->getInt('page', 1));
        $condition = Filter::query($this->getRequest()->query->all());
        $order = $this->getRequest()->query->get('order', '');
        $count = Telefone::count($condition);
        $pager = new \Pager($count, $limit, $page);
        $telefones = Telefone::findAll($condition, $order, $limit, $pager->offset);
        $itens = [];
        foreach ($telefones as $telefone) {
            $itens[] = $telefone->publish(app()->auth->provider);
        }
        return $this->getResponse()->success(['items' => $itens, 'pages' => $pager->pageCount]);
    }

    /**
     * Create a new Telefone
     * @Post("/api/telefones", name="api_telefone_add")
     */
    public function add()
    {
        app()->needLogin();
        $localized = $this->getRequest()->query->getBoolean('localized', false);
        $telefone = new Telefone($this->getData());
        $telefone->filter(new Telefone(), app()->auth->provider, $localized);
        $other_user = $telefone->getClienteID() != app()->auth->user->getID();
        if ($other_user && !app()->auth->has([Permissao::NOME_CADASTROCLIENTES])) {
            $this->needPermission([Permissao::NOME_CADASTROCLIENTES]);
        }
        $prestador = Prestador::findByClienteID($telefone->getClienteID());
        if ($other_user && !app()->auth->isOwner() && $prestador->exists()) {
            throw new AuthorizationException(
                _t('need_owner'),
                Response::HTTP_FORBIDDEN,
                $permissions
            );
        }
        $telefone->insert();
        return $this->getResponse()->success(['item' => $telefone->publish(app()->auth->provider)]);
    }

    /**
     * Modify parts of an existing Telefone
     * @Patch("/api/telefones/{id}", name="api_telefone_update", params={ "id": "\d+" })
     *
     * @param int $id Telefone id
     */
    public function modify($id)
    {
        $this->needPermission([Permissao::NOME_CADASTROCLIENTES]);
        $old_telefone = Telefone::findOrFail(['id' => $id]);
        $localized = $this->getRequest()->query->getBoolean('localized', false);
        $data = array_merge($old_telefone->toArray(), $this->getData());
        $telefone = new Telefone($data);
        $telefone->filter($old_telefone, app()->auth->provider, $localized);
        $telefone->update();
        $old_telefone->clean($telefone);
        return $this->getResponse()->success(['item' => $telefone->publish(app()->auth->provider)]);
    }

    /**
     * Delete existing Telefone
     * @Delete("/api/telefones/{id}", name="api_telefone_delete", params={ "id": "\d+" })
     *
     * @param int $id Telefone id to delete
     */
    public function delete($id)
    {
        $this->needPermission([Permissao::NOME_CADASTROCLIENTES]);
        $telefone = Telefone::findOrFail(['id' => $id]);
        $telefone->delete();
        $telefone->clean(new Telefone());
        return $this->getResponse()->success([]);
    }
}
