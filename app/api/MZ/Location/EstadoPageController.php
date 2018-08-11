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
namespace MZ\Location;

use MZ\System\Permissao;
use MZ\Util\Filter;

/**
 * Allow application to serve system resources
 */
class EstadoPageController extends \MZ\Core\Controller
{
    public function view()
    {
    }

    public function find()
    {
        need_manager(is_output('json'));

        need_permission(Permissao::NOME_CADASTROESTADOS, is_output('json'));

        $limite = isset($_GET['limite'])?intval($_GET['limite']):10;
        if ($limite > 100 || $limite < 1) {
            $limite = 10;
        }
        $condition = Filter::query($_GET);
        unset($condition['ordem']);
        $estado = new Estado($condition);
        $order = Filter::order(isset($_GET['ordem'])?$_GET['ordem']:'');
        $count = Estado::count($condition);
        list($pagesize, $offset, $pagination) = pagestring($count, $limite);
        $estados = Estado::findAll($condition, $order, $pagesize, $offset);

        if (is_output('json')) {
            $items = [];
            foreach ($estados as $estado) {
                $items[] = $estado->publish();
            }
            json(['status' => 'ok', 'items' => $items]);
        }

        $pais = $estado->findPaisID();
        $_paises = \MZ\Location\Pais::findAll();
        return $app->getResponse()->output('gerenciar_estado_index');
    }

    public function add()
    {
        need_manager(is_output('json'));

        need_permission(Permissao::NOME_CADASTROESTADOS, is_output('json'));
        $id = isset($_GET['id']) ? $_GET['id'] : null;
        $estado = Estado::findByID($id);
        $estado->setID(null);

        $focusctrl = 'nome';
        $errors = [];
        $old_estado = $estado;
        if (is_post()) {
            $estado = new Estado($_POST);
            try {
                $estado->filter($old_estado);
                $estado->save();
                $old_estado->clean($estado);
                $msg = sprintf(
                    'Estado "%s" atualizado com sucesso!',
                    $estado->getNome()
                );
                if (is_output('json')) {
                    json(null, ['item' => $estado->publish(), 'msg' => $msg]);
                }
                \Thunder::success($msg, true);
                redirect('/gerenciar/estado/');
            } catch (\Exception $e) {
                $estado->clean($old_estado);
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
        $_paises = \MZ\Location\Pais::findAll();
        return $app->getResponse()->output('gerenciar_estado_cadastrar');
    }

    public function update()
    {
        need_manager(is_output('json'));

        need_permission(Permissao::NOME_CADASTROESTADOS, is_output('json'));
        $id = isset($_GET['id']) ? $_GET['id'] : null;
        $estado = Estado::findByID($id);
        if (!$estado->exists()) {
            $msg = 'O estado não foi informado ou não existe';
            if (is_output('json')) {
                json($msg);
            }
            \Thunder::warning($msg);
            redirect('/gerenciar/estado/');
        }
        $focusctrl = 'nome';
        $errors = [];
        $old_estado = $estado;
        if (is_post()) {
            $estado = new Estado($_POST);
            try {
                $estado->filter($old_estado);
                $estado->save();
                $old_estado->clean($estado);
                $msg = sprintf(
                    'Estado "%s" atualizado com sucesso!',
                    $estado->getNome()
                );
                if (is_output('json')) {
                    json(null, ['item' => $estado->publish(), 'msg' => $msg]);
                }
                \Thunder::success($msg, true);
                redirect('/gerenciar/estado/');
            } catch (\Exception $e) {
                $estado->clean($old_estado);
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
        $_paises = \MZ\Location\Pais::findAll();
        return $app->getResponse()->output('gerenciar_estado_editar');
    }

    public function delete()
    {
        need_manager(is_output('json'));

        need_permission(Permissao::NOME_CADASTROESTADOS, is_output('json'));
        $id = isset($_GET['id']) ? $_GET['id'] : null;
        $estado = Estado::findByID($id);
        if (!$estado->exists()) {
            $msg = 'O estado não foi informado ou não existe';
            if (is_output('json')) {
                json($msg);
            }
            \Thunder::warning($msg);
            redirect('/gerenciar/estado/');
        }
        try {
            $estado->delete();
            $estado->clean(new Estado());
            $msg = sprintf('Estado "%s" excluído com sucesso!', $estado->getNome());
            if (is_output('json')) {
                json('msg', $msg);
            }
            \Thunder::success($msg, true);
        } catch (\Exception $e) {
            $msg = sprintf(
                'Não foi possível excluir o Estado "%s"',
                $estado->getNome()
            );
            if (is_output('json')) {
                json($msg);
            }
            \Thunder::error($msg);
        }
        redirect('/gerenciar/estado/');
    }

    /**
     * Get URL patterns associated with callback for use into router
     * @return array List of routes
     */
    public static function getRoutes()
    {
        return [
            [
                'name' => 'estado_view',
                'path' => '/estado/',
                'method' => 'GET',
                'controller' => 'view',
            ],
            [
                'name' => 'estado_find',
                'path' => '/gerenciar/estado/',
                'method' => 'GET',
                'controller' => 'find',
            ],
            [
                'name' => 'estado_add',
                'path' => '/gerenciar/estado/cadastrar',
                'method' => ['GET', 'POST'],
                'controller' => 'add',
            ],
            [
                'name' => 'estado_update',
                'path' => '/gerenciar/estado/editar',
                'method' => ['GET', 'POST'],
                'controller' => 'update',
            ],
            [
                'name' => 'estado_delete',
                'path' => '/gerenciar/estado/excluir',
                'method' => 'GET',
                'controller' => 'delete',
            ],
        ];
    }
}
