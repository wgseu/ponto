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
use MZ\Database\DB;
use MZ\Session\Sessao;
use MZ\Sale\Pedido;

/**
 * Allow application to serve system resources
 */
class PagamentoOldApiController extends \MZ\Core\ApiController
{
    public function billing()
    {
        if (!app()->getAuthentication()->isLogin()) {
            return $this->json()->error('Usuário não autenticado!');
        }
        if (!app()->auth->has([Permissao::NOME_RELATORIOCAIXA])) {
            return $this->json()->error('Você não tem permissão para acessar o faturamento da empresa');
        }
        $response = [];
        $mes = abs($this->getRequest()->query->getInt('mes'));
        $meses = [];
        for ($i = $mes; $i < $mes + 4; $i++) {
            $data = DB::date("first day of -$i month");
            $meses[] = [
                'mes' => human_date($data, true),
                'total' => Pagamento::getFaturamento([
                    'apartir_datalancamento' => $data,
                    'ate_datalancamento' => DB::now("last day of -$i month 23:59:59")
                ])
            ];
        }
        $response['mensal'] = $meses;
        return $this->json()->success($response);
    }

    public function statement()
    {
        if (!app()->getAuthentication()->isLogin()) {
            return $this->json()->error('Usuário não autenticado!');
        }
        if (!app()->auth->has([Permissao::NOME_RELATORIOCAIXA])) {
            return $this->json()->error('Você não tem permissão para acessar o resumo de valores');
        }
        $sessao = Sessao::findLastAberta();
        $data_inicio = !$sessao->exists() ? DB::date('today') : null;
        $response = [];
        $response['vendas'] = Pedido::fetchTotal($sessao->getID(), $data_inicio);
        $response['receitas'] = Pagamento::getReceitas(
            ['sessaoid' => $sessao->getID(), 'apartir_datalancamento' => $data_inicio]
        );
        $response['despesas'] = Pagamento::getDespesas(
            ['sessaoid' => $sessao->getID(), 'apartir_datalancamento' => $data_inicio]
        );
        $response['faturamento']['atual'] = Pagamento::getFaturamento(
            ['apartir_datalancamento' => DB::date('first day of this month')]
        );
        $response['faturamento']['base'] = Pagamento::getFaturamento([
            'apartir_datalancamento' => DB::date('first day of last month'),
            'ate_datalancamento' => DB::now('-1 month')
        ]);
        $response['faturamento']['estimado'] = round(($response['faturamento']['atual'] / date('j')) * date('t'), 4);
        $response['faturamento']['anterior'] = Pagamento::getFaturamento([
            'apartir_datalancamento' => DB::date('first day of last month'),
            'ate_datalancamento' => DB::now('-1 sec today first day of this month')
        ]);
        $response['faturamento']['restante'] = $response['faturamento']['anterior'] - $response['faturamento']['atual'];
        if ($response['faturamento']['anterior'] < 0.01) {
            $response['faturamento']['alcancado'] = 100;
            $response['faturamento']['metrica'] = 100;
        } else {
            $response['faturamento']['alcancado'] = round(
                ($response['faturamento']['atual'] / $response['faturamento']['anterior']) * 100,
                2
            );
            $response['faturamento']['metrica'] = round(
                ($response['faturamento']['base'] / $response['faturamento']['anterior']) * 100,
                2
            );
        }
        $response['faturamento']['pagamentos'] = Pagamento::rawFindAllTotal(
            [
                'apartir_datalancamento' => DB::date('first day of this month'),
                '!pedidoid' => null
            ],
            ['forma_tipo' => true]
        );
        $mes = abs(intval($this->getRequest()->query->get('mes')));
        $meses = [];
        for ($i = $mes; $i < $mes + 4; $i++) {
            $data = DB::date("first day of -$i month");
            $meses[] = [
                'mes' => human_date($data, true),
                'total' => Pagamento::getFaturamento([
                    'apartir_datalancamento' => $data,
                    'ate_datalancamento' => DB::now("last day of -$i month 23:59:59")
                ])
            ];
        }
        $response['faturamento']['mensal'] = $meses;
        return $this->json()->success($response);
    }

    public function actions()
    {
        app()->needOwner();
        $action = $this->getRequest()->query->get('action');
        if ($action == 'faturamento') {
            $start = strtotime($this->getRequest()->query->get('start'));
            if ($start === false) {
                $start = time();
            }
            $end = strtotime($this->getRequest()->query->get('end'));
            if ($end === false) {
                $end = time();
            }
            if (abs($end - $start) > 60 * 60 * 24 * 90) {
                $end = strtotime('+3 month', $start);
            }
            $faturamentos = Pagamento::rawFindAllTotal(
                [
                    'apartir_datalancamento' => DB::date($start),
                    'ate_datalancamento' => DB::now(strtotime('-1 sec tomorrow', $end)),
                    '!pedidoid' => null
                ],
                ['dia' => true]
            );
            $data = [];
            foreach ($faturamentos as $faturamento) {
                $data[] = ['data' => strtotime($faturamento['data']), 'total' => $faturamento['total']];
            }
            return $this->json()->success(['faturamento' => $data]);
        } elseif ($action == 'meta') {
            $intervalo = strtolower($this->getRequest()->query->get('intervalo'));
            switch ($intervalo) {
                case 'anual':
                    $atual_de = DB::date('first day of jan');
                    $atual_ate = null;
                    $base_de = DB::date('first day of jan last year');
                    $base_ate = DB::now('-1 sec first day of jan');
                    break;
                case 'semanal':
                    $atual_de = DB::date('monday this week');
                    $atual_ate = null;
                    $base_de = DB::date('monday last week');
                    $base_ate = DB::now('-1 sec monday this week');
                    break;
                case 'diaria':
                    $atual_de = DB::date('today');
                    $atual_ate = null;
                    $base_de = DB::date('-1 week');
                    $base_ate = DB::now('-1 sec tomorrow -1 week');
                    break;
                default: // mensal
                    $atual_de = DB::date('first day of this month');
                    $atual_ate = null;
                    $base_de = DB::date('first day of last month');
                    $base_ate = DB::now('-1 sec today first day of this month');
                    break;
            }
            $atual = Pagamento::getFaturamento(['apartir_datalancamento' => $atual_de, 'ate_datalancamento' => $atual_ate]);
            $base  = Pagamento::getFaturamento(['apartir_datalancamento' => $base_de, 'ate_datalancamento' => $base_ate]);
            return $this->json()->success([
                'atual' => $atual,
                'base' => $base,
            ]);
        }
    }

    /**
     * Get URL patterns associated with callback for use into router
     * @return array List of routes
     */
    public static function getRoutes()
    {
        return [
            [
                'name' => 'app_pagamento_billing',
                'path' => '/app/relatorio/faturamento',
                'method' => 'GET',
                'controller' => 'billing',
            ],
            [
                'name' => 'app_pagamento_statement',
                'path' => '/app/relatorio/resumo',
                'method' => 'GET',
                'controller' => 'statement',
            ],
            [
                'name' => 'app_pagamento_actions',
                'path' => '/gerenciar/diversos/relatorio',
                'method' => 'GET',
                'controller' => 'actions',
            ],
        ];
    }
}
