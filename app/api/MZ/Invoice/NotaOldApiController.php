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
namespace MZ\Invoice;

use MZ\System\Permissao;
use MZ\Util\Filter;
use MZ\System\Permissao;
use MZ\Util\Filter;
use MZ\System\Permissao;
use MZ\Sale\Pedido;

use MZ\Sale\Pedido;
use MZ\Session\Caixa;
use MZ\System\Permissao;

/**
 * Allow application to serve system resources
 */
class NotaOldApiController extends \MZ\Core\ApiController
{
    public function view()
    {
        $app = $this->getApplication();        <?php

        need_permission(
            [
                Permissao::NOME_PAGAMENTO, ['||'],
                Permissao::NOME_SELECIONARCAIXA, ['||'],
                Permissao::NOME_RELATORIOPEDIDOS
            ],
            true
        );

        try {
            $pedido_id = isset($_GET['pedidoid']) ? $_GET['pedidoid'] : null;
            $pedido = Pedido::findByID($pedido_id);
            if (!$pedido->exists()) {
                throw new \Exception('O pedido não foi informado ou não existe', 404);
            }
            $_nota = Nota::findValida($pedido->getID());
            if (!$_nota->exists()) {
                throw new \Exception('Não existe nota para o pedido informado', 404);
            }
            if (!$_nota->isAutorizada()) {
                throw new \Exception('A nota desse pedido ainda não foi autorizada', 500);
            }
            $nfe_api = new \NFeAPI();
            $nfe_api->init();
            $xmlfile = \NFeDB::getCaminhoXmlAtual($_nota);
            $nota = new \NFe\Core\NFCe();
            $nota->load($xmlfile);
            json('nota', $nota->toArray(true));
        } catch (\Exception $e) {
            \Log::error($e->getMessage());
            json($e->getMessage());
        }
    }

    public function download()
    {
        $app = $this->getApplication();        <?php

        need_permission(
            [
                Permissao::NOME_RELATORIOFLUXO,
                Permissao::NOME_EXCLUIRPEDIDO,
            ],
            true
        );

        set_time_limit(0);

        try {
            $condition = Filter::query($_GET);
            $notas = Nota::findAll($condition);
            if (count($notas) == 0) {
                throw new \Exception('Nenhuma nota no resultado da busca', 404);
            }
            $nfe_api = new \NFeAPI();
            $nfe_api->init();
            if (count($notas) == 1) {
                $_nota = current($notas);
                $xmlfile = $_nota->getCaminhoXml();
                if (!is_array($xmlfile)) {
                    $xmlname = basename($xmlfile);
                    header('Content-Type: application/xml; charset=utf-8');
                    header("Content-Disposition: attachment; filename*=UTF-8''" . rawurlencode($xmlname));
                    readfile($xmlfile);
                    exit;
                }
            }
            $zipfile = Nota::zip($notas);
            $zipname = 'notas.zip';
            header("Pragma: public");
            header("Expires: 0");
            header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
            header("Cache-Control: public");
            header("Content-Description: File Transfer");
            header("Content-Type: application/octet-stream");
            header("Content-Disposition: attachment; filename*=UTF-8''" . rawurlencode($zipname));
            header("Content-Transfer-Encoding: binary");
            header("Content-Length: ".filesize($zipfile));
            readfile($zipfile);
            unlink($zipfile);
        } catch (\Exception $e) {
            json($e->getMessage());
        }
    }

