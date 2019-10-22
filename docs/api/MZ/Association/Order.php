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

namespace MZ\Association;

use MZ\Util\Document;
use MZ\Util\Generator;
use MZ\Util\Filter;
use MZ\Util\Gender;
use MZ\Util\Validator;
use MZ\Database\SyncModel;
use MZ\Database\DB;
use MZ\Location\Localizacao;
use MZ\Location\Cidade;
use MZ\Location\Bairro;
use MZ\Location\Estado;
use MZ\Location\Pais;
use MZ\Payment\FormaPagto;
use MZ\Payment\Pagamento;
use MZ\Payment\Cartao;
use MZ\Account\Cliente;
use MZ\Sale\Pedido;
use MZ\Sale\Item;
use MZ\Sale\Formacao;
use MZ\Sale\Montagem;
use MZ\Product\Servico;
use MZ\Product\Produto;
use MZ\Product\Composicao;
use MZ\Session\Sessao;
use MZ\Session\Movimentacao;
use MZ\Provider\Prestador;
use MZ\Exception\RedirectException;

class Order extends Pedido
{
    /**
     * Customer
     * @var \MZ\Account\Cliente
     */
    private $customer;

    /**
     * Localization
     * @var \MZ\Location\Localizacao
     */
    private $localization;

    /**
     * District
     * @var \MZ\Location\Bairro
     */
    private $district;

    /**
     * City
     * @var \MZ\Location\Cidade
     */
    private $city;

    /**
     * State
     * @var \MZ\Location\Estado
     */
    private $state;

    /**
     * Country
     * @var \MZ\Location\Pais
     */
    private $country;

    /**
     * Products
     * @var \MZ\Product\Produto[]
     */
    private $products = [];

    /**
     * Payments
     * @var \MZ\Payment\Pagamento[]
     */
    private $payments = [];

    /**
     * Funcionário que está fazendo o pedido
     * @var \MZ\Provider\Prestador
     */
    public $employee;

    /**
     * Constructor for a new empty instance of Pedido
     * @param array $pedido All field and values to fill the instance
     */
    public function __construct($order = [])
    {
        parent::__construct($order);
        $this->employee = new Prestador();
    }

    public function loadOrder($data)
    {
        $this->fromArray($data);
        if (isset($data['clienteid'])) {
            $this->customer = Cliente::findByID($data['clienteid']);
            if (!$this->customer->exists()) {
                $this->customer = null;
            }
        } else {
            $this->customer = null;
        }
        $this->setPessoas(1);
        $this->setCancelado('N');
        $this->setEstado(self::ESTADO_ATIVO);
    }

    public function loadData($data)
    {
        $this->loadOrder($data);
        $this->payments = [];
        $this->localization = null;
        $this->district = null;
        $this->city = null;
        $this->state = null;
        $this->country = null;
        $this->products = [];
        $index = 0;
        $parent_index = null;
        $parent_products = [];
        $itens = $data['itens'] ?? [];
        foreach ($itens as $item_array) {
            $item = new Item($item_array);
            if (!is_null($item->getItemID())) {
                if (isset($parent_products[$item->getItemID()])) {
                    $parent_index = $parent_products[$item->getItemID()];
                } elseif (is_null($parent_index)) {
                    throw new \Exception('A ordem dos pedidos enviados é inválida', 500);
                }
                $item->setItemID($parent_index);
            } else {
                if ($item->exists()) {
                    $parent_products[$item->getID()] = $index;
                    $parent_index = null;
                } else {
                    $parent_index = $index;
                }
            }
            $formacoes = [];
            $_formacoes = $item_array['formacoes'] ?? [];
            foreach ($_formacoes as $formacao_array) {
                $formacoes[] = new Formacao($formacao_array);
            }
            $item->setID($index);
            $this->products[$index] = [
                'item' => $item,
                'formacoes' => $formacoes
            ];
            $index++;
        }
    }

