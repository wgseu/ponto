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
namespace MZ\Payment;

use MZ\System\Permissao;
use MZ\Util\Filter;
use MZ\Core\PageController;
use MZ\Wallet\Carteira;
use MZ\Account\Cliente;
use MZ\Sale\Pedido;
use MZ\Session\Sessao;
use MZ\Account\Conta;
use MZ\Sale\Item;
use MZ\Database\DB;

/**
 * Allow application to serve system resources
 */
class PagamentoPageController extends PageController
{
    public function dashboard()
    {
        app()->needManager();
        app()->needOwner();
        
        $data_inicio = strtotime('first day of last month 0:00');
        $data_fim = strtotime('-1 sec tomorrow');
        $faturamentos = Pagamento::rawFindAllTotal(
            [
                'apartir_datahora' => DB::date('first day of last month'),
                '!pedidoid' => null
            ],
            ['dia' => true]
        );
        $apartir_compra = DB::date('first day of -6 month');
        $top_clientes = Cliente::rawFindAll(
            [
                'comprador' => true,
                'apartir_compra' => $apartir_compra
            ],
            [],
            5
        );
        $sessao = Sessao::findLastAberta();
        $pessoas = Pedido::getTotalPessoas($sessao->getID());
        $stats = Pedido::getTicketMedio($sessao->getID());
        $permanencia = $stats['permanencia'];
        $ticket_medio = $stats['total'];
        $receitas = Pagamento::getReceitas(['sessaoid' => $sessao->getID()]);
        $vendas = Pedido::fetchTotal($sessao->getID());
        $faturamento = [];
        $faturamento['atual'] = Pagamento::getFaturamento(
            ['apartir_datahora' => DB::date('first day of this month')]
        );
        $faturamento['anterior'] = Pagamento::getFaturamento([
            'apartir_datahora' => DB::date('first day of last month'),
            'ate_datahora' => DB::now('-1 month')
        ]);
        $faturamento['base'] = Pagamento::getFaturamento([
            'apartir_datahora' => DB::date('first day of last month'),
            'ate_datahora' => DB::now('-1 sec today first day of this month')
        ]);
        $clientes = [];
        $clientes['total'] = Cliente::count();
        $clientes['hoje'] = Cliente::count(['apartir_cadastro' => DB::date()]);
        $despesas = [];
        $despesas['pagas'] = Pagamento::getDespesas(
            ['apartir_datahora' => DB::date('first day of this month')]
        );
        $conta_info = Conta::getTotalAbertas(null, null, -1, null, date('Y-m-d', $data_fim));
        $despesas['apagar'] = $conta_info['despesas'] - $conta_info['pago'];
        $pagamentos = Pagamento::rawFindAllTotal(
            [
                'sessaoid' => $sessao->getID(),
                '!pedidoid' => null
            ],
            ['forma_tipo' => true]
        );
        $condition = [
            'categorizado' => true,
            'cancelado' => 'N'
        ];
        if ($sessao->exists()) {
            $condition['sessaoid'] = $sessao->getID();
        }
        $categorias = Item::rawFindAll(
            $condition,
            [],
            6
        );
        return $this->view('gerenciar_diversos_index', get_defined_vars());
    }

    public function find()
    {
        $this->needPermission([Permissao::NOME_PAGAMENTO]);

        $limite = max(1, min(100, $this->getRequest()->query->getInt('limite', 10)));
        $condition = Filter::query($this->getRequest()->query->all());
        unset($condition['ordem']);
        $pagamento = new Pagamento($condition);
        $order = Filter::order($this->getRequest()->query->get('ordem', ''));
        $count = Pagamento::count($condition);
        $page = max(1, $this->getRequest()->query->getInt('pagina', 1));
        $pager = new \Pager($count, $limite, $page, 'pagina');
        $pagination = $pager->genBasic();
        $pagamentos = Pagamento::findAll($condition, $order, $limite, $pager->offset);

        if ($this->isJson()) {
            $items = [];
            foreach ($pagamentos as $_pagamento) {
                $items[] = $_pagamento->publish();
            }
            return $this->json()->success(['items' => $items]);
        }

        $_tipo_names = Pedido::getTipoOptions();
        $_estado_names = [
            'Valido' => 'Válido',
            'Ativo' => 'Ativo',
            'Espera' => 'Em espera',
            'Cancelado' => 'Cancelado',
        ];

        $_pagamento_icon = [
            'Dinheiro' => 0,
            'Cartao' => 16,
            'Cheque' => 32,
            'Conta' => 48,
            'Credito' => 64,
            'Transferencia' => 80,
        ];

        $formas_de_pagamento = FormaPagto::findAll();
        $_forma_names = [];
        foreach ($formas_de_pagamento as $forma) {
            $_forma_names[$forma->getID()] = $forma->getDescricao();
        }
        $cartoes = Cartao::findAll();
        $_cartao_names = [];
        foreach ($cartoes as $cartao) {
            $_cartao_names[$cartao->getID()] = $cartao->getDescricao();
        }
        $carteiras = Carteira::findAll();
        $_carteira_names = [];
        foreach ($carteiras as $carteira) {
            $_carteira_names[$carteira->getID()] = $carteira->getDescricao();
        }
        $_funcionario = $pagamento->findFuncionarioID();
        return $this->view('gerenciar_pagamento_index', get_defined_vars());
    }

    /**
     * Get URL patterns associated with callback for use into router
     * @return array List of routes
     */
    public static function getRoutes()
    {
        return [
            [
                'name' => 'pagamento_dashboard',
                'path' => '/gerenciar/diversos/',
                'method' => 'GET',
                'controller' => 'dashboard',
            ],
            [
                'name' => 'pagamento_find',
                'path' => '/gerenciar/pagamento/',
                'method' => 'GET',
                'controller' => 'find',
            ],
        ];
    }
}