    public function send()
    {
        $app = $this->getApplication();        <?php

        need_permission(
            Permissao::NOME_PAGAMENTO,
            true
        );

        set_time_limit(0);

        try {
            $condition = Filter::query($_GET);
            $notas = Nota::findAll($condition);
            $nota = new Nota($condition);

            $modo = isset($condition['modo']) ? $condition['modo'] : null;

            $emissao_inicio = isset($condition['apartir_emissao']) ?
                strtotime(Filter::datetime($condition['apartir_emissao'])) : null;
            $emissao_fim = isset($condition['ate_emissao']) ?
                strtotime(Filter::datetime($condition['ate_emissao'])) : null;
            $lancamento_inicio = isset($condition['apartir_lancamento']) ?
                strtotime(Filter::datetime($condition['apartir_lancamento'])) : null;
            $lancamento_fim = isset($condition['ate_lancamento']) ?
                strtotime(Filter::datetime($condition['ate_lancamento'])) : null;

            if (!in_array($modo, ['contador', 'consumidor'])) {
                throw new \Exception('O modo de envio informado é inválido', 500);
            }
            if (count($notas) == 0) {
                throw new \Exception('Nenhuma nota no resultado da busca', 404);
            }
            if (count($notas) > 1 && $modo == 'consumidor') {
                throw new \Exception('Apenas uma nota por vez pode ser enviada para um consumidor', 500);
            }
            $_nota = current($notas);
            $nfe_api = new \NFeAPI();
            $nfe_api->init();
            $destinatario = $nfe_api->getExternalEmitente()->findContadorID();
            if ($modo == 'consumidor') {
                $pedido = $_nota->findPedidoID();
                $destinatario = $pedido->findClienteID();
            }
            if (!$destinatario->exists()) {
                if ($modo == 'contador') {
                    throw new \Exception('O contador não foi informado nas configurações do emitente', 500);
                } else {
                    throw new \Exception('O consumidor não foi informado no pedido', 500);
                }
            }
            if (!check_email($destinatario->getEmail())) {
                if ($modo == 'contador') {
                    throw new \Exception('O E-mail do contador não foi informado no cadastro', 500);
                } else {
                    throw new \Exception('O E-mail do consumidor não foi informado no cadastro', 500);
                }
            }
            $sufixo = [];
            $filters = [];
            if ($emissao_inicio || $emissao_fim) {
                $sufixo[] = 'emissão '.human_range($emissao_inicio, $emissao_fim, '-');
                $filters['Período de emissão'] = human_range($emissao_inicio, $emissao_fim);
            }
            if ($lancamento_inicio || $lancamento_fim) {
                $sufixo[] = 'lançamento '.human_range($lancamento_inicio, $lancamento_fim, '-');
                $filters['Período de lançamento'] = human_range($lancamento_inicio, $lancamento_fim);
            }
            if (is_numeric($nota->getSerie())) {
                $filters['Série'] = intval($nota->getSerie());
            }
            if (trim($nota->getAmbiente()) != '') {
                $filters['Ambiente'] = Nota::getAmbienteOptions($nota->getAmbiente());
            }
            if ($nota->isContingencia()) {
                $filters['Contingência'] = 'Sim';
            }
            if (trim($nota->getEstado()) != '') {
                $sufixo[] = Nota::getEstadoOptions($nota->getEstado());
                $filters['Estado da nota'] = Nota::getEstadoOptions($nota->getEstado());
            }
            if (count($notas) == 1) {
                $xmlfile = $_nota->getCaminhoXml();
                if (!is_array($xmlfile)) {
                    $xmlname = basename($xmlfile);
                    mail_nota($destinatario->getEmail(), $destinatario->getNome(), $modo, $filters, [$xmlname => $xmlfile]);
                    json(null, []);
                }
            }
            need_permission(
                [
                    Permissao::NOME_RELATORIOFLUXO,
                    Permissao::NOME_EXCLUIRPEDIDO,
                ],
                is_output('json')
            );
            $sufixo_str = '';
            if (count($sufixo) > 1) {
                $sufixo_str = ' e ' . array_pop($sufixo);
            }
            $sufixo_str = implode(', ', $sufixo) . $sufixo_str;
            if (count($sufixo) > 0) {
                $sufixo_str = ' ' . $sufixo_str;
            }
            $zipfile = Nota::zip($notas);
            $zipname = 'Notas'.$sufixo_str.'.zip';
            try {
                mail_nota($destinatario->getEmail(), $destinatario->getNome(), $modo, $filters, [$zipname => $zipfile]);
            } catch (\Exception $e) {
                unlink($zipfile);
                throw $e;
            }
            unlink($zipfile);
            json(null, []);
        } catch (\Exception $e) {
            json($e->getMessage());
        }
    }

