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
use MZ\Wallet\Moeda;
use MZ\Util\Filter;
use MZ\Core\PageController;

/**
 * Allow application to serve system resources
 */
class PaisPageController extends PageController
{
    public function find()
    {
        $this->needPermission([Permissao::NOME_CADASTROPAISES]);

        $limite = max(1, min(100, $this->getRequest()->query->getInt('limite', 10)));
        $condition = Filter::query($this->getRequest()->query->all());
        unset($condition['ordem']);
        $pais = new Pais($condition);
        $order = Filter::order($this->getRequest()->query->get('ordem', ''));
        $count = Pais::count($condition);
        $page = max(1, $this->getRequest()->query->getInt('pagina', 1));
        $pager = new \Pager($count, $limite, $page, 'pagina');
        $pagination = $pager->genBasic();
        $paises = Pais::findAll($condition, $order, $limite, $pager->offset);

        if ($this->isJson()) {
            $items = [];
            foreach ($paises as $_pais) {
                $items[] = $_pais->publish();
            }
            return $this->json()->success(['items' => $items]);
        }

        $moedas = Moeda::findAll();
        return $this->view('gerenciar_pais_index', get_defined_vars());
    }

    public function add()
    {
        $this->needPermission([Permissao::NOME_CADASTROPAISES]);
        $id = $this->getRequest()->query->getInt('id', null);
        $pais = Pais::findByID($id);
        $pais->setID(null);

        $focusctrl = 'nome';
        $errors = [];
        $old_pais = $pais;
        if (is_post()) {
            $pais = new Pais($this->getData());
            try {
                $pais->filter($old_pais, true);
                $pais->save();
                $old_pais->clean($pais);
                $msg = sprintf(
                    'País "%s" atualizado com sucesso!',
                    $pais->getNome()
                );
                if ($this->isJson()) {
                    return $this->json()->success(['item' => $pais->publish()], $msg);
                }
                \Thunder::success($msg, true);
                return $this->redirect('/gerenciar/pais/');
            } catch (\Exception $e) {
                $pais->clean($old_pais);
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
        $moedas = Moeda::findAll();
        $flags_images = Pais::getImageIndexOptions();
        return $this->view('gerenciar_pais_cadastrar', get_defined_vars());
    }

    public function update()
    {
        $this->needPermission([Permissao::NOME_CADASTROPAISES]);
        $id = $this->getRequest()->query->getInt('id', null);
        $pais = Pais::findByID($id);
        if (!$pais->exists()) {
            $msg = 'O país não foi informado ou não existe';
            if ($this->isJson()) {
                return $this->json()->error($msg);
            }
            \Thunder::warning($msg);
            return $this->redirect('/gerenciar/pais/');
        }
        $focusctrl = 'nome';
        $errors = [];
        $old_pais = $pais;
        if (is_post()) {
            $pais = new Pais($this->getData());
            try {
                $pais->filter($old_pais, true);
                $pais->save();
                $old_pais->clean($pais);
                $msg = sprintf(
                    'País "%s" atualizado com sucesso!',
                    $pais->getNome()
                );
                if ($this->isJson()) {
                    return $this->json()->success(['item' => $pais->publish()], $msg);
                }
                \Thunder::success($msg, true);
                return $this->redirect('/gerenciar/pais/');
            } catch (\Exception $e) {
                $pais->clean($old_pais);
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
        $moedas = Moeda::findAll();
        $flags_images = Pais::getImageIndexOptions();
        return $this->view('gerenciar_pais_editar', get_defined_vars());
    }

    public function delete()
    {
        $this->needPermission([Permissao::NOME_CADASTROPAISES]);
        $id = $this->getRequest()->query->getInt('id', null);
        $pais = Pais::findByID($id);
        if (!$pais->exists()) {
            $msg = 'O país não foi informado ou não existe';
            if ($this->isJson()) {
                return $this->json()->error($msg);
            }
            \Thunder::warning($msg);
            return $this->redirect('/gerenciar/pais/');
        }
        try {
            $pais->delete();
            $pais->clean(new Pais());
            $msg = sprintf('País "%s" excluído com sucesso!', $pais->getNome());
            $msg = 'País "' . $pais->getNome() . '" excluído com sucesso!';
            if ($this->isJson()) {
                return $this->json()->success([], $msg);
            }
            \Thunder::success($msg, true);
        } catch (\Exception $e) {
            $msg = sprintf(
                'Não foi possível excluir o país "%s"',
                $pais->getNome()
            );
            if ($this->isJson()) {
                return $this->json()->error($msg);
            }
            \Thunder::error($msg);
        }
        return $this->redirect('/gerenciar/pais/');
    }

    /**
     * Get URL patterns associated with callback for use into router
     * @return array List of routes
     */
    public static function getRoutes()
    {
        return [
            [
                'name' => 'pais_find',
                'path' => '/gerenciar/pais/',
                'method' => 'GET',
                'controller' => 'find',
            ],
            [
                'name' => 'pais_add',
                'path' => '/gerenciar/pais/cadastrar',
                'method' => ['GET', 'POST'],
                'controller' => 'add',
            ],
            [
                'name' => 'pais_update',
                'path' => '/gerenciar/pais/editar',
                'method' => ['GET', 'POST'],
                'controller' => 'update',
            ],
            [
                'name' => 'pais_delete',
                'path' => '/gerenciar/pais/excluir',
                'method' => 'GET',
                'controller' => 'delete',
            ],
        ];
    }
}
