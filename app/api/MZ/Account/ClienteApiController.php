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
 * Informações de cliente físico ou jurídico. Clientes, empresas,
 * funcionários, fornecedores e parceiros são cadastrados aqui
 */
class ClienteApiController extends \MZ\Core\ApiController
{
    /**
     * Find all Clientes
     * @Get("/api/clientes", name="api_cliente_find")
     */
    public function find()
    {
        $this->needPermission([Permissao::NOME_CADASTROCLIENTES]);
        $limit = max(1, min(100, $this->getRequest()->query->getInt('limit', 10)));
        $page = max(1, $this->getRequest()->query->getInt('page', 1));
        $condition = Filter::query($this->getRequest()->query->all());
        unset($condition['ordem']);
        $genero = isset($condition['genero']) ? $condition['genero'] : null;
        if ($genero == 'Empresa') {
            $condition['tipo'] = Cliente::TIPO_JURIDICA;
            unset($condition['genero']);
        }
        $order = $this->getRequest()->query->get('order', '');
        $count = Cliente::count($condition);
        $pager = new \Pager($count, $limit, $page);
        $clientes = Cliente::findAll($condition, $order, $limit, $pager->offset);
        $itens = [];
        foreach ($clientes as $cliente) {
            $itens[] = $cliente->publish(app()->auth->provider);
        }
        return $this->getResponse()->success(['items' => $itens, 'pages' => $pager->pageCount]);
    }

    /**
     * Create a new Cliente
     * @Post("/api/clientes", name="api_cliente_add")
     */
    public function add()
    {
        $this->needPermission([Permissao::NOME_CADASTROCLIENTES]);
        $localized = $this->getRequest()->query->getBoolean('localized', false);
        $cliente = new Cliente($this->getData());
        $aceitar = null;
        $aceitar = $this->getRequest()->request->get('aceitar');
        if ($aceitar != 'true') {
            throw new \MZ\Exception\ValidationException(
                ['aceitar' => 'Os termos não foram aceitos']
            );
        }
        $senha = $this->getRequest()->request->get('confirmarsenha', '');
        $cliente->passwordMatch($senha);
        $cliente->filter(new Cliente(), app()->auth->provider, $localized);
        $cliente->getTelefone()->setPaisID(app()->getSystem()->getCountry()->getID());
        $cliente->getTelefone()->filter($old_cliente->getTelefone(), app()->auth->provider, true);
        $cliente->insert();
        return $this->getResponse()->success(['item' => $cliente->publish(app()->auth->provider)]);
    }

    /**
     * Modify parts of an existing Cliente
     * @Patch("/api/clientes/{id}", name="api_cliente_update", params={ "id": "\d+" })
     *
     * @param int $id Cliente id
     */
    public function modify($id)
    {
        app()->needManager();
        $old_cliente = Cliente::findOrFail(['id' => $id]);
        $localized = $this->getRequest()->query->getBoolean('localized', false);
        $data = $this->getData($old_cliente->toArray());
        $cliente = new Cliente($data);
        $cliente->setEmail($old_cliente->getEmail());
        $cliente->setTipo($old_cliente->getTipo());
        $cliente->setSlogan($old_cliente->getSlogan());
        $senha = $this->getRequest()->request->get('confirmarsenha', '');
        $cliente->passwordMatch($senha);
        $cliente->filter($old_cliente, app()->auth->provider, $localized);
        $cliente->getTelefone()->setPaisID(app()->getSystem()->getCountry()->getID());
        $cliente->getTelefone()->filter($old_cliente->getTelefone(), app()->auth->provider, true);
        $cliente->update();
        $old_cliente->clean($cliente);
        return $this->getResponse()->success(['item' => $cliente->publish(app()->auth->provider)]);
    }

    /**
     * Delete existing Cliente
     * @Delete("/api/clientes/{id}", name="api_cliente_delete", params={ "id": "\d+" })
     *
     * @param int $id Cliente id to delete
     */
    public function delete($id)
    {
        $this->needPermission([Permissao::NOME_CADASTROCLIENTES]);
        $cliente = Cliente::findOrFail(['id' => $id]);
        $cliente->delete();
        $cliente->clean(new Cliente());
        return $this->getResponse()->success([]);
    }
}