    public function searchCustomer()
    {
        if (!is_null($this->customer)) {
            if (!$this->customer->exists() && Validator::checkEmail($this->customer->getEmail())) {
                $cliente = Cliente::findByEmail($this->customer->getEmail());
                if ($cliente->exists()) {
                    $this->customer->fromArray($cliente->toArray());
                }
            }
            if (!$this->customer->exists() && Validator::checkPhone($this->customer->getTelefone()->getNumero())) {
                $cliente = Cliente::findByFone($this->customer->getTelefone()->getNumero());
                if ($cliente->exists()) {
                    $this->customer->fromArray($cliente->toArray());
                }
            }
        }
    }

    public function registerCustomer()
    {
        if (!is_null($this->customer) && !$this->customer->exists()) {
            // todo cliente precisa de uma senha, gera uma aleatória
            $this->customer->setSenha(Generator::token().'a123Z');
            $this->customer->filter(new Cliente(), app()->auth->provider);
            $this->customer->insert();
        }
    }

    public function search()
    {
        $this->searchCustomer();
        $pedido = new Pedido($this);
        if ($pedido->exists()) {
            $pedido->loadByID();
        } elseif (in_array($this->getTipo(), [self::TIPO_MESA, self::TIPO_COMANDA])) {
            $pedido->loadByLocal();
        } elseif (!is_null($this->customer) && $this->customer->exists()) {
            // loadAproximado requires costumer id
            $pedido->setClienteID($this->customer->getID());
            $pedido->loadAproximado();
        }
        if ($pedido->exists()) {
            $this->fromArray($pedido->toArray());
        }
    }

    private function registerAddress()
    {
        $this->country->loadByCodigo();
        $this->state->setPaisID($this->country->getID());
        $this->state->loadByPaisIDUF();
        if (!$this->state->exists()) {
            throw new \Exception(sprintf('O estado de UF "%s" não existe', $this->state->getUF()), 401);
        }
        $this->city->setEstadoID($this->state->getID());
        $find_city = new Cidade($this->city);
        $find_city->loadByEstadoIDNome();
        if (!$find_city->exists()) {
            $this->city->filter(new Cidade(), app()->auth->provider);
            $this->city->insert();
        } else {
            $this->city->fromArray($find_city->toArray());
        }
        $this->district->setCidadeID($this->city->getID());
        $find_district = new Bairro($this->district);
        $find_district->loadByCidadeIDNome();
        if (!$find_district->exists()) {
            foreach ($this->products as $item_info) {
                $item = $item_info['item'];
                if ($item->getServicoID() == Servico::ENTREGA_ID) {
                    $this->district->setValorEntrega($item->getSubtotal());
                    break;
                }
            }
            $this->district->setDisponivel('Y');
            $this->district->filter(new Bairro(), app()->auth->provider);
            $this->district->insert();
        } else {
            $this->district->fromArray($find_district->toArray());
        }
        $this->localization->setClienteID($this->getClienteID());
        $find_localization = new Localizacao($this->localization);
        $find_localization->loadByCEP();
        if (!$find_localization->isSame($this->localization)) {
            $find_localization->fromArray($this->localization->toArray());
            $find_localization->loadByClienteID();
        }
        if ($find_localization->isSame($this->localization)) {
            $this->localization->fromArray($find_localization->toArray());
            return $this;
        }
        $this->localization->setBairroID($this->district->getID());
        $this->localization->insert();
    }

