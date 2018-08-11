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
namespace MZ\Product;

use MZ\System\Permissao;
use MZ\Util\Filter;

/**
 * Allow application to serve system resources
 */
class UnidadePageController extends \MZ\Core\Controller
{
    public function view()
    {
    }

    public function find()
    {
        need_manager(is_output('json'));

        need_permission(Permissao::NOME_CADASTROPRODUTOS, is_output('json'));

        $limite = isset($_GET['limite']) ? intval($_GET['limite']) : 10;
        if ($limite > 100 || $limite < 1) {
            $limite = 10;
        }
        $condition = Filter::query($_GET);
        unset($condition['ordem']);
        $unidade = new Unidade($condition);
        $order = Filter::order(isset($_GET['ordem']) ? $_GET['ordem'] : '');
        $count = Unidade::count($condition);
        list($pagesize, $offset, $pagination) = pagestring($count, $limite);
        $unidades = Unidade::findAll($condition, $order, $pagesize, $offset);

        if (is_output('json')) {
            $items = [];
            foreach ($unidades as $_unidade) {
                $items[] = $_unidade->publish();
            }
            json(['status' => 'ok', 'items' => $items]);
        }

        return $app->getResponse()->output('gerenciar_unidade_index');
    }

    public function add()
    {
        need_manager(is_output('json'));

        need_permission(Permissao::NOME_CADASTROPRODUTOS, is_output('json'));
        $id = isset($_GET['id']) ? $_GET['id'] : null;
        $unidade = Unidade::findByID($id);
        $unidade->setID(null);

        $focusctrl = 'nome';
        $errors = [];
        $old_unidade = $unidade;
        if (is_post()) {
            $unidade = new Unidade($_POST);
            try {
                $unidade->filter($old_unidade);
                $unidade->insert();
                $old_unidade->clean($unidade);
                $msg = sprintf(
                    'Unidade "%s" cadastrada com sucesso!',
                    $unidade->getNome()
                );
                if (is_output('json')) {
                    json(null, ['item' => $unidade->publish(), 'msg' => $msg]);
                }
                \Thunder::success($msg, true);
                redirect('/gerenciar/unidade/');
            } catch (\Exception $e) {
                $unidade->clean($old_unidade);
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
        return $app->getResponse()->output('gerenciar_unidade_cadastrar');
    }

    public function update()
    {
        need_manager(is_output('json'));

        need_permission(Permissao::NOME_CADASTROPRODUTOS, is_output('json'));
        $id = isset($_GET['id']) ? $_GET['id'] : null;
        $unidade = Unidade::findByID($id);
        if (!$unidade->exists()) {
            $msg = 'A unidade não foi informada ou não existe';
            if (is_output('json')) {
                json($msg);
            }
            \Thunder::warning($msg);
            redirect('/gerenciar/unidade/');
        }
        $focusctrl = 'nome';
        $errors = [];
        $old_unidade = $unidade;
        if (is_post()) {
            $unidade = new Unidade($_POST);
            try {
                $unidade->filter($old_unidade);
                $unidade->update();
                $old_unidade->clean($unidade);
                $msg = sprintf(
                    'Unidade "%s" atualizada com sucesso!',
                    $unidade->getNome()
                );
                if (is_output('json')) {
                    json(null, ['item' => $unidade->publish(), 'msg' => $msg]);
                }
                \Thunder::success($msg, true);
                redirect('/gerenciar/unidade/');
            } catch (\Exception $e) {
                $unidade->clean($old_unidade);
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
        return $app->getResponse()->output('gerenciar_unidade_editar');
    }

    public function delete()
    {
        need_manager(is_output('json'));

        need_permission(Permissao::NOME_CADASTROPRODUTOS, is_output('json'));
        $id = isset($_GET['id']) ? $_GET['id'] : null;
        $unidade = Unidade::findByID($id);
        if (!$unidade->exists()) {
            $msg = 'A unidade não foi informada ou não existe';
            if (is_output('json')) {
                json($msg);
            }
            \Thunder::warning($msg);
            redirect('/gerenciar/unidade/');
        }
        try {
            $unidade->delete();
            $unidade->clean(new Unidade());
            $msg = sprintf('Unidade "%s" excluída com sucesso!', $unidade->getNome());
            if (is_output('json')) {
                json('msg', $msg);
            }
            \Thunder::success($msg, true);
        } catch (\Exception $e) {
            $msg = sprintf(
                'Não foi possível excluir a unidade "%s"',
                $unidade->getNome()
            );
            if (is_output('json')) {
                json($msg);
            }
            \Thunder::error($msg);
        }
        redirect('/gerenciar/unidade/');
    }

    /**
     * Get URL patterns associated with callback for use into router
     * @return array List of routes
     */
    public static function getRoutes()
    {
        return [
            [
                'name' => 'unidade_view',
                'path' => '/unidade/',
                'method' => 'GET',
                'controller' => 'view',
            ],
            [
                'name' => 'unidade_find',
                'path' => '/gerenciar/unidade/',
                'method' => 'GET',
                'controller' => 'find',
            ],
            [
                'name' => 'unidade_add',
                'path' => '/gerenciar/unidade/cadastrar',
                'method' => ['GET', 'POST'],
                'controller' => 'add',
            ],
            [
                'name' => 'unidade_update',
                'path' => '/gerenciar/unidade/editar',
                'method' => ['GET', 'POST'],
                'controller' => 'update',
            ],
            [
                'name' => 'unidade_delete',
                'path' => '/gerenciar/unidade/excluir',
                'method' => 'GET',
                'controller' => 'delete',
            ],
        ];
    }
}
