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
use MZ\Session\Sessao;
use MZ\Database\DB;
use MZ\Device\Impressora;
use MZ\Device\Dispositivo;
use MZ\Payment\Pagamento;
use MZ\Logger\Log;

use Thermal\Printer;
use Thermal\Connection\Buffer;
use Thermal\Model;
use MZ\Coupon\Order\Receipt;

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
                'datahora',
            ];
            $items = [];
            $servicos = [];
            foreach ($itens as $_pedido) {
                $_pedido['tipo'] = $_pedido['pedidotipo'];
                $item = array_intersect_key($_pedido, array_flip($campos));
                if (is_null($item['servicoid'])) {
                    $item['imagemurl'] = get_image_url($item['imagemurl'], 'produto', null);
                    $items[] = $item;
                } elseif (is_greater($item['preco'], 0)) {
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
                $item = $pagamento->publish();
                $_pagamentos[] = $item;
            }
            $response = [];
            $response['estado'] = $pedido->getEstadoSimples();
            $response['pedidoid'] = $pedido->getID();
            if ($pedido->getClienteID()) {
                $cliente = $pedido->findClienteID();
                $response['cliente'] = $cliente->publish();
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
     * TODO: move to new Api
     */
    public function newCoupon()
    {
        if (!app()->getAuthentication()->isLogin()) {
            return $this->json()->error('Usuário não autenticado!');
        }
        app()->needManager();
        try {
            $pedido = new Pedido($this->getRequest()->query->all());
            $pedido->loadByID();
            if (!$pedido->exists()) {
                throw new \Exception('O pedido informado não existe');
            }
            $pedido->checkAccess(app()->auth->provider);
            if ($pedido->getEstado() != Pedido::ESTADO_FECHADO) {
                $pedido->setFechadorID(app()->auth->provider->getID());
                $pedido->setDataImpressao(DB::now());
                $pedido->setEstado(Pedido::ESTADO_FECHADO);
                $pedido->update();
            }
            $dispositivo = new Dispositivo();
            $dispositivo->setNome($this->getRequest()->query->get('device'));
            $dispositivo->setSerial($this->getRequest()->query->get('serial'));
            $dispositivo->loadBySerial();
            if (!$dispositivo->exists()) {
                throw new \Exception('O dispositivo informado não existe ou não foi validado');
            }
            $impressora = Impressora::find([], [
                'modo' => [-1 => Impressora::MODO_TERMINAL],
                'dispositivoid' => [-1 => $dispositivo->getID()],
                'setorid' => [-1 => $dispositivo->getSetorID()]
            ]);
            if (!$impressora->exists()) {
                throw new \Exception('Nenhuma impressora cadastrada, cadastre uma impressora!');
            }
            $model = new Model($impressora->getModelo());
            $connection = new Buffer();
            $printer = new Printer($model, $connection);
            $printer->setColumns($impressora->getColunas());
            $receipt = new Receipt($printer);
            $receipt->setOrder($pedido);
            $receipt->setItems(Item::findAll(
                [
                    'pedidoid' => $pedido->getID(),
                    'cancelado' => 'N'
                ],
                ['id' => 1]
            ));
            $receipt->setPayments(Pagamento::findAll(
                [
                    'pedidoid' => $pedido->getID(),
                    'cancelado' => 'N'
                ],
                ['id' => 1]
            ));
            $receipt->printCoupon();
            if ($impressora->getAvanco() > 0) {
                $printer->feed($impressora->getAvanco());
            }
            $printer->buzzer();
            $printer->cutter();
            $data = $connection->getBuffer();
            return $this->json()->success([
                'data' => base64_encode($data),
                'printer' => $impressora->getNome(),
                'name' => 'Cupom de Consumo do Pedido #' . $pedido->getID()
            ]);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return $this->json()->error($e->getMessage());
        }
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
