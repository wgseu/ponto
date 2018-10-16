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

use MZ\System\Permissao;
use MZ\Util\Filter;
use MZ\Device\Impressora;
use MZ\Device\Dispositivo;
use MZ\Coupon\Service\Order as ServiceOrder;

use Thermal\Printer;
use Thermal\Connection\Buffer;
use Thermal\Model;

/**
 * Allow application to serve system resources
 */
class ItemApiController extends \MZ\Core\ApiController
{
    /**
     * Simple memory cache
     * @var array
     */
    private $cache = [];

    /**
     * @param Item $item
     * @return \MZ\Product\Produto
     */
    public function findProduct($item)
    {
        if (isset($this->cache['products'][$item->getProdutoID()])) {
            return $this->cache['products'][$item->getProdutoID()];
        }
        $product = $item->findProdutoID();
        $this->cache['products'][$item->getProdutoID()] = $product;
        return $product;
    }

    /**
     * @param int $setor_id
     * @return \MZ\Environment\Setor
     */
    public function findSector($setor_id)
    {
        if (isset($this->cache['sectors'][$setor_id])) {
            return $this->cache['sectors'][$setor_id];
        }
        $sector = \MZ\Environment\Setor::findByID($setor_id);
        $this->cache['sectors'][$setor_id] = $sector;
        return $sector;
    }

    /**
     * Build printer jobs for not printed order items
     * @Put("/api/itens/jobs/{id}", name="api_item_jobs", params={ "id": "\d+" })
     */
    public function jobs($id)
    {
        $pedido = Pedido::findOrFail(['id' => $id]);
        $pedido->checkAccess(app()->auth->provider);
        $condition = [
            'pedidoid' => $pedido->getID(),
            'servicoid' => null,
            'estado' => Item::ESTADO_ADICIONADO,
            'cancelado' => 'N'
        ];
        $itens = Item::findAll($condition, ['id' => 1]);
        $docs = [];
        $setores = [];
        $ids = [];
        $affected_items = [];
        $data = $this->getData();
        $dispositivo = new Dispositivo();
        $dispositivo->setNome($data['device']);
        $dispositivo->setSerial($data['serial']);
        $dispositivo->loadBySerial();
        if (!$dispositivo->exists()) {
            throw new \Exception('O dispositivo informado não existe ou não foi validado');
        }
        foreach ($itens as $item) {
            $produto = $this->findProduct($item);
            if (is_null($produto->getSetorPreparoID())) {
                continue;
            }
            $affected_items[] = $item;
            $setores[$produto->getSetorPreparoID()][] = $item;
            $ids[$produto->getSetorPreparoID()][] = $item->getID();
        }
        $grupos = [];
        foreach ($setores as $setor_id => $itens) {
            $setor = $this->findSector($setor_id);
            $impressora = Impressora::find([], [
                'modo' => [-1 => Impressora::MODO_SERVICO],
                'dispositivoid' => [-1 => $dispositivo->getID()],
                'setorid' => [-1 => $setor->getID()]
            ]);
            if (!$impressora->exists()) {
                throw new \Exception('Nenhuma impressora cadastrada, cadastre uma impressora!');
            }
            $ticket = $impressora->hasOption(Impressora::OPCAO_TICKET);
            if ($ticket) {
                foreach ($itens as $item) {
                    $ordem = 0;
                    $restante = $item->getQuantidade();
                    $affected = [$item->getID()];
                    while ($restante > 0) {
                        $ordem++;
                        $anterior = $restante;
                        $restante = max(0, $restante - 1);
                        $item_unitario = new Item($item);
                        $item_unitario->setQuantidade($anterior - $restante);
                        $token = \sha1('GrandChef(' .
                            $item->getID() . ':' .
                            $pedido->getID() . ':' .
                            $item->getProdutoID() . ':' .
                            $item_unitario->getQuantidade() . ':' .
                            $ordem . ')'
                        );
                        $token = \strtoupper(\substr($token, 16, 6));
                        $grupos[] = [
                            'itens' => [$item_unitario],
                            'impressora' => $impressora,
                            'setor' => $setor,
                            'ticket' => $ticket,
                            'affected' => $affected,
                            'ordem' => $ordem,
                            'token' => $token,
                        ];
                        $affected = [];
                    }
                }
            } else {
                $grupos[] = [
                    'itens' => $itens,
                    'impressora' => $impressora,
                    'setor' => $setor,
                    'ticket' => $ticket,
                    'affected' => $ids[$setor->getID()],
                    'ordem' => null,
                    'token' => null,
                ];
            }
        }
        foreach ($grupos as $grupo) {
            $impressora = $grupo['impressora'];
            $setor = $grupo['setor'];
            $model = new Model($impressora->getModelo());
            $connection = new Buffer();
            $printer = new Printer($model, $connection);
            $printer->setColumns($impressora->getColunas());
            $service = new ServiceOrder($printer);
            $service->setCache($this->cache);
            $service->setOrder($pedido);
            $service->setItems($grupo['itens']);
            $service->setSector($setor);
            $service->setTicket($grupo['ticket']);
            $service->setNumber($grupo['ordem']);
            $service->setToken($grupo['token']);
            $service->printCoupon();
            if ($impressora->getAvanco() > 0) {
                $printer->feed($impressora->getAvanco());
            }
            if (!$impressora->hasOption(Impressora::OPCAO_SEM_BEEP)) {
                $printer->buzzer();
            }
            if ($impressora->hasOption(Impressora::OPCAO_CORTAR)) {
                $printer->cutter();
            }
            $buffer = $connection->getBuffer();
            $docs[] = [
                'data' => base64_encode($buffer),
                'affected' => $grupo['affected'],
                'printer' => $impressora->getNome(),
                'name' => 'Cupom de Serviço do(a) ' . $setor->getNome() . ' do Pedido #' . $pedido->getID(),
            ];
        }
        foreach ($affected_items as $item) {
            $item->setEstado(Item::ESTADO_ENVIADO);
            $item->update();
        }
        return $this->getResponse()->success(['docs' => $docs]);
    }

    /**
     * Uncheck order items marked as printed
     * @Put("/api/itens/uncheck", name="api_item_uncheck")
     */
    public function uncheck()
    {
        $data = $this->getData();
        $affected = $data['affected'];
        $pedido = new Pedido();
        foreach ($affected as $item_id) {
            $item = Item::findByID($item_id);
            if ($pedido->getID() != $item->getPedidoID()) {
                $pedido = $item->findPedidoID();
                $pedido->checkAccess(app()->auth->provider);
            }
            $item->setEstado(Item::ESTADO_ADICIONADO);
            $item->update();
        }
        return $this->getResponse()->success();
    }
}