    private function insertProducts()
    {
        $added = 0;
        $pacotes = [];
        $comissao_balcao = is_boolean_config('Vendas', 'Balcao.Comissao');
        $pacote_pedido = new Item();
        foreach ($this->products as $item_info) {
            $item = $item_info['item'];
            $item->setPedidoID($this->getID());
            $item->setPrestadorID($this->employee->getID());
            $item->setPrecoCompra(0);
            $produto = $item->findProdutoID();
            if ($produto->exists()) {
                // se chegou aqui é porque o item é um produto e não serviço
                if (!is_null($item->getItemID())) {
                    $item->setItemID($pacote_pedido->getID());
                    $pacotes[$pacote_pedido->getID()]['itens'][] = $item_info;
                } elseif ($produto->getTipo() != Produto::TIPO_PACOTE) {
                    $pacote_pedido = new Item();
                }
                if (!is_null($produto->getCustoProducao())) {
                    $item->setPrecoCompra($produto->getCustoProducao());
                }
            }
            $item->setEstado(Item::ESTADO_ADICIONADO);
            $item->setCancelado('N');
            $item->setDataVisualizacao(null);
            $item->filter(new Item(), app()->auth->provider); // limpa o ID
            if ($produto->exists() && $produto->isCobrarServico() &&
                (
                    in_array($this->getTipo(), [self::TIPO_MESA, self::TIPO_COMANDA]) ||
                    ($comissao_balcao && $this->getTipo() == self::TIPO_AVULSO)
                )
            ) {
                $item->setComissao(Filter::money(
                    $this->employee->getPorcentagem() / 100 * $item->getPreco(),
                    false
                ));
            } else {
                $item->setComissao(0);
            }
            $item->totalize();
            $this->checkSaldo($item->getTotal());
            $item->register($item_info['formacoes']);
            if ($produto->exists() && $produto->getTipo() == Produto::TIPO_PACOTE) {
                $pacote_pedido = $item;
                $pacote = $item_info;
                $pacote['itens'] = [];
                $pacotes[$pacote_pedido->getID()] = $pacote;
            }
            $added++;
        }
        // percorre todos os pacotes e valida a formação, lançando exceção quando conter erros
        foreach ($pacotes as $pacote) {
            $montagem = new Montagem($pacote['item']);
            $montagem->initialize();
            $montagem->addItem($pacote['item'], $pacote['formacoes']);
            $itens = $pacote['itens'];
            foreach ($itens as $item) {
                $montagem->addItem($item['item'], $item['formacoes']);
            }
            $montagem->validate();
        }
        return $added;
    }

    /**
     * Add items to existing order, create a new order when don't exists
     *
     * @return int quantidade de produtos ou serviços adicionados
     */
    public function process()
    {
        $added = 0;
        $new_order = false;
        if ($this->getTipo() != self::TIPO_MESA && $this->getTipo() != self::TIPO_COMANDA) {
            throw new \Exception('Tipo de lançamento não suportado nessa versão', 500);
        }
        try {
            DB::beginTransaction();
            if ($this->employee->exists()) {
                $this->validaAcesso($this->employee);
            }
            if (!$this->exists()) {
                $sessao = Sessao::findByAberta(true);
                $this->setSessaoID($sessao->getID());
            }
            $this->registerCustomer();
            $viagem = !$this->getLocalizacaoID();
            if (!$this->exists()) {
                // não existe pedido ainda, cadastra um novo
                if (!is_null($this->customer)) {
                    $this->setClienteID($this->customer->getID());
                }
                if (!is_null($this->localization) && !is_null($this->getClienteID())) {
                    $this->registerAddress();
                    $this->setLocalizacaoID($viagem ? null : $this->localization->getID());
                }
                $this->setPrestadorID($this->employee->getID());
                $this->filter(new Pedido(), $this->employee);
                $this->insert();
                $new_order = true;
            }
            $added = $this->insertProducts();
            $paid = 0;
            if (!$viagem) {
                foreach ($this->payments as $pagamento) {
                    $pagamento->setPedidoID($this->getID());
                    $pagamento->insert();
                    $paid++;
                }
            }
            if ($added > 0 && !$new_order && $this->getEstado() != self::ESTADO_ATIVO) {
                if (in_array($this->getTipo(), [self::TIPO_MESA, self::TIPO_COMANDA])) {
                    $this->setEstado(self::ESTADO_ATIVO);
                }
            }
            if ($paid > 0 || $added > 0) {
                $this->totalize();
                $this->update();
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
        return $added;
    }
}
