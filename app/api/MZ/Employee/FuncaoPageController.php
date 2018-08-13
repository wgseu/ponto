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

use MZ\Util\Filter;

/**
 * Allow application to serve system resources
 */
class FuncaoPageController extends \MZ\Core\Controller
{
    public function find()
    {
        need_manager(is_output('json'));

        need_owner();

        $limite = isset($_GET['limite']) ? intval($_GET['limite']) : 10;
        if ($limite > 100 || $limite < 1) {
            $limite = 10;
        }
        $condition = Filter::query($_GET);
        unset($condition['ordem']);
        $funcao = new Funcao($condition);
        $order = Filter::order(isset($_GET['ordem']) ? $_GET['ordem'] : '');
        $count = Funcao::count($condition);
        list($pagesize, $offset, $pagination) = pagestring($count, $limite);
        $funcoes = Funcao::findAll($condition, $order, $pagesize, $offset);

        if (is_output('json')) {
            $items = [];
            foreach ($funcoes as $_funcao) {
                $items[] = $_funcao->publish();
            }
            return $this->json()->success(['items' => $items]);
        }

        return $this->view('gerenciar_funcao_index', get_defined_vars());
    }

    public function add()
    {
        need_manager(is_output('json'));

        need_owner();
        $id = isset($_GET['id']) ? $_GET['id'] : null;
        $funcao = Funcao::findByID($id);
        $funcao->setID(null);

        $focusctrl = 'descricao';
        $errors = [];
        $old_funcao = $funcao;
        if (is_post()) {
            $funcao = new Funcao($_POST);
            try {
                $funcao->filter($old_funcao);
                $funcao->insert();
                $old_funcao->clean($funcao);
                $msg = sprintf(
                    'Função "%s" cadastrada com sucesso!',
                    $funcao->getDescricao()
                );
                if (is_output('json')) {
                    return $this->json()->success(['item' => $funcao->publish()], $msg);
                }
                \Thunder::success($msg, true);
                return $this->redirect('/gerenciar/funcao/');
            } catch (\Exception $e) {
                $funcao->clean($old_funcao);
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
        return $this->view('gerenciar_funcao_cadastrar', get_defined_vars());
    }

    public function update()
    {
        need_manager(is_output('json'));

        need_owner();
        $id = isset($_GET['id']) ? $_GET['id'] : null;
        $funcao = Funcao::findByID($id);
        if (!$funcao->exists()) {
            $msg = 'A função não foi informada ou não existe';
            if (is_output('json')) {
                return $this->json()->error($msg);
            }
            \Thunder::warning($msg);
            return $this->redirect('/gerenciar/funcao/');
        }
        $focusctrl = 'descricao';
        $errors = [];
        $old_funcao = $funcao;
        if (is_post()) {
            $funcao = new Funcao($_POST);
            try {
                $funcao->filter($old_funcao);
                $funcao->update();
                $old_funcao->clean($funcao);
                $msg = sprintf(
                    'Função "%s" atualizada com sucesso!',
                    $funcao->getDescricao()
                );
                if (is_output('json')) {
                    return $this->json()->success(['item' => $funcao->publish()], $msg);
                }
                \Thunder::success($msg, true);
                return $this->redirect('/gerenciar/funcao/');
            } catch (\Exception $e) {
                $funcao->clean($old_funcao);
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
        return $this->view('gerenciar_funcao_editar', get_defined_vars());
    }

    public function delete()
    {
        need_manager(is_output('json'));

        need_owner();
        $id = isset($_GET['id']) ? $_GET['id'] : null;
        $funcao = Funcao::findByID($id);
        if (!$funcao->exists()) {
            $msg = 'A função não foi informada ou não existe';
            if (is_output('json')) {
                return $this->json()->error($msg);
            }
            \Thunder::warning($msg);
            return $this->redirect('/gerenciar/funcao/');
        }
        try {
            $funcao->delete();
            $funcao->clean(new Funcao());
            $msg = sprintf('Função "%s" excluída com sucesso!', $funcao->getDescricao());
            if (is_output('json')) {
                return $this->json()->success([], $msg);
            }
            \Thunder::success($msg, true);
        } catch (\Exception $e) {
            $msg = sprintf(
                'Não foi possível excluir a função "%s"',
                $funcao->getDescricao()
            );
            if (is_output('json')) {
                return $this->json()->error($msg);
            }
            \Thunder::error($msg);
        }
        return $this->redirect('/gerenciar/funcao/');
    }

    /**
     * Get URL patterns associated with callback for use into router
     * @return array List of routes
     */
    public static function getRoutes()
    {
        return [
            [
                'name' => 'funcao_find',
                'path' => '/gerenciar/funcao/',
                'method' => 'GET',
                'controller' => 'find',
            ],
            [
                'name' => 'funcao_add',
                'path' => '/gerenciar/funcao/cadastrar',
                'method' => ['GET', 'POST'],
                'controller' => 'add',
            ],
            [
                'name' => 'funcao_update',
                'path' => '/gerenciar/funcao/editar',
                'method' => ['GET', 'POST'],
                'controller' => 'update',
            ],
            [
                'name' => 'funcao_delete',
                'path' => '/gerenciar/funcao/excluir',
                'method' => 'GET',
                'controller' => 'delete',
            ],
        ];
    }
}
