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
namespace MZ\Session;

use MZ\System\Permissao;
use MZ\Util\Filter;
use MZ\Core\PageController;

/**
 * Allow application to serve system resources
 */
class MovimentacaoPageController extends PageController
{
    public function find()
    {
        $this->needPermission([Permissao::NOME_ABRIRCAIXA]);

        $limite = max(1, min(100, $this->getRequest()->query->getInt('limite', 10)));
        $condition = Filter::query($this->getRequest()->query->all());
        unset($condition['ordem']);
        $movimentacao = new Movimentacao($condition);
        $order = Filter::order($this->getRequest()->query->get('ordem', ''));
        $count = Movimentacao::count($condition);
        $page = max(1, $this->getRequest()->query->getInt('pagina', 1));
        $pager = new \Pager($count, $limite, $page, 'pagina');
        $pagination = $pager->genPages();
        $movimentacoes = Movimentacao::findAll($condition, $order, $limite, $pager->offset);

        if ($this->isJson()) {
            $items = [];
            foreach ($movimentacoes as $_movimentacao) {
                $items[] = $_movimentacao->publish(app()->auth->provider);
            }
            return $this->json()->success(['items' => $items]);
        }

        $_movimentacao_icon = [
            'Y' => 0,
            'N' => 16,
        ];

        $estados = [
            'Y' => 'Aberto',
            'N' => 'Fechado',
        ];
        $caixas = Caixa::findAll();
        $_caixa_names = [];
        foreach ($caixas as $caixa) {
            $_caixa_names[$caixa->getID()] = $caixa->getDescricao();
        }
        $_funcionario = $movimentacao->findIniciadorID();
        return $this->view('gerenciar_movimentacao_index', get_defined_vars());
    }

    /**
     * Get URL patterns associated with callback for use into router
     * @return array List of routes
     */
    public static function getRoutes()
    {
        return [
            [
                'name' => 'movimentacao_find',
                'path' => '/gerenciar/movimentacao/',
                'method' => 'GET',
                'controller' => 'find',
            ],
        ];
    }
}
