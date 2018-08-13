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
namespace MZ\Payment;

use MZ\Wallet\Banco;
use MZ\System\Permissao;
use MZ\Util\Filter;

/**
 * Allow application to serve system resources
 */
class FolhaChequePageController extends \MZ\Core\Controller
{
    public function find()
    {
        need_permission(Permissao::NOME_PAGAMENTO, is_output('json'));

        $limite = isset($_GET['limite']) ? intval($_GET['limite']) : 10;
        if ($limite > 100 || $limite < 1) {
            $limite = 10;
        }
        $condition = Filter::query($_GET);
        unset($condition['ordem']);
        $folha_cheque = new FolhaCheque($condition);
        $cheque = new Cheque($condition);
        $order = Filter::order(isset($_GET['ordem']) ? $_GET['ordem'] : '');
        $count = FolhaCheque::count($condition);
        list($pagesize, $offset, $pagination) = pagestring($count, $limite);
        $folhas_de_cheques = FolhaCheque::findAll($condition, $order, $pagesize, $offset);

        if (is_output('json')) {
            $items = [];
            foreach ($folhas_de_cheques as $_folha_cheque) {
                $items[] = $_folha_cheque->publish();
            }
            return $this->json()->success(['items' => $items]);
        }

        $_banco = $cheque->findBancoID();
        $_cliente = $cheque->findClienteID();
        $estados = [
            'N' => 'A compensar',
            'Y' => 'Recolhido',
        ];
        return $this->view('gerenciar_folha_cheque_index', get_defined_vars());
    }

    public function recall()
    {
        need_permission(Permissao::NOME_PAGAMENTO, is_output('json'));
        $id = isset($_GET['id']) ? $_GET['id'] : null;
        $folha_cheque = FolhaCheque::findByID($id);
        if (!$folha_cheque->exists()) {
            $msg = 'A folha de cheque não foi informada ou não existe';
            if (is_output('json')) {
                return $this->json()->error($msg);
            }
            \Thunder::warning($msg);
            return $this->redirect('/gerenciar/folha_cheque/');
        }
        try {
            $folha_cheque->recolher();
            $msg = sprintf('Folha de cheque "%s" compensada com sucesso!', $folha_cheque->getNumero());
            if (is_output('json')) {
                return $this->json()->success([], $msg);
            }
            \Thunder::success($msg, true);
        } catch (\Exception $e) {
            $msg = sprintf(
                'Não foi possível recolher a folha de cheque "%s"',
                $folha_cheque->getNumero()
            );
            if (is_output('json')) {
                return $this->json()->error($msg);
            }
            \Thunder::error($msg);
        }
        return $this->redirect('/gerenciar/folha_cheque/');
    }

    /**
     * Get URL patterns associated with callback for use into router
     * @return array List of routes
     */
    public static function getRoutes()
    {
        return [
            [
                'name' => 'folha_cheque_find',
                'path' => '/gerenciar/folha_cheque/',
                'method' => 'GET',
                'controller' => 'find',
            ],
            [
                'name' => 'folha_cheque_recall',
                'path' => '/gerenciar/folha_cheque/recolher',
                'method' => 'GET',
                'controller' => 'recall',
            ],
        ];
    }
}
