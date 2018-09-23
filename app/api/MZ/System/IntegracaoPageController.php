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
use MZ\Core\PageController;

/**
 * Allow application to serve system resources
 */
class IntegracaoPageController extends PageController
{
    public function find()
    {
        $this->needPermission([Permissao::NOME_ALTERARCONFIGURACOES]);

        $limite = max(1, min(100, $this->getRequest()->query->getInt('limite', 10)));
        $condition = Filter::query($this->getRequest()->query->all());
        unset($condition['ordem']);
        $integracao = new Integracao($condition);
        $order = Filter::order($this->getRequest()->query->get('ordem', ''));
        $count = Integracao::count($condition);
        $page = max(1, $this->getRequest()->query->getInt('pagina', 1));
        $pager = new \Pager($count, $limite, $page, 'pagina');
        $pagination = $pager->genBasic();
        $integracoes = Integracao::findAll($condition, $order, $limite, $pager->offset);

        if ($this->isJson()) {
            $items = [];
            foreach ($integracoes as $integracao) {
                $items[] = $integracao->publish();
            }
            return $this->json()->success(['items' => $items]);
        }

        return $this->view('gerenciar_integracao_index', get_defined_vars());
    }

    public function update()
    {
        $this->needPermission([Permissao::NOME_ALTERARCONFIGURACOES]);
        $id = $this->getRequest()->query->getInt('id', null);
        $integracao = Integracao::findByID($id);
        if (!$integracao->exists()) {
            $msg = 'A integração não foi informada ou não existe';
            if ($this->isJson()) {
                return $this->json()->error($msg);
            }
            \Thunder::warning($msg);
            return $this->redirect('/gerenciar/integracao/');
        }
        $focusctrl = 'nome';
        $errors = [];
        $old_integracao = $integracao;
        if (is_post()) {
            $integracao = new Integracao($this->getData());
            try {
                $integracao->filter($old_integracao, true);
                $integracao->save(array_keys($this->getData()));
                $old_integracao->clean($integracao);
                $msg = sprintf(
                    'Integração "%s" atualizada com sucesso!',
                    $integracao->getNome()
                );
                if ($this->isJson()) {
                    return $this->json()->success(['item' => $integracao->publish()], $msg);
                }
                \Thunder::success($msg, true);
                return $this->redirect('/gerenciar/integracao/');
            } catch (\Exception $e) {
                $integracao->clean($old_integracao);
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
