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
namespace MZ\Integrator;

use Curl\Curl;
use MZ\Employee\Funcionario;
use MZ\Sale\Pedido;
use MZ\System\Task;
use MZ\Association\Order;
use MZ\Association\Product;

/**
 * Kromax Service and Task
 */
class Kromax extends Task
{
    const NAME = 'kromax';

    const CARDS = [
        'RAM' => ['name' => 'AMERICAN EXPRESS (Crédito)'],
        'DNREST' => ['name' => 'DINERS (Crédito)'],
        'REC' => ['name' => 'ELO (Crédito)'],
        'RHIP' => ['name' => 'HIPERCARD (Crédito)'],
        'RDREST' => ['name' => 'MASTERCARD (Crédito)'],
        'VSREST' => ['name' => 'VISA (Crédito)'],
        'RED' => ['name' => 'ELO (Débito)'],
        'MEREST' => ['name' => 'MASTERCARD (Débito)'],
        'VIREST' => ['name' => 'VISA (Débito)'],
        'VVREST' => ['name' => 'ALELO REFEICAO (Vale)'],
        'RSODEX' => ['name' => 'SODEXO (Vale)'],
        'TRE' => ['name' => 'TICKET RESTAURANTE (Vale)'],
        'VALECA' => ['name' => 'VALE CARD (Vale)'],
        'VR_SMA' => ['name' => 'VR SMART (Vale)'],
        'AM' => ['name' => 'AMEX (Online)'],
        'DNR' => ['name' => 'DINERS (Online)'],
        'ELO' => ['name' => 'ELO (Online)'],
        'MC' => ['name' => 'MASTERCARD (Online)'],
        'VIS' => ['name' => 'VISA (Online)']
    ];

    /**
     * Name of the task
     * @return string task name
     */
    public function getName()
    {
        $integracao = $this->getData();
        return $integracao->getNome();
    }

    /**
     * Execute task
     * @return integer Number of pending work
     */
    public function run()
    {
        $dom = $this->request();
        $this->setPending(0);
        $order = new Order();
        $order->setIntegracao($this->getData());
        $order->setCardNames(self::CARDS);
        $order->setEmployee(Funcionario::findByID(1));
        if (!$this->checkReponse($dom, 0)) {
            // TODO atualizar tabela de produtos por outro meio mais rápido
            $product = new Product($this->getData());
            $product->populateFromXML($dom);

            $order->loadDOM($dom);
            $order->search();
            if (!$order->exists()) {
                $order->process();
            }
            $changes = $order->store();
        } else {
            $changes = $order->changes(1);
        }
        $updates = $this->submit($changes);
        if ($order->apply($updates)) {
            $this->setPending(1);
        }
        return $this->getPending();
    }

    private function submit($changes)
    {
        $updates = [];
        foreach ($changes as $change) {
            $this->submitStatus($change['code'], $change['estado']);
            $updates[] = $change;
        }
        return $updates;
    }

    private function initCurl()
    {
        $curl = new Curl();
        $curl->setConnectTimeout(4);
        $curl->setTimeout(6);
        $curl->setXmlDecoder(function ($response) {
            $dom = new \DOMDocument();
            $xml_obj = @$dom->loadXML($response);
            if (!($xml_obj === false)) {
                $response = $dom;
            }
            return $response;
        });
        return $curl;
    }
        
    private function submitStatus($id, $estado)
    {
        // build request
        switch ($estado) {
            case Pedido::ESTADO_AGENDADO:
                $status = 6;
                $mensagem = 'Pedido recebido';
                break;
            case Pedido::ESTADO_ATIVO:
                $status = 0;
                $mensagem = 'Chegou na cozinha';
                break;
            case Pedido::ESTADO_ENTREGA:
                $status = 2;
                $mensagem = 'Motoboy a caminho';
                break;
            case Pedido::ESTADO_FINALIZADO:
                $status = 5;
                $mensagem = 'Encomenda entregue';
                break;
            case Order::ESTADO_CANCELADO:
                $status = 3;
                $mensagem = 'Pedido cancelado';
                break;
            default:
                // status não tratado
                return;
        }
        $dom = new \DOMDocument('1.0', 'UTF-8');
        $tabela_node = $dom->createElement('tabela');
        $updatestatus_node = $dom->createElement('updatestatus');
        $id_node = $updatestatus_node->appendChild($dom->createElement('id'));
        $id_node->appendChild($dom->createTextNode($id));
        $status_node = $updatestatus_node->appendChild($dom->createElement('status'));
        $status_node->appendChild($dom->createTextNode($status));
        $mensagem_node = $updatestatus_node->appendChild($dom->createElement('mensagem'));
        $mensagem_node->appendChild($dom->createTextNode($mensagem));
        $tabela_node->appendChild($updatestatus_node);
        $dom->appendChild($tabela_node);
        // send request
        $integracao = $this->getData();
        $url = $this->checkURL('/update_status.php?passe=' . $integracao->getSecret());
        $curl = $this->initCurl();
        $curl->setHeader('Content-Type', 'application/xml');
        $curl->put($url, $dom->saveXML());
        if ($curl->error) {
            throw new \Exception($curl->errorMessage, $curl->errorCode);
        }
        if (!($curl->response instanceof \DOMDocument)) {
            throw new \Exception('A resposta da submissão não é um XML', 401);
        }
        $dom = $curl->response;
        if (!$this->checkReponse($curl->response, $status)) {
            throw new \Exception('A resposta da submissão não é um retorno válido', 401);
        }
        return $curl->response;
    }

    private function checkURL($append_url)
    {
        $integracao = $this->getData();
        $url_base = $integracao->getToken();
        if (!preg_match('/^http[s]?:\/\//', $url_base)) {
            throw new \Exception(
                sprintf('Token com URL inválida para o integrador "%s"', $integracao->getNome()),
                401
            );
        }
        return $url_base . $append_url;
    }

    private function request()
    {
        $integracao = $this->getData();
        $url = $this->checkURL('/tele.php?passe=' . $integracao->getSecret());
        $curl = $this->initCurl();
        $curl->get($url);
        if ($curl->error) {
            throw new \Exception($curl->errorMessage, $curl->errorCode);
        }
        if (!($curl->response instanceof \DOMDocument)) {
            throw new \Exception('A resposta de novos pedidos não é um XML', 401);
        }
        return $curl->response;
    }

    private function checkReponse($dom, $expected_code = 0)
    {
        $root = $dom->documentElement;
        if (is_null($root)) {
            throw new \Exception('Resposta inválida', 401);
        }
        if ($root->nodeName != 'retorno') {
            return false;
        }
        $mensagem = $root->getElementsByTagName('mensagem');
        $status = $root->getElementsByTagName('status');
        if ($mensagem->length == 0 || $status->length == 0) {
            throw new \Exception('Resposta inválida', 401);
        }
        $code = intval($status->item(0)->nodeValue);
        if ($code != $expected_code) {
            throw new \Exception($mensagem->item(0)->nodeValue, $code);
        }
        return true;
    }
}
