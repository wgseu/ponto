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

use MZ\Util\Filter;

/**
 * Allow application to serve system resources
 */
class IntegracaoPageController extends \MZ\Core\Controller
{
    public function find()
    {
        need_permission(Permissao::NOME_ALTERARCONFIGURACOES, is_output('json'));

        $limite = isset($_GET['limite']) ? intval($_GET['limite']) : 10;
        if ($limite > 100 || $limite < 1) {
            $limite = 10;
        }
        $condition = Filter::query($_GET);
        unset($condition['ordem']);
        $integracao = new Integracao($condition);
        $order = Filter::order(isset($_GET['ordem']) ? $_GET['ordem'] : '');
        $count = Integracao::count($condition);
        list($pagesize, $offset, $pagination) = pagestring($count, $limite);
        $integracoes = Integracao::findAll($condition, $order, $pagesize, $offset);

        if (is_output('json')) {
            $items = [];
            foreach ($integracoes as $integracao) {
                $items[] = $integracao->publish();
            }
            json(['status' => 'ok', 'items' => $items]);
        }

        return $this->view('gerenciar_integracao_index', get_defined_vars());
    }

    public function update()
    {
        need_permission(Permissao::NOME_ALTERARCONFIGURACOES, is_output('json'));
        $id = isset($_GET['id']) ? $_GET['id'] : null;
        $integracao = Integracao::findByID($id);
        if (!$integracao->exists()) {
            $msg = 'A integração não foi informada ou não existe';
            if (is_output('json')) {
                json($msg);
            }
            \Thunder::warning($msg);
            redirect('/gerenciar/integracao/');
        }
        $focusctrl = 'nome';
        $errors = [];
        $old_integracao = $integracao;
        if (is_post()) {
            $integracao = new Integracao($_POST);
            try {
                $integracao->filter($old_integracao);
                $integracao->save(array_keys($_POST));
                $old_integracao->clean($integracao);
                $msg = sprintf(
                    'Integração "%s" atualizada com sucesso!',
                    $integracao->getNome()
                );
                if (is_output('json')) {
                    json(null, ['item' => $integracao->publish(), 'msg' => $msg]);
                }
                \Thunder::success($msg, true);
                redirect('/gerenciar/integracao/');
            } catch (\Exception $e) {
                $integracao->clean($old_integracao);
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
        return $this->view('gerenciar_integracao_editar', get_defined_vars());
    }

    /**
     * Get URL patterns associated with callback for use into router
     * @return array List of routes
     */
    public static function getRoutes()
    {
        return [
            [
                'name' => 'integracao_find',
                'path' => '/gerenciar/integracao/',
                'method' => 'GET',
                'controller' => 'find',
            ],
            [
                'name' => 'integracao_update',
                'path' => '/gerenciar/integracao/editar',
                'method' => ['GET', 'POST'],
                'controller' => 'update',
            ],
        ];
    }
}
