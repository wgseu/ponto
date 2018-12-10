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
namespace MZ\Stock;

use MZ\System\Permissao;
use MZ\Util\Filter;
use MZ\Core\PageController;

/**
 * Allow application to serve system resources
 */
class EstoquePageController extends PageController
{
    public function find()
    {
        $this->needPermission([Permissao::NOME_ESTOQUE]);

        $limite = max(1, min(100, $this->getRequest()->query->getInt('limite', 10)));
        $condition = Filter::query($this->getRequest()->query->all());
        unset($condition['ordem']);
        $estoque = new Estoque($condition);
        $order = Filter::order($this->getRequest()->query->get('ordem', ''));
        $count = Estoque::count($condition);
        $page = max(1, $this->getRequest()->query->getInt('pagina', 1));
        $pager = new \Pager($count, $limite, $page, 'pagina');
        $pagination = $pager->genPages();
        $estoques = Estoque::findAll($condition, $order, $limite, $pager->offset);

        if ($this->isJson()) {
            $items = [];
            foreach ($estoques as $_estoque) {
                $items[] = $_estoque->publish(app()->auth->provider);
            }
            return $this->json()->success(['items' => $items]);
        }

        $_produto = $estoque->findProdutoID();
        $_fornecedor = $estoque->findFornecedorID();

        $tipos = Estoque::getTipoMovimentoOptions();
        return $this->view('gerenciar_estoque_index', get_defined_vars());
    }

    public function cancel()
    {
        $this->needPermission([Permissao::NOME_ESTOQUE, Permissao::NOME_RETIRARDOESTOQUE]);
        $id = $this->getRequest()->query->getInt('id', null);
        $estoque = Estoque::findByID($id);
        if (!$estoque->exists()) {
            $msg = 'O estoque não foi informado ou não existe';
            if ($this->isJson()) {
                return $this->json()->error($msg);
            }
            \Thunder::warning($msg);
            return $this->redirect('/gerenciar/estoque/');
        }
        try {
            $produto = $estoque->findProdutoID();
            $estoque->cancelar();
            $msg = sprintf(
                'Entrada do produto "%s" e quantidade %s cancelada com sucesso!',
                $produto->getDescricao(),
                strval($estoque->getQuantidade())
            );
            if ($this->isJson()) {
                return $this->json()->success([], $msg);
            }
            \Thunder::success($msg, true);
        } catch (\Exception $e) {
            $msg = $e->getMessage();
            if ($this->isJson()) {
                return $this->json()->error($msg);
            }
            \Thunder::error($msg);
        }
        return $this->redirect('/gerenciar/estoque/');
    }

    /**
     * Get URL patterns associated with callback for use into router
     * @return array List of routes
     */
    public static function getRoutes()
    {
        return [
            [
                'name' => 'estoque_find',
                'path' => '/gerenciar/estoque/',
                'method' => 'GET',
                'controller' => 'find',
            ],
            [
                'name' => 'estoque_cancel',
                'path' => '/gerenciar/estoque/cancelar',
                'method' => 'GET',
                'controller' => 'cancel',
            ],
        ];
    }
}
