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
namespace MZ\Company;

use MZ\System\Permissao;
use MZ\Util\Filter;

/**
 * Informa o horário de funcionamento do estabelecimento
 */
class HorarioApiController extends \MZ\Core\ApiController
{
    /**
     * Find all Horários
     * @Get("/api/horarios", name="api_horario_find")
     */
    public function find()
    {
        $this->needPermission([Permissao::NOME_ALTERARHORARIO]);
        $limit = max(1, min(100, $this->getRequest()->query->getInt('limit', 10)));
        $page = max(1, $this->getRequest()->query->getInt('page', 1));
        $condition = Filter::query($this->getRequest()->query->all());
        $order = $this->getRequest()->query->get('order', '');
        $count = Horario::count($condition);
        $pager = new \Pager($count, $limit, $page);
        $horarios = Horario::findAll($condition, $order, $limit, $pager->offset);
        $itens = [];
        foreach ($horarios as $horario) {
            $itens[] = $horario->publish(app()->auth->provider);
        }
        return $this->getResponse()->success(['items' => $itens, 'pages' => $pager->pageCount]);
    }

    /**
     * Create a new Horário
     * @Post("/api/horarios", name="api_horario_add")
     */
    public function add()
    {
        $this->needPermission([Permissao::NOME_ALTERARHORARIO]);
        $localized = $this->getRequest()->query->getBoolean('localized', false);
        $horario = new Horario($this->getData());
        $horario->filter(new Horario(), app()->auth->provider, $localized);
        $horario->insert();
        return $this->getResponse()->success(['item' => $horario->publish(app()->auth->provider)]);
    }

    /**
     * Modify parts of an existing Horário
     * @Patch("/api/horarios/{id}", name="api_horario_update", params={ "id": "\d+" })
     *
     * @param int $id Horário id
     */
    public function modify($id)
    {
        $this->needPermission([Permissao::NOME_ALTERARHORARIO]);
        $old_horario = Horario::findOrFail(['id' => $id]);
        $localized = $this->getRequest()->query->getBoolean('localized', false);
        $data = $this->getData($old_horario->toArray());
        $horario = new Horario($data);
        $horario->filter($old_horario, app()->auth->provider, $localized);
        $horario->update();
        $old_horario->clean($horario);
        return $this->getResponse()->success(['item' => $horario->publish(app()->auth->provider)]);
    }

    /**
     * Delete existing Horário
     * @Delete("/api/horarios/{id}", name="api_horario_delete", params={ "id": "\d+" })
     *
     * @param int $id Horário id to delete
     */
    public function delete($id)
    {
        $this->needPermission([Permissao::NOME_ALTERARHORARIO]);
        $horario = Horario::findOrFail(['id' => $id]);
        $horario->delete();
        $horario->clean(new Horario());
        return $this->getResponse()->success([]);
    }
}
