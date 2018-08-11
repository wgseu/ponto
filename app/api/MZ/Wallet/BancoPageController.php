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
namespace MZ\Wallet;

use MZ\System\Permissao;
use MZ\Util\Filter;

/**
 * Allow application to serve system resources
 */
class BancoPageController extends \MZ\Core\Controller
{
    public function view()
    {
    }

    public function find()
    {
        need_manager(is_output('json'));

        need_permission(Permissao::NOME_CADASTROBANCOS, is_output('json'));

        $limite = isset($_GET['limite']) ? intval($_GET['limite']) : 10;
        if (!is_numeric($limite) || $limite > 100 || $limite < 1) {
            $limite = 10;
        }
        $condition = Filter::query($_GET);
        unset($condition['ordem']);
        $banco = new Banco($condition);
        $order = Filter::order(isset($_GET['ordem']) ? $_GET['ordem'] : '');
        $count = Banco::count($condition);
        list($pagesize, $offset, $pagination) = pagestring($count, $limite);
        $bancos = Banco::findAll($condition, $order, $pagesize, $offset);

        if (is_output('json')) {
            $items = [];
            foreach ($bancos as $_banco) {
                $items[] = $_banco->publish();
            }
            json('items', $items);
        }

        return $app->getResponse()->output('gerenciar_banco_index');
    }

    public function add()
    {
        need_manager(is_output('json'));

        need_permission(Permissao::NOME_CADASTROBANCOS, is_output('json'));
        $focusctrl = 'numero';
        $errors = [];
        $banco = new Banco();
        $old_banco = $banco;
        if (is_post()) {
            $banco = new Banco($_POST);
            try {
                $banco->filter($old_banco);
                $banco->insert();
                $msg = sprintf(
                    'Banco "%s" cadastrado com sucesso!',
                    $banco->getRazaoSocial()
                );
                if (is_output('json')) {
                    json(null, ['item' => $banco->publish(), 'msg' => $msg]);
                }
                \Thunder::success($msg, true);
                redirect('/gerenciar/banco/');
            } catch (\Exception $e) {
                $banco->clean($old_banco);
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
        return $app->getResponse()->output('gerenciar_banco_cadastrar');
    }

    public function update()
    {
        need_manager(is_output('json'));

        need_permission(Permissao::NOME_CADASTROBANCOS, is_output('json'));
        $id = isset($_GET['id']) ? $_GET['id'] : null;
        $banco = Banco::findByID($id);
        if (!$banco->exists()) {
            $msg = 'O banco informado não existe!';
            if (is_output('json')) {
                json($msg);
            }
            \Thunder::warning($msg);
            redirect('/gerenciar/banco/');
        }
        $focusctrl = 'razaosocial';
        $errors = [];
        $old_banco = $banco;
        if (is_post()) {
            $banco = new Banco($_POST);
            try {
                $banco->filter($old_banco);
                $banco->update();
                $msg = sprintf(
                    'Banco "%s" atualizado com sucesso!',
                    $banco->getRazaoSocial()
                );
                if (is_output('json')) {
                    json(null, ['item' => $banco->publish(), 'msg' => $msg]);
                }
                \Thunder::success($msg, true);
                redirect('/gerenciar/banco/');
            } catch (\Exception $e) {
                $banco->clean($old_banco);
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
        return $app->getResponse()->output('gerenciar_banco_editar');
    }

    public function delete()
    {
        need_manager(is_output('json'));

        need_permission(Permissao::NOME_CADASTROBANCOS, is_output('json'));
        $id = isset($_GET['id']) ? $_GET['id'] : null;
        $banco = Banco::findByID($id);
        if (!$banco->exists()) {
            $msg = 'O banco não foi informado ou não existe!';
            if (is_output('json')) {
                json($msg);
            }
            \Thunder::warning($msg);
            redirect('/gerenciar/banco/');
        }
        try {
            $banco->delete();
            $banco->clean(new Banco());
            $msg = sprintf('Banco "%s" excluído com sucesso!', $banco->getRazaoSocial());
            if (is_output('json')) {
                json('msg', $msg);
            }
            \Thunder::success($msg, true);
        } catch (\Exception $e) {
            $msg = sprintf(
                'Não foi possível excluir o banco "%s"!',
                $banco->getRazaoSocial()
            );
            if (is_output('json')) {
                json($msg);
            }
            \Thunder::error($msg);
        }
        redirect('/gerenciar/banco/');
    }

    /**
     * Get URL patterns associated with callback for use into router
     * @return array List of routes
     */
    public static function getRoutes()
    {
        return [
            [
                'name' => 'banco_view',
                'path' => '/banco/',
                'method' => 'GET',
                'controller' => 'view',
            ],
            [
                'name' => 'banco_find',
                'path' => '/gerenciar/banco/',
                'method' => 'GET',
                'controller' => 'find',
            ],
            [
                'name' => 'banco_add',
                'path' => '/gerenciar/banco/cadastrar',
                'method' => ['GET', 'POST'],
                'controller' => 'add',
            ],
            [
                'name' => 'banco_update',
                'path' => '/gerenciar/banco/editar',
                'method' => ['GET', 'POST'],
                'controller' => 'update',
            ],
            [
                'name' => 'banco_delete',
                'path' => '/gerenciar/banco/excluir',
                'method' => 'GET',
                'controller' => 'delete',
            ],
        ];
    }
}
