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
namespace MZ\Product;

use MZ\System\Permissao;
use MZ\Util\Filter;

/**
 * Allow application to serve system resources
 */
class PacoteApiController extends \MZ\Core\ApiController
{
    /**
     * Find all Pacotes
     * @Get("/api/pacotes", name="api_pacote_find")
     */
    public function find()
    {
        $condition = Filter::query($this->getRequest()->query->all());
        $order = $this->getRequest()->query->get('order', '');
        $pacotes = Pacote::findAll($condition, $order);
        $itens = [];
        foreach ($pacotes as $pacote) {
            $itens[] = $pacote->publish(app()->auth->provider);
        }
        return $this->getResponse()->success(['items' => $itens]);
    }

    /**
     * Create a new Pacote
     * @Post("/api/pacotes", name="api_pacote_add")
     */
    public function add()
    {
        $this->needPermission([Permissao::NOME_CADASTROPRODUTOS]);
        $localized = $this->getRequest()->query->getBoolean('localized', false);
        $pacote = new Pacote($this->getData());
        $pacote->filter(new Pacote(), app()->auth->provider, $localized);
        $pacote->insert();
        return $this->getResponse()->success(['item' => $pacote->publish(app()->auth->provider)]);
    }

    /**
     * Modify parts of an existing Pacote
     * @Patch("/api/pacotes/{id}", name="api_pacote_update", params={ "id": "\d+" })
     * 
     * @param int $id Pacote id
     */
    public function modify($id)
    {
        $this->needPermission([Permissao::NOME_CADASTROPRODUTOS]);
        $old_pacote = Pacote::findOrFail(['id' => $id]);
        $localized = $this->getRequest()->query->getBoolean('localized', false);
        $data = array_merge($old_pacote->toArray(), $this->getData());
        $pacote = new Pacote($data);
        $pacote->filter($old_pacote, app()->auth->provider, $localized);
        $pacote->update();
        $old_pacote->clean($pacote);
        return $this->getResponse()->success(['item' => $pacote->publish(app()->auth->provider)]);
    }

    /**
     * Delete existing Pacote
     * @Delete("/api/pacotes/{id}", name="api_pacote_delete", params={ "id": "\d+" })
     * 
     * @param int $id Pacote id to delete
     */
    public function delete($id)
    {
        $this->needPermission([Permissao::NOME_CADASTROPRODUTOS]);
        $pacote = Pacote::findOrFail(['id' => $id]);
        $pacote->delete();
        $pacote->clean(new Pacote());
        return $this->getResponse()->success([]);
    }
}
