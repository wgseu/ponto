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
use MZ\Core\PageController;

/**
 * Allow application to serve system resources
 */
class ChequePageController extends PageController
{
    public function find()
    {
        $this->needPermission([Permissao::NOME_PAGAMENTO]);

        $limite = max(1, min(100, $this->getRequest()->query->getInt('limite', 10)));
        $condition = Filter::query($this->getRequest()->query->all());
        unset($condition['ordem']);
        $cheque = new Cheque($condition);
        $order = Filter::order($this->getRequest()->query->get('ordem', ''));
        $count = Cheque::count($condition);
        $page = max(1, $this->getRequest()->query->getInt('pagina', 1));
        $pager = new \Pager($count, $limite, $page, 'pagina');
        $pagination = $pager->genPages();
        $cheques = Cheque::findAll($condition, $order, $limite, $pager->offset);

        if ($this->isJson()) {
            $items = [];
            foreach ($cheques as $_cheque) {
                $items[] = $_cheque->publish(app()->auth->provider);
            }
            return $this->json()->success(['items' => $items]);
        }
        $_banco = $cheque->findBancoID();
        $_cliente = $cheque->findClienteID();
        $estados = [
            'N' => 'A compensar',
            'Y' => 'Recolhido',
        ];
        return $this->view('gerenciar_cheque_index', get_defined_vars());
    }

    public function recall()
    {
        $this->needPermission([Permissao::NOME_PAGAMENTO]);
        $id = $this->getRequest()->query->getInt('id', null);
        $cheque = Cheque::findByID($id);
        if (!$cheque->exists()) {
            $msg = 'O cheque não foi informado ou não existe';
            if ($this->isJson()) {
                return $this->json()->error($msg);
            }
            \Thunder::warning($msg);
            return $this->redirect('/gerenciar/cheque/');
        }
        try {
            $cheque->recolher();
            $msg = sprintf('Cheque "%s" compensado com sucesso!', $cheque->getNumero());
            if ($this->isJson()) {
                return $this->json()->success([], $msg);
            }
            \Thunder::success($msg, true);
        } catch (\Exception $e) {
            $msg = sprintf(
                'Não foi possível recolher o cheque "%s"',
                $cheque->getNumero()
            );
            if ($this->isJson()) {
                return $this->json()->error($msg);
            }
            \Thunder::error($msg);
        }
        return $this->redirect('/gerenciar/cheque/');
    }

    /**
     * Get URL patterns associated with callback for use into router
     * @return array List of routes
     */
    public static function getRoutes()
    {
        return [
            [
                'name' => 'cheque_find',
                'path' => '/gerenciar/cheque/',
                'method' => 'GET',
                'controller' => 'find',
            ],
            [
                'name' => 'cheque_recall',
                'path' => '/gerenciar/cheque/recolher',
                'method' => 'GET',
                'controller' => 'recall',
            ],
        ];
    }
}
