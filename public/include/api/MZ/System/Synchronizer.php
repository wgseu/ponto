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

class Synchronizer
{
    const ACTION_ADDED = 'adicao_itens';
    const ACTION_DELETED = 'remocao_itens';
    const ACTION_OPEN = 'abertura';
    const ACTION_CLOSE = 'fechamento';
    const ACTION_INFO = 'informacoes';
    const ACTION_STATE = 'estado';
    const ACTION_PAY = 'pagamento';
    const ACTION_RESERVED = 'reserva';
    const ACTION_FREE = 'liberacao';

    private $socket;

    public function __construct()
    {
        // open socket
        $this->socket = @fsockopen('127.0.0.1', 6219, $errno, $errstr);
        if ($this->socket === false) {
            throw new \Exception('O servidor do GrandChef está fechado', $errno);
        }
    }

    public function __destruct()
    {
        // close socket
        @fclose($this->socket);
    }

    private function write($message)
    {
        // send message to server
        $result = @fwrite($this->socket, $message);
        if ($result === false) {
            throw new \Exception('Falha ao notificar ao servidor do GrandChef');
        }
    }

    private function read()
    {
        $result = @fgets($this->socket);
        if ($result === false) {
            throw new \Exception('Falha ao receber uma notificação do servidor do GrandChef');
        }
        return $result;
    }

    public function send($data = [])
    {
        $cmd = json_encode($data);
        $length = strlen($cmd);
        $bytes = pack('N', $length);
        $this->write($bytes);
        $this->write($cmd);
    }

    public function updateOrder($id, $tipo, $mesa_id, $comanda_id, $action)
    {
        $this->send(['cmd' => 'pedidos', 'id' => $id, 'tipo' => $tipo, 'mesa' => intval($mesa_id), 'comanda' => intval($comanda_id), 'action' => $action]);
    }

    public function updateAuth($funcionario_id, $dispositivo_nome)
    {
        $this->send(['cmd' => 'autenticacao', 'funcionario' => $funcionario_id, 'dispositivo' => $dispositivo_nome]);
    }

    public function printOrder($pedido_id, $funcionario_id)
    {
        $this->send(['cmd' => 'imprimir', 'pedido' => intval($pedido_id), 'funcionario' => $funcionario_id, 'action' => 'conta']);
    }

    public function printServices($pedido_id)
    {
        $this->send(['cmd' => 'imprimir', 'pedido' => intval($pedido_id), 'action' => 'servicos']);
    }

    public function printQueue($pedido_id)
    {
        $this->send(['cmd' => 'imprimir', 'pedido' => intval($pedido_id), 'action' => 'senha']);
    }

    public function deviceAdded($device_name, $caixa_id)
    {
        $this->send(['cmd' => 'dispositivos', 'nome' => $device_name, 'caixa' => intval($caixa_id), 'action' => 'criacao']);
    }

    public function deviceUpdated($device_name, $caixa_id)
    {
        $this->send(['cmd' => 'dispositivos', 'nome' => $device_name, 'caixa' => intval($caixa_id), 'action' => 'alteracao']);
    }

    public function printOptionsChanged()
    {
        $this->send(['cmd' => 'sistema', 'action' => 'impressao_opcoes']);
    }

    public function systemOptionsChanged()
    {
        $this->send(['cmd' => 'sistema', 'action' => 'sistema_info']);
    }

    public function enterpriseChanged()
    {
        $this->send(['cmd' => 'sistema', 'action' => 'empresa_info']);
    }

    public function integratorChanged()
    {
        $this->send(['cmd' => 'integracao', 'action' => 'alteracao']);
    }

    public function invoiceAdded($nota_id, $pedido_id)
    {
        $this->send(['cmd' => 'nota', 'id' => intval($nota_id), 'pedido' => intval($pedido_id), 'action' => 'criacao']);
    }

    public function invoiceRun($nota_id, $pedido_id)
    {
        $this->send(['cmd' => 'nota', 'id' => intval($nota_id), 'pedido' => intval($pedido_id), 'action' => 'executar']);
    }
}
