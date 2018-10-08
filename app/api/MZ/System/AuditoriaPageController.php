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
namespace MZ\System;

use MZ\Util\Filter;
use MZ\Core\PageController;
use MZ\Provider\Prestador;

/**
 * Allow application to serve system resources
 */
class AuditoriaPageController extends PageController
{
    public function find()
    {
        $this->needPermission([Permissao::NOME_RELATORIOAUDITORIA]);

        $limite = max(1, min(100, $this->getRequest()->query->getInt('limite', 10)));
        $condition = Filter::query($this->getRequest()->query->all());
        unset($condition['ordem']);
        $auditoria = new Auditoria($condition);
        $order = Filter::order($this->getRequest()->query->get('ordem', ''));
        $count = Auditoria::count($condition);
        $page = max(1, $this->getRequest()->query->getInt('pagina', 1));
        $pager = new \Pager($count, $limite, $page, 'pagina');
        $pagination = $pager->genPages();
        $auditorias = Auditoria::findAll($condition, $order, $limite, $pager->offset);

        if ($this->isJson()) {
            $items = [];
            foreach ($auditorias as $_auditoria) {
                $items[] = $_auditoria->publish();
            }
            return $this->json()->success(['items' => $items]);
        }

        $prestadores = Prestador::findAll();
        $_funcionario_names = [];
        foreach ($prestadores as $prestador) {
            $_cliente = $prestador->findClienteID();
            $_funcionario_names[$prestador->getID()] = $_cliente->getLogin();
        }

        $_funcionario = $auditoria->findPrestadorID();
        $_tipo_names = Auditoria::getTipoOptions();
        $_prioridade_names = Auditoria::getPrioridadeOptions();
        $_prioridade_classe = [
            'Baixa' => '',
            'Media' => 'warning',
            'Alta' => 'danger'
        ];
        $_tipo_icon = [
            'Financeiro' => 0,
            'Administrativo' => 16
        ];
        return $this->view('gerenciar_auditoria_index', get_defined_vars());
    }

    /**
     * Get URL patterns associated with callback for use into router
     * @return array List of routes
     */
    public static function getRoutes()
    {
        return [
            [
                'name' => 'auditoria_find',
                'path' => '/gerenciar/auditoria/',
                'method' => 'GET',
                'controller' => 'find',
            ],
        ];
    }
}
