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
use MZ\Core\PageController;

/**
 * Allow application to serve system resources
 */
class MoedaPageController extends PageController
{
    public function find()
    {
        $this->needPermission([Permissao::NOME_CADASTROMOEDAS]);

        $limite = max(1, min(100, $this->getRequest()->query->getInt('limite', 10)));
        $condition = Filter::query($this->getRequest()->query->all());
        unset($condition['ordem']);
        $moeda = new Moeda($condition);
        $order = Filter::order($this->getRequest()->query->get('ordem', ''));
        $count = Moeda::count($condition);
        $page = max(1, $this->getRequest()->query->getInt('pagina', 1));
        $pager = new \Pager($count, $limite, $page, 'pagina');
        $pagination = $pager->genBasic();
        $moedas = Moeda::findAll($condition, $order, $limite, $pager->offset);

        if ($this->isJson()) {
            $items = [];
            foreach ($moedas as $_moeda) {
                $items[] = $_moeda->publish();
            }
            return $this->json()->success(['items' => $items]);
        }

        return $this->view('gerenciar_moeda_index', get_defined_vars());
    }

    public function add()
    {
        $this->needPermission([Permissao::NOME_CADASTROMOEDAS]);
        $id = $this->getRequest()->query->getInt('id', null);
        $moeda = Moeda::findByID($id);
        $moeda->setID(null);

        $focusctrl = 'nome';
        $errors = [];
        $old_moeda = $moeda;
        if ($this->getRequest()->isMethod('POST')) {
            $moeda = new Moeda($this->getData());
            try {
                $moeda->filter($old_moeda, true);
                $moeda->save();
                $old_moeda->clean($moeda);
                $msg = sprintf(
                    'Moeda "%s" atualizada com sucesso!',
                    $moeda->getNome()
                );
                if ($this->isJson()) {
                    return $this->json()->success(['item' => $moeda->publish()], $msg);
                }
                \Thunder::success($msg, true);
                return $this->redirect('/gerenciar/moeda/');
            } catch (\Exception $e) {
                $moeda->clean($old_moeda);
                if ($e instanceof \MZ\Exception\ValidationException) {
                    $errors = $e->getErrors();
                }
                if ($this->isJson()) {
                    return $this->json()->error($e->getMessage(), null, $errors);
                }
                \Thunder::error($e->getMessage());
                foreach ($errors as $key => $value) {
                    $focusctrl = $key;
                    break;
                }
            }
        } elseif ($this->isJson()) {
            return $this->json()->error('Nenhum dado foi enviado');
        } elseif (is_null($moeda->getNome())) {
            $moeda->setDivisao(100);
            $moeda->setFracao('Centavo');
            $moeda->setFormato('$ %s');
        }
        return $this->view('gerenciar_moeda_cadastrar', get_defined_vars());
    }

    public function update()
    {
        $this->needPermission([Permissao::NOME_CADASTROMOEDAS]);
        $id = $this->getRequest()->query->getInt('id', null);
        $moeda = Moeda::findByID($id);
        if (!$moeda->exists()) {
            $msg = 'A moeda não foi informada ou não existe';
            if ($this->isJson()) {
                return $this->json()->error($msg);
            }
            \Thunder::warning($msg);
            return $this->redirect('/gerenciar/moeda/');
        }
        $focusctrl = 'nome';
        $errors = [];
        $old_moeda = $moeda;
        if ($this->getRequest()->isMethod('POST')) {
            $moeda = new Moeda($this->getData());
            try {
                $moeda->filter($old_moeda, true);
                $moeda->save();
                $old_moeda->clean($moeda);
                $msg = sprintf(
                    'Moeda "%s" atualizada com sucesso!',
                    $moeda->getNome()
                );
                if ($this->isJson()) {
                    return $this->json()->success(['item' => $moeda->publish()], $msg);
                }
                \Thunder::success($msg, true);
                return $this->redirect('/gerenciar/moeda/');
            } catch (\Exception $e) {
                $moeda->clean($old_moeda);
                if ($e instanceof \MZ\Exception\ValidationException) {
                    $errors = $e->getErrors();
                }
                if ($this->isJson()) {
                    return $this->json()->error($e->getMessage(), null, $errors);
                }
                \Thunder::error($e->getMessage());
                foreach ($errors as $key => $value) {
                    $focusctrl = $key;
                    break;
                }
            }
        } elseif ($this->isJson()) {
            return $this->json()->error('Nenhum dado foi enviado');
        }
        return $this->view('gerenciar_moeda_editar', get_defined_vars());
    }

    public function delete()
    {
        $this->needPermission([Permissao::NOME_CADASTROMOEDAS]);
        $id = $this->getRequest()->query->getInt('id', null);
        $moeda = Moeda::findByID($id);
        if (!$moeda->exists()) {
            $msg = 'A moeda não foi informada ou não existe';
            if ($this->isJson()) {
                return $this->json()->error($msg);
            }
            \Thunder::warning($msg);
            return $this->redirect('/gerenciar/moeda/');
        }
        try {
            $moeda->delete();
            $moeda->clean(new Moeda());
            $msg = sprintf('Moeda "%s" excluída com sucesso!', $moeda->getNome());
            if ($this->isJson()) {
                return $this->json()->success([], $msg);
            }
            \Thunder::success($msg, true);
        } catch (\Exception $e) {
            $msg = sprintf(
                'Não foi possível excluir a moeda "%s"',
                $moeda->getNome()
            );
            if ($this->isJson()) {
                return $this->json()->error($msg);
            }
            \Thunder::error($msg);
        }
        return $this->redirect('/gerenciar/moeda/');
    }

    /**
     * Get URL patterns associated with callback for use into router
     * @return array List of routes
     */
    public static function getRoutes()
    {
        return [
            [
                'name' => 'moeda_find',
                'path' => '/gerenciar/moeda/',
                'method' => 'GET',
                'controller' => 'find',
            ],
            [
                'name' => 'moeda_add',
                'path' => '/gerenciar/moeda/cadastrar',
                'method' => ['GET', 'POST'],
                'controller' => 'add',
            ],
            [
                'name' => 'moeda_update',
                'path' => '/gerenciar/moeda/editar',
                'method' => ['GET', 'POST'],
                'controller' => 'update',
            ],
            [
                'name' => 'moeda_delete',
                'path' => '/gerenciar/moeda/excluir',
                'method' => 'GET',
                'controller' => 'delete',
            ],
        ];
    }
}
