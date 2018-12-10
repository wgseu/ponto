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
namespace MZ\Sale;

use MZ\Association\Order;
use MZ\Payment\Pagamento;
use MZ\Product\Produto;

/**
 * Allow application to serve system resources
 */
class PedidoOldApiController extends \MZ\Core\ApiController
{
    public function find()
    {
        if (!app()->getAuthentication()->isLogin()) {
            return $this->json()->error('Usuário não autenticado!');
        }
        try {
            $pedido = new Pedido();
            $tipo = Pedido::TIPO_MESA;
            if ($this->getRequest()->query->get('comanda')) {
                $tipo = Pedido::TIPO_COMANDA;
            }
            $pedido->setID($this->getRequest()->query->get('id'));
            $pedido->setTipo($tipo);
            $pedido->setMesaID($this->getRequest()->query->get('mesa'));
            $pedido->setComandaID($this->getRequest()->query->get('comanda'));
            if ($pedido->exists()) {
                $pedido->loadByID();
                $pedido->checkAccess(app()->auth->provider);
            } else {
                $pedido->checkAccess(app()->auth->provider);
                $pedido->loadByLocal();
            }
            $agrupar = $this->getRequest()->query->getBoolean('agrupar', true);
            $group = ['i.id'];
            if ($agrupar) {
                $group = [
                    'i.servicoid',
                    'i.produtoid',
                    'i.preco',
                    'i.detalhes'
                ];
            }
            $itens = Item::rawFindAll(
                [
                    'detalhado' => true,
                    'pedidoid' => $pedido->getID(),
                    'cancelado' => 'N'
                ],
                ['id' => 1],
                null,
                null,
                [],
                $group
            );
            $campos = [
                'id',
                'itemid',
                'produtopedidoid',
                'tipo',
                'mesaid',
                'comandaid',
                'produtoid',
                'servicoid',
                'produtotipo',
                'produtodescricao',
                'produtoabreviacao',
                'produtoconteudo',
                'unidadesigla',
                'preco',
                'quantidade',
                'precovenda',
                'porcentagem',
                'detalhes',
                'descricao',
                'imagemurl',
                'produtodataatualizacao',
                'datalancamento',
            ];
            $items = [];
            $servicos = [];
            foreach ($itens as $_pedido) {
                $_pedido['tipo'] = $_pedido['pedidotipo'];
                $_pedido['produtopedidoid'] = $_pedido['itemid'];
                $item = array_intersect_key($_pedido, array_flip($campos));
                $formacao_pacote = null;
                if ($item['produtotipo'] !== Produto::TIPO_PRODUTO || $item['itemid'] !== null) {
                    $_formacoes = Formacao::findAll(['itemid' => $item['id']]);
                    $formacoes = [];
                    foreach ($_formacoes as $formacao) {
                        if ($formacao->getTipo() == Formacao::TIPO_PACOTE) {
                            $formacao_pacote = $formacao;
                        }
                        $formacoes[] = $formacao->publish(app()->auth->provider);
                    }
                    $item['formacoes'] = $formacoes;
                }
                if ($item['itemid'] !== null && $formacao_pacote !== null) {
                    $pacote = $formacao_pacote->findPacoteID();
                    $grupo = $pacote->findGrupoID();
                    $item['grupoid'] = $grupo->getID();
                    $item['gruponome'] = $grupo->getNome();
                    $item['grupotipo'] = $grupo->getTipo();
                }
                if (is_null($item['servicoid'])) {
                    $item['imagemurl'] = get_image_url($item['imagemurl'], 'produto', null);
                    $items[] = $item;
                } elseif (is_greater($item['preco'], 0)) {
                    $item['imagemurl'] = get_image_url($item['imagemurl'], 'servico', null);
                    $servicos[] = $item;
                }
            }
            $pagamentos = Pagamento::findAll(
                [
                    'pedidoid' => $pedido->getID(),
                    'cancelado' => 'N'
                ],
                ['id' => 1]
            );
            $_pagamentos = [];
            foreach ($pagamentos as $pagamento) {
                $item = $pagamento->publish(app()->auth->provider);
                $_pagamentos[] = $item;
            }
            $response = [];
            $response['estado'] = $pedido->getEstadoSimples();
            $response['pedidoid'] = $pedido->getID();
            if ($pedido->getClienteID()) {
                $cliente = $pedido->findClienteID();
                $response['cliente'] = $cliente->publish(app()->auth->provider);
            }
            $total = $pedido->findTotal();
            $response['produtos'] = $total['produtos'];
            $response['comissao'] = $total['comissao'];
            $response['servicos'] = [
                'total' => $total['servicos'],
                'itens' => $servicos
            ];
            $response['descontos'] = $total['descontos'];
            $response['total'] = $total['total'];
            $response['pagamentos'] = $_pagamentos;
            $response['pago'] = $pedido->findPagamentoTotal();
            $response['pedidos'] = $items;
            return $this->json()->success($response);
        } catch (\Exception $e) {
            return $this->json()->error($e->getMessage());
        }
    }

    public function add()
    {
        app()->needLogin();
        $order = new Order();
        $order->setEmployee(app()->auth->provider);
        try {
            $order->loadData($this->getRequest()->request->all());
            $order->search();
            $order->process();
        } catch (\Exception $e) {
            return $this->json()->error($e->getMessage());
        }
        return $this->json()->success();
    }

    public function coupon()
    {
        return $this->json()->error('Atualize o aplicativo para imprimir a conta');
    }

    /**
     * Get URL patterns associated with callback for use into router
     * @return array List of routes
     */
    public static function getRoutes()
    {
        return [
            [
                'name' => 'app_pedido_find',
                'path' => '/app/pedido/listar',
                'method' => 'GET',
                'controller' => 'find',
            ],
            [
                'name' => 'app_pedido_add',
                'path' => '/app/pedido/',
                'method' => 'POST',
                'controller' => 'add',
            ],
            [
                'name' => 'app_pedido_coupon',
                'path' => '/app/pedido/imprimir',
                'method' => 'GET',
                'controller' => 'coupon',
            ]
        ];
    }
}
