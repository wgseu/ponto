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
class ContaPageController extends \MZ\Core\Controller
{
    public function find()
    {
        need_permission(Permissao::NOME_CADASTROCONTAS, is_output('json'));

        $limite = isset($_GET['limite']) ? intval($_GET['limite']) : 10;
        if ($limite > 100 || $limite < 1) {
            $limite = 10;
        }
        $condition = Filter::query($_GET);
        unset($condition['ordem']);
        $conta = new Conta($condition);
        $order = Filter::order(isset($_GET['ordem']) ? $_GET['ordem'] : '');
        $count = Conta::count($condition);
        list($pagesize, $offset, $pagination) = pagestring($count, $limite);
        $contas = Conta::findAll($condition, $order, $pagesize, $offset);

        if (is_output('json')) {
            $items = [];
            foreach ($contas as $_conta) {
                $items[] = $_conta->publish();
            }
            json(['status' => 'ok', 'items' => $items]);
        }

        $_cliente = $conta->findClienteID();
        $_classificacao = $conta->findClassificacaoID();
        $classificacoes = Classificacao::findAll();
        $_classificacoes = [];
        foreach ($classificacoes as $classificacao) {
            $_classificacoes[$classificacao->getID()] = $classificacao->getDescricao();
        }
        return $this->view('gerenciar_conta_index', get_defined_vars());
    }

    public function add()
    {
        need_permission(Permissao::NOME_CADASTROCONTAS, is_output('json'));
        $id = isset($_GET['id']) ? $_GET['id'] : null;
        $conta = Conta::findByID($id);
        $conta->setID(null);
        $conta->setVencimento(DB::now());
        $conta->setDataEmissao(DB::now());
        $conta->setDataPagamento(null);

        $focusctrl = 'descricao';
        $errors = [];
        $old_conta = $conta;
        if (is_post()) {
            $conta = new Conta($_POST);
            try {
                $old_conta->setFuncionarioID(logged_employee()->getID());
                $despesa = isset($_POST['tipo']) ? $_POST['tipo'] < 0 : false;
                $conta->filter($old_conta, $despesa);
                $conta->insert();
                $old_conta->clean($conta);
                $msg = sprintf(
                    'Conta "%s" cadastrada com sucesso!',
                    $conta->getDescricao()
                );
                if (is_output('json')) {
                    json(null, ['item' => $conta->publish(), 'msg' => $msg]);
                }
                \Thunder::success($msg, true);
                redirect('/gerenciar/conta/');
            } catch (\Exception $e) {
                $conta->clean($old_conta);
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
        $classificacao_id_obj = $conta->findClassificacaoID();
        $sub_classificacao_id_obj = $conta->findSubClassificacaoID();
        return $this->view('gerenciar_conta_cadastrar', get_defined_vars());
    }

    public function update()
    {
        need_permission(Permissao::NOME_CADASTROCONTAS, is_output('json'));
        $id = isset($_GET['id']) ? $_GET['id'] : null;
        $conta = Conta::findByID($id);
        if (!$conta->exists()) {
            $msg = 'A conta não foi informada ou não existe';
            if (is_output('json')) {
                json($msg);
            }
            \Thunder::warning($msg);
            redirect('/gerenciar/conta/');
        }
        $focusctrl = 'descricao';
        $errors = [];
        $old_conta = $conta;
        if (is_post()) {
            $conta = new Conta($_POST);
            try {
                $despesa = isset($_POST['tipo']) ? $_POST['tipo'] < 0 : false;
                $conta->filter($old_conta, $despesa);
                $conta->update();
                $old_conta->clean($conta);
                $msg = sprintf(
                    'Conta "%s" atualizada com sucesso!',
                    $conta->getDescricao()
                );
                if (is_output('json')) {
                    json(null, ['item' => $conta->publish(), 'msg' => $msg]);
                }
                \Thunder::success($msg, true);
                redirect('/gerenciar/conta/');
            } catch (\Exception $e) {
                $conta->clean($old_conta);
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

        $classificacao_id_obj = $conta->findClassificacaoID();
        $sub_classificacao_id_obj = $conta->findSubClassificacaoID();
        return $this->view('gerenciar_conta_editar', get_defined_vars());
    }

    public function delete()
    {
        need_permission(Permissao::NOME_CADASTROCONTAS, is_output('json'));
        $id = isset($_GET['id']) ? $_GET['id'] : null;
        $conta = Conta::findByID($id);
        if (!$conta->exists()) {
            $msg = 'A conta não foi informada ou não existe';
            if (is_output('json')) {
                json($msg);
            }
            \Thunder::warning($msg);
            redirect('/gerenciar/conta/');
        }
        try {
            $conta->delete();
            $conta->clean(new Conta());
            $msg = sprintf('Conta "%s" excluída com sucesso!', $conta->getDescricao());
            if (is_output('json')) {
                json('msg', $msg);
            }
            \Thunder::success($msg, true);
        } catch (\Exception $e) {
            $msg = sprintf(
                'Não foi possível excluir a conta "%s"!',
                $conta->getDescricao()
            );
            if (is_output('json')) {
                json($msg);
            }
            \Thunder::error($msg);
        }
        redirect('/gerenciar/conta/');
    }

    /**
     * Get URL patterns associated with callback for use into router
     * @return array List of routes
     */
    public static function getRoutes()
    {
        return [
            [
                'name' => 'conta_find',
                'path' => '/gerenciar/conta/',
                'method' => 'GET',
                'controller' => 'find',
            ],
            [
                'name' => 'conta_add',
                'path' => '/gerenciar/conta/cadastrar',
                'method' => ['GET', 'POST'],
                'controller' => 'add',
            ],
            [
                'name' => 'conta_update',
                'path' => '/gerenciar/conta/editar',
                'method' => ['GET', 'POST'],
                'controller' => 'update',
            ],
            [
                'name' => 'conta_delete',
                'path' => '/gerenciar/conta/excluir',
                'method' => 'GET',
                'controller' => 'delete',
            ],
        ];
    }
}
