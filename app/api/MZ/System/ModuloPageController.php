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
namespace MZ\System;

use MZ\Database\DB;
use MZ\Util\Filter;

/**
 * Allow application to serve system resources
 */
class ModuloPageController extends \MZ\Core\Controller
{
    public function view()
    {
    }

    public function find()
    {
        need_manager(is_output('json'));

        need_permission(Permissao::NOME_ALTERARCONFIGURACOES, is_output('json'));

        $limite = isset($_GET['limite']) ? intval($_GET['limite']) : 10;
        if ($limite > 100 || $limite < 1) {
            $limite = 10;
        }
        $condition = Filter::query($_GET);
        unset($condition['ordem']);
        $modulo = new Modulo($condition);
        $order = Filter::order(isset($_GET['ordem']) ? $_GET['ordem'] : '');
        $count = Modulo::count($condition);
        list($pagesize, $offset, $pagination) = pagestring($count, $limite);
        $modulos = Modulo::findAll($condition, $order, $pagesize, $offset);

        if (is_output('json')) {
            $items = [];
            foreach ($modulos as $_modulo) {
                $items[] = $_modulo->publish();
            }
            json(['status' => 'ok', 'items' => $items]);
        }

        return $app->getResponse()->output('gerenciar_modulo_index');
    }

    public function add()
    {
        need_manager(is_output('json'));
    }

    public function update()
    {
        need_manager(is_output('json'));

        need_permission(Permissao::NOME_ALTERARCONFIGURACOES, true);
        $id = isset($_GET['id']) ? $_GET['id'] : null;
        $modulo = Modulo::findByID($id);
        if (!$modulo->exists()) {
            $msg = 'O módulo não foi informado ou não existe';
            json($msg);
        }
        $focusctrl = 'nome';
        $errors = [];
        $old_modulo = $modulo;
        if (is_post()) {
            $modulo = new Modulo($_POST);
            try {
                DB::beginTransaction();
                $modulo->filter($old_modulo);
                $modulo->update();
                $old_modulo->clean($modulo);
                try {
                    $sync = new Synchronizer();
                    $sync->systemOptionsChanged();
                } catch (\Exception $e) {
                    \Log::error($e->getMessage());
                }
                DB::commit();
                $msg = sprintf(
                    'Módulo "%s" atualizado com sucesso!',
                    $modulo->getNome()
                );
                json(null, ['item' => $modulo->publish(), 'msg' => $msg]);
            } catch (\Exception $e) {
                DB::Rollback();
                $modulo->clean($old_modulo);
                if ($e instanceof \MZ\Exception\ValidationException) {
                    $errors = $e->getErrors();
                }
                json($e->getMessage(), null, ['errors' => $errors]);
            }
        } else {
            json('Nenhum dado foi enviado');
        }
    }

    public function delete()
    {
        need_manager(is_output('json'));
    }

    /**
     * Get URL patterns associated with callback for use into router
     * @return array List of routes
     */
    public static function getRoutes()
    {
        return [
            [
                'name' => 'modulo_view',
                'path' => '/modulo/',
                'method' => 'GET',
                'controller' => 'view',
            ],
            [
                'name' => 'modulo_find',
                'path' => '/gerenciar/modulo/',
                'method' => 'GET',
                'controller' => 'find',
            ],
            [
                'name' => 'modulo_add',
                'path' => '/gerenciar/modulo/cadastrar',
                'method' => ['GET', 'POST'],
                'controller' => 'add',
            ],
            [
                'name' => 'modulo_update',
                'path' => '/gerenciar/modulo/editar',
                'method' => ['GET', 'POST'],
                'controller' => 'update',
            ],
            [
                'name' => 'modulo_delete',
                'path' => '/gerenciar/modulo/excluir',
                'method' => 'GET',
                'controller' => 'delete',
            ],
        ];
    }
}