    public function process()
    {
        $app = $this->getApplication();        <?php

        need_permission(Permissao::NOME_ALTERARCONFIGURACOES, true);

        set_time_limit(0);
        $nfe_api = new \NFeAPI();
        $nfe_api->setOffline(isset($_POST['offline_start']) ? $_POST['offline_start'] : null);
        try {
            $nfe_api->init();
            $result = $nfe_api->processa();
            json('result', ['processed' => $result, 'offline_start' => $nfe_api->getOffline()]);
        } catch (\Exception $e) {
            \Log::error($e->getMessage());
            json($e->getMessage());
        }
    }

    public function add()
    {
        need_manager(is_output('json'));
        need_permission([Permissao::NOME_PAGAMENTO, ['||'], Permissao::NOME_SELECIONARCAIXA], true);

        if (!is_post()) {
            json('Nenhum dado foi enviado');
        }

        try {
            $caixa = Caixa::findByID(isset($_POST['caixaid']) ? $_POST['caixaid'] : null);
            if (!$caixa->exists()) {
                throw new \Exception('O caixa informado não existe', 404);
            }
            if (!$caixa->isAtivo()) {
                throw new \Exception(sprintf('O caixa "%s" não está ativo', $caixa->getDescricao()), 500);
            }
            $pedido = Pedido::findByID(isset($_POST['pedidoid']) ? $_POST['pedidoid'] : null);
            if (!$pedido->exists()) {
                throw new \Exception('O pedido informado não existe', 404);
            }
            $emitente = Emitente::findByID('1');
            if (!$emitente->exists()) {
                throw new \Exception('As configurações fiscais do emitente não foram ajustadas', 500);
            }
            $nota = Nota::findByPedidoID($pedido->getID(), true);
            $added = 0;
            if (!$nota->exists()) {
                $nota->setPedidoID($pedido->getID());
                $nota->setSerie($caixa->getSerie());
                $nota->setAmbiente($emitente->getAmbiente());
                $nota = $nota->criarProxima();
                $added = 1;
            }
            if (!$nota->isCorrigido()) {
                $nota->setCorrigido('Y');
                $nota->update();
            }
            $notified = 0;
            try {
                $appsync = new \MZ\System\Synchronizer();
                if ($added) {
                    $appsync->invoiceAdded($nota->getID(), $nota->getPedidoID());
                } else {
                    $appsync->invoiceRun($nota->getID(), $nota->getPedidoID());
                }
                $notified = 1;
            } catch (\Exception $e) {
                \Log::error($e->getMessage());
            }
            json('nota', [
                'id' => $nota->getID(),
                'pedidoid' => $nota->getPedidoID(),
                'notificado' => $notified,
                'adicionado' => $added
            ]);
        } catch (\Exception $e) {
            \Log::error($e->getMessage());
            json($e->getMessage());
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
                'name' => 'nota_download',
                'path' => '/gerenciar/nota/baixar',
                'method' => 'GET',
                'controller' => 'download',
            ],
            [
                'name' => 'nota_add',
                'path' => '/gerenciar/nota/cadastrar',
                'method' => 'POST',
                'controller' => 'add',
            ],
            [
                'name' => 'nota_view',
                'path' => '/gerenciar/nota/danfe',
                'method' => 'GET',
                'controller' => 'view',
            ],
            [
                'name' => 'nota_send',
                'path' => '/gerenciar/nota/enviar',
                'method' => 'GET',
                'controller' => 'send',
            ],
            [
                'name' => 'nota_process',
                'path' => '/gerenciar/nota/processa',
                'method' => 'POST',
                'controller' => 'process',
            ],
        ];
    }
}
