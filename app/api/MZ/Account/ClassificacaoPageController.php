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
namespace MZ\Account;

use MZ\System\Permissao;
use MZ\Util\Filter;

/**
 * Allow application to serve system resources
 */
class ClassificacaoPageController extends \MZ\Core\Controller
{
    public function find()
    {
        need_permission(Permissao::NOME_CADASTROCONTAS, is_output('json'));

        $limite = isset($_GET['limite']) ? intval($_GET['limite']) : 10;
        if ($limite > 100 || $limite < 1) {
            $limite = 10;
        }
        $condition = Filter::query($_GET);
        unset($condition['ordem']);
        if (isset($condition['classificacaoid']) && intval($condition['classificacaoid']) < 0) {
            unset($condition['classificacaoid']);
        } elseif (array_key_exists('classificacaoid', $_GET)) {
            $condition['classificacaoid'] = isset($condition['classificacaoid']) ? $condition['classificacaoid'] : null;
        }
        $classificacao = new Classificacao($condition);
        $order = Filter::order(isset($_GET['ordem']) ? $_GET['ordem'] : '');
        $count = Classificacao::count($condition);
        list($pagesize, $offset, $pagination) = pagestring($count, $limite);
        $classificacoes = Classificacao::findAll($condition, $order, $pagesize, $offset);

        if (is_output('json')) {
            $items = [];
            foreach ($classificacoes as $_classificacao) {
                $items[] = $_classificacao->publish();
            }
            return $this->json()->success(['items' => $items]);
        }

        $classificacoes_sup = Classificacao::findAll(['classificacaoid' => null]);
        $_classificacao_names = [];
        foreach ($classificacoes_sup as $classificacao) {
            $_classificacao_names[$classificacao->getID()] = $classificacao->getDescricao();
        }
        return $this->view('gerenciar_classificacao_index', get_defined_vars());
    }

    public function add()
    {
        need_permission(Permissao::NOME_CADASTROCONTAS, is_output('json'));
        $id = isset($_GET['id']) ? $_GET['id'] : null;
        $classificacao = Classificacao::findByID($id);
        $classificacao->setID(null);

        $focusctrl = 'descricao';
        $errors = [];
        $old_classificacao = $classificacao;
        if (is_post()) {
            $classificacao = new Classificacao($_POST);
            try {
                $classificacao->filter($old_classificacao);
                $classificacao->insert();
                $old_classificacao->clean($classificacao);
                $msg = sprintf(
                    'Classificação "%s" cadastrada com sucesso!',
                    $classificacao->getDescricao()
                );
                if (is_output('json')) {
                    return $this->json()->success(['item' => $classificacao->publish()], $msg);
                }
                \Thunder::success($msg, true);
                return $this->redirect('/gerenciar/classificacao/');
            } catch (\Exception $e) {
                $classificacao->clean($old_classificacao);
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
            $classificacao = new Classificacao();
        }
        $_classificacoes = Classificacao::findAll(['classificacaoid' => null]);
        return $this->view('gerenciar_classificacao_cadastrar', get_defined_vars());
    }

    public function update()
    {
        need_permission(Permissao::NOME_CADASTROCONTAS, is_output('json'));
        $id = isset($_GET['id']) ? $_GET['id'] : null;
        $classificacao = Classificacao::findByID($id);
        if (!$classificacao->exists()) {
            $msg = 'A classificação não foi informada ou não existe!';
            if (is_output('json')) {
                return $this->json()->error($msg);
            }
            \Thunder::warning($msg);
            return $this->redirect('/gerenciar/classificacao/');
        }
        $focusctrl = 'descricao';
        $errors = [];
        $old_classificacao = $classificacao;
        if (is_post()) {
            $classificacao = new Classificacao($_POST);
            try {
                $classificacao->setID($old_classificacao->getID());
                $classificacao->filter($old_classificacao);
                $classificacao->update();
                $old_classificacao->clean($classificacao);
                $msg = sprintf(
                    'Classificação "%s" atualizada com sucesso!',
                    $classificacao->getDescricao()
                );
                if (is_output('json')) {
                    return $this->json()->success(['item' => $classificacao->publish()], $msg);
                }
                \Thunder::success($msg, true);
                return $this->redirect('/gerenciar/classificacao/');
            } catch (\Exception $e) {
                $classificacao->clean($old_classificacao);
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
        $_classificacoes = Classificacao::findAll(['classificacaoid' => null]);
        return $this->view('gerenciar_classificacao_editar', get_defined_vars());
    }

    public function delete()
    {
        need_permission(Permissao::NOME_CADASTROCONTAS, is_output('json'));
        $id = isset($_GET['id']) ? $_GET['id'] : null;
        $classificacao = Classificacao::findByID($id);
        if (!$classificacao->exists()) {
            $msg = 'A classificação não foi informada ou não existe!';
            if (is_output('json')) {
                return $this->json()->error($msg);
            }
            \Thunder::warning($msg);
            return $this->redirect('/gerenciar/classificacao/');
        }
        try {
            $classificacao->delete();
            $classificacao->clean(new Classificacao());
            $msg = sprintf('Classificação "%s" excluída com sucesso!', $classificacao->getDescricao());
            if (is_output('json')) {
                return $this->json()->success([], $msg);
            }
            \Thunder::success($msg, true);
        } catch (\Exception $e) {
            $msg = sprintf(
                'Não foi possível excluir a classificação "%s"!',
                $classificacao->getDescricao()
            );
            if (is_output('json')) {
                return $this->json()->error($msg);
            }
            \Thunder::error($msg);
        }
        return $this->redirect('/gerenciar/classificacao/');
    }

    /**
     * Get URL patterns associated with callback for use into router
     * @return array List of routes
     */
    public static function getRoutes()
    {
        return [
            [
                'name' => 'classificacao_find',
                'path' => '/gerenciar/classificacao/',
                'method' => 'GET',
                'controller' => 'find',
            ],
            [
                'name' => 'classificacao_add',
                'path' => '/gerenciar/classificacao/cadastrar',
                'method' => ['GET', 'POST'],
                'controller' => 'add',
            ],
            [
                'name' => 'classificacao_update',
                'path' => '/gerenciar/classificacao/editar',
                'method' => ['GET', 'POST'],
                'controller' => 'update',
            ],
            [
                'name' => 'classificacao_delete',
                'path' => '/gerenciar/classificacao/excluir',
                'method' => 'GET',
                'controller' => 'delete',
            ],
        ];
    }
}
