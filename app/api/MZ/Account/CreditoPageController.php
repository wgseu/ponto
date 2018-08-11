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
use MZ\Database\DB;
use MZ\Util\Filter;

/**
 * Allow application to serve system resources
 */
class CreditoPageController extends \MZ\Core\Controller
{
    public function find()
    {
        need_permission(Permissao::NOME_CADASTRARCREDITOS, is_output('json'));

        $limite = isset($_GET['limite']) ? intval($_GET['limite']) : 10;
        if ($limite > 100 || $limite < 1) {
            $limite = 10;
        }
        $condition = Filter::query($_GET);
        unset($condition['ordem']);
        $credito = new Credito($condition);
        $order = Filter::order(isset($_GET['ordem']) ? $_GET['ordem'] : '');
        $count = Credito::count($condition);
        list($pagesize, $offset, $pagination) = pagestring($count, $limite);
        $creditos = Credito::findAll($condition, $order, $pagesize, $offset);

        if (is_output('json')) {
            $items = [];
            foreach ($creditos as $_credito) {
                $items[] = $_credito->publish();
            }
            json(['status' => 'ok', 'items' => $items]);
        }

        $_cliente = $credito->findClienteID();
        $estados = [
            'Y' => 'Cancelados',
            'N' => 'Válidos',
        ];
        return $this->view('gerenciar_credito_index', get_defined_vars());
    }

    public function add()
    {
        need_permission(Permissao::NOME_CADASTRARCREDITOS, is_output('json'));
        $id = isset($_GET['id']) ? $_GET['id'] : null;
        $credito = Credito::findByID($id);
        $credito->setID(null);
        $credito->setDataCadastro(DB::now());

        $focusctrl = 'detalhes';
        $errors = [];
        $old_credito = $credito;
        if (is_post()) {
            $credito = new Credito($_POST);
            try {
                $old_credito->setFuncionarioID(logged_employee()->getID());
                $old_credito->setClienteID($credito->getClienteID());
                $credito->filter($old_credito);
                $credito->insert();
                $old_credito->clean($credito);
                $msg = sprintf(
                    'Crédito "%s" cadastrado com sucesso!',
                    $credito->getDetalhes()
                );
                if (is_output('json')) {
                    json(null, ['item' => $credito->publish(), 'msg' => $msg]);
                }
                \Thunder::success($msg, true);
                redirect('/gerenciar/credito/');
            } catch (\Exception $e) {
                $credito->clean($old_credito);
                if ($e instanceof \MZ\Exception\ValidationException) {
                    $errors = $e->getErrors();
                }
                if (is_output('json')) {
                    json($e->getMessage(), null, ['errors' => $errors]);
                }
                \Thunder::error($e->getMessage());
                foreach ($errors as $key => $value) {
                    $focusctrl = $key;
                    break;
                }
            }
        } elseif (is_output('json')) {
            json('Nenhum dado foi enviado');
        }
        return $this->view('gerenciar_credito_cadastrar', get_defined_vars());
    }

    public function update()
    {
        need_permission(Permissao::NOME_CADASTRARCREDITOS, is_output('json'));
        $id = isset($_GET['id']) ? $_GET['id'] : null;
        $credito = Credito::findByID($id);
        if (!$credito->exists()) {
            $msg = 'O crédito não foi informado ou não existe';
            if (is_output('json')) {
                json($msg);
            }
            \Thunder::warning($msg);
            redirect('/gerenciar/credito/');
        }
        $focusctrl = 'detalhes';
        $errors = [];
        $old_credito = $credito;
        if (is_post()) {
            $credito = new Credito($_POST);
            try {
                $credito->filter($old_credito);
                $credito->update();
                $old_credito->clean($credito);
                $msg = sprintf(
                    'Crédito "%s" atualizado com sucesso!',
                    $credito->getDetalhes()
                );
                if (is_output('json')) {
                    json(null, ['item' => $credito->publish(), 'msg' => $msg]);
                }
                \Thunder::success($msg, true);
                redirect('/gerenciar/credito/');
            } catch (\Exception $e) {
                $credito->clean($old_credito);
                if ($e instanceof \MZ\Exception\ValidationException) {
                    $errors = $e->getErrors();
                }
                if (is_output('json')) {
                    json($e->getMessage(), null, ['errors' => $errors]);
                }
                \Thunder::error($e->getMessage());
                foreach ($errors as $key => $value) {
                    $focusctrl = $key;
                    break;
                }
            }
        } elseif (is_output('json')) {
            json('Nenhum dado foi enviado');
        }
        return $this->view('gerenciar_credito_editar', get_defined_vars());
    }

    public function delete()
    {
        need_permission(Permissao::NOME_CADASTRARCREDITOS, is_output('json'));
        $id = isset($_GET['id']) ? $_GET['id'] : null;
        $credito = Credito::findByID($id);
        if (!$credito->exists()) {
            $msg = 'O crédito não foi informado ou não existe';
            if (is_output('json')) {
                json($msg);
            }
            \Thunder::warning($msg);
            redirect('/gerenciar/credito/');
        }
        try {
            $credito->delete();
            $credito->clean(new Credito());
            $msg = sprintf('Crédito "%s" excluído com sucesso!', $credito->getDetalhes());
            if (is_output('json')) {
                json('msg', $msg);
            }
            \Thunder::success($msg, true);
        } catch (\Exception $e) {
            $msg = sprintf(
                'Não foi possível excluir o crédito "%s"',
                $credito->getDetalhes()
            );
            if (is_output('json')) {
                json($msg);
            }
            \Thunder::error($msg);
        }
        redirect('/gerenciar/credito/');
    }

    public function cancel()
    {
        need_permission(Permissao::NOME_CADASTRARCREDITOS, is_output('json'));
        $id = isset($_GET['id']) ? $_GET['id'] : null;
        $credito = Credito::findByID($id);
        if (!$credito->exists()) {
            $msg = 'O crédito não foi informado ou não existe';
            if (is_output('json')) {
                json($msg);
            }
            \Thunder::warning($msg);
            redirect('/gerenciar/credito/');
        }
        try {
            $credito->cancel();
            $msg = sprintf('Crédito "%s" cancelado com sucesso!', $credito->getDetalhes());
            if (is_output('json')) {
                json('msg', $msg);
            }
            \Thunder::success($msg, true);
        } catch (\Exception $e) {
            $msg = sprintf(
                'Não foi possível cancelar o crédito "%s"',
                $credito->getDetalhes()
            );
            if (is_output('json')) {
                json($msg);
            }
            \Thunder::error($msg);
        }
        redirect('/gerenciar/credito/');
    }

    /**
     * Get URL patterns associated with callback for use into router
     * @return array List of routes
     */
    public static function getRoutes()
    {
        return [
            [
                'name' => 'credito_find',
                'path' => '/gerenciar/credito/',
                'method' => 'GET',
                'controller' => 'find',
            ],
            [
                'name' => 'credito_add',
                'path' => '/gerenciar/credito/cadastrar',
                'method' => ['GET', 'POST'],
                'controller' => 'add',
            ],
            [
                'name' => 'credito_update',
                'path' => '/gerenciar/credito/editar',
                'method' => ['GET', 'POST'],
                'controller' => 'update',
            ],
            [
                'name' => 'credito_delete',
                'path' => '/gerenciar/credito/excluir',
                'method' => 'GET',
                'controller' => 'delete',
            ],
            [
                'name' => 'credito_cancel',
                'path' => '/gerenciar/credito/cancelar',
                'method' => 'GET',
                'controller' => 'cancel',
            ],
        ];
    }
}
