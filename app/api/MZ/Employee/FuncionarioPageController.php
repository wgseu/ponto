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
namespace MZ\Employee;

use MZ\System\Permissao;
use MZ\Database\DB;
use MZ\Account\Cliente;
use MZ\Util\Filter;

/**
 * Allow application to serve system resources
 */
class FuncionarioPageController extends \MZ\Core\Controller
{
    public function find()
    {
        need_manager(is_output('json'));

        need_manager();

        $limite = isset($_GET['limite']) ? intval($_GET['limite']) : 10;
        if ($limite > 100 || $limite < 1) {
            $limite = 10;
        }
        $condition = Filter::query($_GET);
        unset($condition['ordem']);
        if (!logged_employee()->has(Permissao::NOME_CADASTROFUNCIONARIOS)) {
            $condition['id'] = logged_employee()->getID();
        }
        $funcionario = new Funcionario($condition);
        $order = Filter::order(isset($_GET['ordem']) ? $_GET['ordem'] : '');
        $count = Funcionario::count($condition);
        list($pagesize, $offset, $pagination) = pagestring($count, $limite);
        $funcionarios = Funcionario::findAll($condition, $order, $pagesize, $offset);

        if (is_output('json')) {
            $items = [];
            foreach ($funcionarios as $_funcionario) {
                $items[] = $_funcionario->publish();
            }
            return $this->json()->success(['items' => $items]);
        }

        $funcao = $funcionario->findFuncaoID();
        $estado = isset($_GET['estado']) ? $_GET['estado'] : null;
        if ($estado == 'ativo') {
            $estado = 'Y';
        } elseif ($estado == 'inativo') {
            $estado = 'N';
        } else {
            $estado = null;
        }
        $funcoes = [];
        $_funcoes = Funcao::findAll();
        foreach ($_funcoes as $funcao) {
            $funcoes[$funcao->getID()] = $funcao->getDescricao();
        }
        $generos = Cliente::getGeneroOptions();
        $estados = [
            'Y' => 'Ativo',
            'N' => 'Inativo',
        ];
        return $this->view('gerenciar_funcionario_index', get_defined_vars());
    }

    public function add()
    {
        need_permission(Permissao::NOME_CADASTROFUNCIONARIOS, is_output('json'));
        $id = isset($_GET['id']) ? $_GET['id'] : null;
        $funcionario = Funcionario::findByID($id);
        $funcionario->setID(null);

        $focusctrl = 'funcaoid';
        $errors = [];
        $old_funcionario = $funcionario;
        if (is_post()) {
            $funcionario = new Funcionario($_POST);
            try {
                $funcionario->filter($old_funcionario);
                $funcionario->insert();
                $old_funcionario->clean($funcionario);
                $cliente = $funcionario->findClienteID();
                $msg = sprintf(
                    'Funcionário "%s" cadastrado com sucesso!',
                    $cliente->getAssinatura()
                );
                if (is_output('json')) {
                    return $this->json()->success(['item' => $cliente->publish()], $msg);
                }
                \Thunder::success($msg, true);
                return $this->redirect('/gerenciar/funcionario/');
            } catch (\Exception $e) {
                $funcionario->clean($old_funcionario);
                if ($e instanceof \MZ\Exception\ValidationException) {
                    $errors = $e->getErrors();
                }
                if (is_output('json')) {
                    return $this->json()->error($e->getMessage(), null, $errors);
                }
                \Thunder::error($e->getMessage());
                foreach ($errors as $key => $value) {
                    $focusctrl = $key;
                    break;
                }
            }
        } elseif (is_output('json')) {
            return $this->json()->error('Nenhum dado foi enviado');
        } elseif (is_null($funcionario->getClienteID())) {
            $funcionario->setAtivo('Y');
        }
        $cliente_id_obj = $funcionario->findClienteID();
        $_funcoes = Funcao::findAll();
        $linguagens = get_languages_info();
        return $this->view('gerenciar_funcionario_cadastrar', get_defined_vars());
    }

