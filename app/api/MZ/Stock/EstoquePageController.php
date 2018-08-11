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

/**
 * Allow application to serve system resources
 */
class EstoquePageController extends \MZ\Core\Controller
{
    public function find()
    {
        need_manager(is_output('json'));

        need_permission(Permissao::NOME_ESTOQUE, is_output('json'));

        $limite = isset($_GET['limite']) ? intval($_GET['limite']) : 10;
        if ($limite > 100 || $limite < 1) {
            $limite = 10;
        }
        $condition = Filter::query($_GET);
        unset($condition['ordem']);
        $estoque = new Estoque($condition);
        $order = Filter::order(isset($_GET['ordem']) ? $_GET['ordem'] : '');
        $count = Estoque::count($condition);
        list($pagesize, $offset, $pagination) = pagestring($count, $limite);
        $estoques = Estoque::findAll($condition, $order, $pagesize, $offset);

        if (is_output('json')) {
            $items = [];
            foreach ($estoques as $_estoque) {
                $items[] = $_estoque->publish();
            }
            json(['status' => 'ok', 'items' => $items]);
        }

        $_produto = $estoque->findProdutoID();
        $_fornecedor = $estoque->findFornecedorID();

        $tipos = Estoque::getTipoMovimentoOptions();
        return $app->getResponse()->output('gerenciar_estoque_index');
    }

    public function cancel()
    {
        need_permission([Permissao::NOME_ESTOQUE, Permissao::NOME_RETIRARDOESTOQUE], is_output('json'));
        $id = isset($_GET['id']) ? $_GET['id'] : null;
        $estoque = Estoque::findByID($id);
        if (!$estoque->exists()) {
            $msg = 'O estoque não foi informado ou não existe';
            if (is_output('json')) {
                json($msg);
            }
            \Thunder::warning($msg);
            redirect('/gerenciar/estoque/');
        }
        try {
            $produto = $estoque->findProdutoID();
            $estoque->cancelar();
            $msg = sprintf(
                'Entrada do produto "%s" e quantidade %s cancelada com sucesso!',
                $produto->getDescricao(),
                strval($estoque->getQuantidade())
            );
            if (is_output('json')) {
                json('msg', $msg);
            }
            \Thunder::success($msg, true);
        } catch (\Exception $e) {
            $msg = $e->getMessage();
            if (is_output('json')) {
                json($msg);
            }
            \Thunder::error($msg);
        }
        redirect('/gerenciar/estoque/');
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
