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
class MesaPageController extends \MZ\Core\Controller
{
    public function find()
    {
        need_permission(Permissao::NOME_CADASTROMESAS, is_output('json'));

        $limite = isset($_GET['limite']) ? intval($_GET['limite']) : 10;
        if ($limite > 100 || $limite < 1) {
            $limite = 10;
        }
        $condition = Filter::query($_GET);
        unset($condition['ordem']);
        $mesa = new Mesa($condition);
        $order = Filter::order(isset($_GET['ordem']) ? $_GET['ordem'] : '');
        $count = Mesa::count($condition);
        list($pagesize, $offset, $pagination) = pagestring($count, $limite);
        $mesas = Mesa::findAll($condition, $order, $pagesize, $offset);

        if (is_output('json')) {
            $items = [];
            foreach ($mesas as $_mesa) {
                $items[] = $_mesa->publish();
            }
            return $this->json()->success(['items' => $items]);
        }

        $ativas = [
            'Y' => 'Ativas',
            'N' => 'Inativas',
        ];

        return $this->view('gerenciar_mesa_index', get_defined_vars());
    }

    public function add()
    {
        need_permission(Permissao::NOME_CADASTROMESAS, is_output('json'));
        $id = isset($_GET['id']) ? $_GET['id'] : null;
        $mesa = Mesa::findByID($id);
        $mesa->setID(null);

        $focusctrl = 'nome';
        $errors = [];
        $old_mesa = $mesa;
        if (is_post()) {
            $mesa = new Mesa($_POST);
            try {
                $mesa->filter($old_mesa);
                $mesa->insert();
                $old_mesa->clean($mesa);
                $msg = sprintf(
                    'Mesa "%s" cadastrada com sucesso!',
                    $mesa->getNome()
                );
                if (is_output('json')) {
                    return $this->json()->success(['item' => $mesa->publish()], $msg);
                }
                \Thunder::success($msg, true);
                return $this->redirect('/gerenciar/mesa/');
            } catch (\Exception $e) {
                $mesa->clean($old_mesa);
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
        } else {
            $mesa->loadNextID();
            $mesa->setNome('Mesa ' . $mesa->getID());
            $mesa->setAtiva('Y');
        }
        return $this->view('gerenciar_mesa_cadastrar', get_defined_vars());
    }

    public function update()
    {
        need_permission(Permissao::NOME_CADASTROMESAS, is_output('json'));
        $id = isset($_GET['id']) ? $_GET['id'] : null;
        $mesa = Mesa::findByID($id);
        if (!$mesa->exists()) {
            $msg = 'A mesa não foi informada ou não existe';
            if (is_output('json')) {
                return $this->json()->error($msg);
            }
            \Thunder::warning($msg);
            return $this->redirect('/gerenciar/mesa/');
        }
        $focusctrl = 'nome';
        $errors = [];
        $old_mesa = $mesa;
        if (is_post()) {
            $mesa = new Mesa($_POST);
            try {
                $mesa->filter($old_mesa);
                $mesa->update();
                $old_mesa->clean($mesa);
                $msg = sprintf(
                    'Mesa "%s" atualizada com sucesso!',
                    $mesa->getNome()
                );
                if (is_output('json')) {
                    return $this->json()->success(['item' => $mesa->publish()], $msg);
                }
                \Thunder::success($msg, true);
                return $this->redirect('/gerenciar/mesa/');
            } catch (\Exception $e) {
                $mesa->clean($old_mesa);
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
        return $this->view('gerenciar_mesa_editar', get_defined_vars());
    }

    public function delete()
    {
        need_permission(Permissao::NOME_CADASTROMESAS, is_output('json'));
        $id = isset($_GET['id']) ? $_GET['id'] : null;
        $mesa = Mesa::findByID($id);
        if (!$mesa->exists()) {
            $msg = 'A mesa não foi informada ou não existe';
            if (is_output('json')) {
                return $this->json()->error($msg);
            }
            \Thunder::warning($msg);
            return $this->redirect('/gerenciar/mesa/');
        }
        try {
            $mesa->delete();
            $mesa->clean(new Mesa());
            $msg = sprintf('Mesa "%s" excluída com sucesso!', $mesa->getNome());
            if (is_output('json')) {
                return $this->json()->success([], $msg);
            }
            \Thunder::success($msg, true);
        } catch (\Exception $e) {
            $msg = sprintf(
                'Não foi possível excluir a mesa "%s"',
                $mesa->getNome()
            );
            if (is_output('json')) {
                return $this->json()->error($msg);
            }
            \Thunder::error($msg);
        }
        return $this->redirect('/gerenciar/mesa/');
    }

    /**
     * Get URL patterns associated with callback for use into router
     * @return array List of routes
     */
    public static function getRoutes()
    {
        return [
            [
                'name' => 'mesa_find',
                'path' => '/gerenciar/mesa/',
                'method' => 'GET',
                'controller' => 'find',
            ],
            [
                'name' => 'mesa_add',
                'path' => '/gerenciar/mesa/cadastrar',
                'method' => ['GET', 'POST'],
                'controller' => 'add',
            ],
            [
                'name' => 'mesa_update',
                'path' => '/gerenciar/mesa/editar',
                'method' => ['GET', 'POST'],
                'controller' => 'update',
            ],
            [
                'name' => 'mesa_delete',
                'path' => '/gerenciar/mesa/excluir',
                'method' => 'GET',
                'controller' => 'delete',
            ],
        ];
    }
}