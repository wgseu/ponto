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
namespace MZ\Session;

use MZ\System\Permissao;
use MZ\Util\Filter;
use MZ\System\Permissao;

/**
 * Allow application to serve system resources
 */
class CaixaPageController extends \MZ\Core\Controller
{
    public function view()
    {
    }

    public function find()
    {
        need_manager(is_output('json'));

        need_permission(Permissao::NOME_CADASTROCAIXAS, is_output('json'));

        $limite = isset($_GET['limite']) ? intval($_GET['limite']) : 10;
        if ($limite > 100 || $limite < 1) {
            $limite = 10;
        }
        $condition = Filter::query($_GET);
        unset($condition['ordem']);
        $caixa = new Caixa($condition);
        $order = Filter::order(isset($_GET['ordem']) ? $_GET['ordem'] : '');
        $count = Caixa::count($condition);
        list($pagesize, $offset, $pagination) = pagestring($count, $limite);
        $caixas = Caixa::findAll($condition, $order, $pagesize, $offset);

        if (is_output('json')) {
            $items = [];
            foreach ($caixas as $_caixa) {
                $items[] = $_caixa->publish();
            }
            json(['status' => 'ok', 'items' => $items]);
        }

        $estados = [
            'Y' => 'Ativos',
            'N' => 'Inativos',
        ];

        return $app->getResponse()->output('gerenciar_caixa_index');
    }

    public function add()
    {
        need_manager(is_output('json'));

        need_permission(Permissao::NOME_CADASTROCAIXAS, is_output('json'));
        $id = isset($_GET['id']) ? $_GET['id'] : null;
        $caixa = Caixa::findByID($id);
        $caixa->setID(null);

        $focusctrl = 'descricao';
        $errors = [];
        $old_caixa = $caixa;
        if (is_post()) {
            $caixa = new Caixa($_POST);
            try {
                $caixa->filter($old_caixa);
                $caixa->insert();
                $old_caixa->clean($caixa);
                $msg = sprintf(
                    'Caixa "%s" cadastrado com sucesso!',
                    $caixa->getDescricao()
                );
                if (is_output('json')) {
                    json(null, ['item' => $caixa->publish(), 'msg' => $msg]);
                }
                \Thunder::success($msg, true);
                redirect('/gerenciar/caixa/');
            } catch (\Exception $e) {
                $caixa->clean($old_caixa);
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
        } else {
            $caixa->setAtivo('Y');
        }
        return $app->getResponse()->output('gerenciar_caixa_cadastrar');
    }

    public function update()
    {
        need_manager(is_output('json'));

        need_permission(Permissao::NOME_CADASTROCAIXAS, is_output('json'));
        $id = isset($_GET['id']) ? $_GET['id'] : null;
        $caixa = Caixa::findByID($id);
        if (!$caixa->exists()) {
            $msg = 'O caixa informado não existe!';
            if (is_output('json')) {
                json($msg);
            }
            \Thunder::warning($msg);
            redirect('/gerenciar/caixa/');
        }
        $focusctrl = 'descricao';
        $errors = [];
        $old_caixa = $caixa;
        if (is_post()) {
            $caixa = new Caixa($_POST);
            try {
                $caixa->setID($old_caixa->getID());
                if (!$app->getSystem()->isFiscalVisible()) {
                    $caixa->setNumeroInicial($old_caixa->getNumeroInicial());
                    $caixa->setSerie($old_caixa->getSerie());
                }
                $caixa->filter($old_caixa);
                $caixa->update();
                $old_caixa->clean($caixa);
                $msg = sprintf(
                    'Caixa "%s" atualizado com sucesso!',
                    $caixa->getDescricao()
                );
                if (is_output('json')) {
                    json(null, ['item' => $caixa->publish(), 'msg' => $msg]);
                }
                \Thunder::success($msg, true);
                redirect('/gerenciar/caixa/');
            } catch (\Exception $e) {
                $caixa->clean($old_caixa);
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
        return $app->getResponse()->output('gerenciar_caixa_editar');
    }

    public function delete()
    {
        need_manager(is_output('json'));

        need_permission(Permissao::NOME_CADASTROCAIXAS, is_output('json'));
        $id = isset($_GET['id']) ? $_GET['id'] : null;
        $caixa = Caixa::findByID($id);
        if (!$caixa->exists()) {
            $msg = 'O caixa não foi informado ou não existe!';
            if (is_output('json')) {
                json($msg);
            }
            \Thunder::warning($msg);
            redirect('/gerenciar/caixa/');
        }
        try {
            $caixa->delete();
            $caixa->clean(new Caixa());
            $msg = sprintf('Caixa "%s" excluído com sucesso!', $caixa->getDescricao());
            if (is_output('json')) {
                json('msg', $msg);
            }
            \Thunder::success($msg, true);
        } catch (\Exception $e) {
            $msg = sprintf(
                'Não foi possível excluir o caixa "%s"!',
                $caixa->getDescricao()
            );
            if (is_output('json')) {
                json($msg);
            }
            \Thunder::error($msg);
        }
        redirect('/gerenciar/caixa/');
    }

    /**
     * Get URL patterns associated with callback for use into router
     * @return array List of routes
     */
    public static function getRoutes()
    {
        return [
            [
                'name' => 'caixa_view',
                'path' => '/caixa/',
                'method' => 'GET',
                'controller' => 'view',
            ],
            [
                'name' => 'caixa_find',
                'path' => '/gerenciar/caixa/',
                'method' => 'GET',
                'controller' => 'find',
            ],
            [
                'name' => 'caixa_add',
                'path' => '/gerenciar/caixa/cadastrar',
                'method' => ['GET', 'POST'],
                'controller' => 'add',
            ],
            [
                'name' => 'caixa_update',
                'path' => '/gerenciar/caixa/editar',
                'method' => ['GET', 'POST'],
                'controller' => 'update',
            ],
            [
                'name' => 'caixa_delete',
                'path' => '/gerenciar/caixa/excluir',
                'method' => 'GET',
                'controller' => 'delete',
            ],
        ];
    }
}