    public function update()
    {
        need_manager(is_output('json'));

        need_manager();
        $id = isset($_GET['id']) ? $_GET['id'] : null;
        $funcionario = Funcionario::findByID($id);
        if (!$funcionario->exists()) {
            $msg = 'O funcionário não foi informado ou não existe';
            if (is_output('json')) {
                return $this->json()->error($msg);
            }
            \Thunder::warning($msg);
            return $this->redirect('/gerenciar/funcionario/');
        }
        if ((
                !logged_employee()->has(Permissao::NOME_CADASTROFUNCIONARIOS) &&
                !is_self($funcionario)
            ) ||
            (
                $funcionario->has(Permissao::NOME_CADASTROFUNCIONARIOS) &&
                !is_self($funcionario) &&
                !is_owner()
            )
        ) {
            $msg = 'Você não tem permissão para alterar as informações desse(a) funcionário(a)';
            if (is_output('json')) {
                return $this->json()->error($msg);
            }
            \Thunder::warning($msg);
            return $this->redirect('/gerenciar/funcionario/');
        }
        $cliente_func = $funcionario->findClienteID();
        $focusctrl = 'clienteid';
        $errors = [];
        $old_funcionario = $funcionario;
        if (is_post()) {
            $funcionario = new Funcionario($_POST);
            try {
                $funcionario->filter($old_funcionario);
                $funcionario->update();
                $old_funcionario->clean($funcionario);
                $msg = sprintf(
                    'Funcionário(a) "%s" atualizado(a) com sucesso!',
                    $cliente_func->getAssinatura()
                );
                if (is_output('json')) {
                    return $this->json()->success(['item' => $cliente_func->publish()], $msg);
                }
                \Thunder::success($msg, true);
                return $this->redirect('/gerenciar/funcionario/');
            } catch (\Exception $e) {
                $funcionario->clean($old_funcionario);
                if ($e instanceof \MZ\Exception\ValidationException) {
                    $errors = $e->getErrors();
                }
                if (is_output('json')) {
                    return $this->json()->error($e->getMessage(), null, $errors);
                }
                \Thunder::error($e->getMessage());
                foreach ($errors as $key => $value) {
                    $focusctrl = $key;
                    break;
                }
            }
        } elseif (is_output('json')) {
            return $this->json()->error('Nenhum dado foi enviado');
        }
        $_funcoes = Funcao::findAll();
        $linguagens = get_languages_info();
        return $this->view('gerenciar_funcionario_editar', get_defined_vars());
    }

    public function delete()
    {
        need_permission(Permissao::NOME_CADASTROFUNCIONARIOS, is_output('json'));
        $id = isset($_GET['id']) ? $_GET['id'] : null;
        $funcionario = Funcionario::findByID($id);
        if (!$funcionario->exists()) {
            $msg = 'O funcionário não foi informado ou não existe';
            if (is_output('json')) {
                return $this->json()->error($msg);
            }
            \Thunder::warning($msg);
            return $this->redirect('/gerenciar/funcionario/');
        }
        $cliente = $funcionario->findClienteID();
        try {
            $funcionario->delete();
            $funcionario->clean(new Funcionario());
            $msg = sprintf('Funcionário "%s" excluído com sucesso!', $cliente->getAssinatura());
            if (is_output('json')) {
                return $this->json()->success([], $msg);
            }
            \Thunder::success($msg, true);
        } catch (\Exception $e) {
            $msg = sprintf(
                'Não foi possível excluir o funcionário "%s"',
                $cliente->getAssinatura()
            );
            if (is_output('json')) {
                return $this->json()->error($msg);
            }
            \Thunder::error($msg);
        }
        return $this->redirect('/gerenciar/funcionario/');
    }

    /**
     * Get URL patterns associated with callback for use into router
     * @return array List of routes
     */
    public static function getRoutes()
    {
        return [
            [
                'name' => 'funcionario_find',
                'path' => '/gerenciar/funcionario/',
                'method' => 'GET',
                'controller' => 'find',
            ],
            [
                'name' => 'funcionario_add',
                'path' => '/gerenciar/funcionario/cadastrar',
                'method' => ['GET', 'POST'],
                'controller' => 'add',
            ],
            [
                'name' => 'funcionario_update',
                'path' => '/gerenciar/funcionario/editar',
                'method' => ['GET', 'POST'],
                'controller' => 'update',
            ],
            [
                'name' => 'funcionario_delete',
                'path' => '/gerenciar/funcionario/excluir',
                'method' => 'GET',
                'controller' => 'delete',
            ],
        ];
    }
}