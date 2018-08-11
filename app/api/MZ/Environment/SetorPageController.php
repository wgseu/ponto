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
namespace MZ\Environment;

use MZ\System\Permissao;
use MZ\Util\Filter;

/**
 * Allow application to serve system resources
 */
class SetorPageController extends \MZ\Core\Controller
{
    public function view()
    {
    }

    public function find()
    {
        need_manager(is_output('json'));

        need_permission(Permissao::NOME_ESTOQUE, is_output('json'));

        $limite = isset($_GET['limite']) ? intval($_GET['limite']) : 10;
        if ($limite > 100 || $limite < 1) {
            $limite = 10;
        }
        $condition = Filter::query($_GET);
        unset($condition['ordem']);
        $setor = new Setor($condition);
        $order = Filter::order(isset($_GET['ordem']) ? $_GET['ordem'] : '');
        $count = Setor::count($condition);
        list($pagesize, $offset, $pagination) = pagestring($count, $limite);
        $setores = Setor::findAll($condition, $order, $pagesize, $offset);

        if (is_output('json')) {
            $items = [];
            foreach ($setores as $_setor) {
                $items[] = $_setor->publish();
            }
            json(['status' => 'ok', 'items' => $items]);
        }

        return $app->getResponse()->output('gerenciar_setor_index');
    }

    public function add()
    {
        need_manager(is_output('json'));

        need_permission(Permissao::NOME_ESTOQUE, is_output('json'));
        $id = isset($_GET['id']) ? $_GET['id'] : null;
        $setor = Setor::findByID($id);
        $setor->setID(null);

        $focusctrl = 'nome';
        $errors = [];
        $old_setor = $setor;
        if (is_post()) {
            $setor = new Setor($_POST);
            try {
                $setor->filter($old_setor);
                $setor->insert();
                $old_setor->clean($setor);
                $msg = sprintf(
                    'Setor "%s" cadastrado com sucesso!',
                    $setor->getNome()
                );
                if (is_output('json')) {
                    json(null, ['item' => $setor->publish(), 'msg' => $msg]);
                }
                \Thunder::success($msg, true);
                redirect('/gerenciar/setor/');
            } catch (\Exception $e) {
                $setor->clean($old_setor);
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
        return $app->getResponse()->output('gerenciar_setor_cadastrar');
    }

    public function update()
    {
        need_manager(is_output('json'));

        need_permission(Permissao::NOME_ESTOQUE, is_output('json'));
        $id = isset($_GET['id']) ? $_GET['id'] : null;
        $setor = Setor::findByID($id);
        if (!$setor->exists()) {
            $msg = 'O setor não foi informado ou não existe';
            if (is_output('json')) {
                json($msg);
            }
            \Thunder::warning($msg);
            redirect('/gerenciar/setor/');
        }
        $focusctrl = 'nome';
        $errors = [];
        $old_setor = $setor;
        if (is_post()) {
            $setor = new Setor($_POST);
            try {
                $setor->setID($old_setor->getID());
                $setor->filter($old_setor);
                $setor->update();
                $old_setor->clean($setor);
                $msg = sprintf(
                    'Setor "%s" atualizado com sucesso!',
                    $setor->getNome()
                );
                if (is_output('json')) {
                    json(null, ['item' => $setor->publish(), 'msg' => $msg]);
                }
                \Thunder::success($msg, true);
                redirect('/gerenciar/setor/');
            } catch (\Exception $e) {
                $setor->clean($old_setor);
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
        return $app->getResponse()->output('gerenciar_setor_editar');
    }

    public function delete()
    {
        need_manager(is_output('json'));

        need_permission(Permissao::NOME_ESTOQUE, is_output('json'));
        $id = isset($_GET['id']) ? $_GET['id'] : null;
        $setor = Setor::findByID($id);
        if (!$setor->exists()) {
            $msg = 'O setor não foi informado ou não existe';
            if (is_output('json')) {
                json($msg);
            }
            \Thunder::warning($msg);
            redirect('/gerenciar/setor/');
        }
        try {
            $setor->delete();
            $setor->clean(new Setor());
            $msg = sprintf('Setor "%s" excluído com sucesso!', $setor->getNome());
            if (is_output('json')) {
                json('msg', $msg);
            }
            \Thunder::success($msg, true);
        } catch (\Exception $e) {
            $msg = sprintf(
                'Não foi possível excluir o setor "%s"',
                $setor->getNome()
            );
            if (is_output('json')) {
                json($msg);
            }
            \Thunder::error($msg);
        }
        redirect('/gerenciar/setor/');
    }

    /**
     * Get URL patterns associated with callback for use into router
     * @return array List of routes
     */
    public static function getRoutes()
    {
        return [
            [
                'name' => 'setor_view',
                'path' => '/setor/',
                'method' => 'GET',
                'controller' => 'view',
            ],
            [
                'name' => 'setor_find',
                'path' => '/gerenciar/setor/',
                'method' => 'GET',
                'controller' => 'find',
            ],
            [
                'name' => 'setor_add',
                'path' => '/gerenciar/setor/cadastrar',
                'method' => ['GET', 'POST'],
                'controller' => 'add',
            ],
            [
                'name' => 'setor_update',
                'path' => '/gerenciar/setor/editar',
                'method' => ['GET', 'POST'],
                'controller' => 'update',
            ],
            [
                'name' => 'setor_delete',
                'path' => '/gerenciar/setor/excluir',
                'method' => 'GET',
                'controller' => 'delete',
            ],
        ];
    }
}
