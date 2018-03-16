<?php
/*
	Copyright 2016 da MZ Software - MZ Desenvolvimento de Sistemas LTDA
	Este arquivo é parte do programa GrandChef - Sistema para Gerenciamento de Churrascarias, Bares e Restaurantes.
	O GrandChef é um software proprietário; você não pode redistribuí-lo e/ou modificá-lo.
	DISPOSIÇÕES GERAIS
	O cliente não deverá remover qualquer identificação do produto, avisos de direitos autorais,
	ou outros avisos ou restrições de propriedade do GrandChef.

	O cliente não deverá causar ou permitir a engenharia reversa, desmontagem,
	ou descompilação do GrandChef.

	PROPRIEDADE DOS DIREITOS AUTORAIS DO PROGRAMA

	GrandChef é a especialidade do desenvolvedor e seus
	licenciadores e é protegido por direitos autorais, segredos comerciais e outros direitos
	de leis de propriedade.

	O Cliente adquire apenas o direito de usar o software e não adquire qualquer outros
	direitos, expressos ou implícitos no GrandChef diferentes dos especificados nesta Licença.
*/
namespace MZ\Association;

use MZ\Util\Document;
use MZ\Util\Filter;
use MZ\Database\Helper;

class Order extends \ZPedido
{
    /**
     * Integration
     */
    private $integracao;
    /**
     * Customer
     */
    private $customer;
    /**
     * Localization
     */
    private $localization;
    /**
     * District
     */
    private $district;
    /**
     * City
     */
    private $city;
    /**
     * State
     */
    private $state;
    /**
     * Country
     */
    private $country;
    /**
     * Products
     */
    private $products;
    /**
     * Integration order code
     */
    private $code;

    public function __construct($integracao) {
        parent::__construct();
        $this->integracao = $integracao;
    }

    public function loadDOM($dom)
    {
        $dados = $this->integracao->read();
        $cartoes = isset($dados['cartoes'])?$dados['cartoes']:array();
        $produtos = isset($dados['produtos'])?$dados['produtos']:array();
        $response = $dom->documentElement;
        if (is_null($response)) {
            throw new \Exception('Root element not found in XML file', 401);
        }
        $body_nodes = $response->getElementsByTagName('response-body');
        $body_pedido = null;
        $body_list = null;
        foreach ($body_nodes as $body) {
            $attr_value = $body->getAttribute('class');
            if ($attr_value == 'pedido') {
                $body_pedido = $body;
            } elseif ($attr_value == 'list') {
                $body_list = $body;
            }
        }
        if (is_null($body_pedido)) {
            throw new \Exception('Order node not found in XML file', 401);
        }
        if (is_null($body_list)) {
            throw new \Exception('Product list node not found in XML file', 401);
        }
        $this->code = Document::childValue($body_pedido, 'idPedidoCurto');
        $obs = Document::childValue($body_pedido, 'obsPedido');
        // $obs = str_replace("\r", '', str_replace("\n", ', ', trim($obs)));
        $this->setDescricao($obs);
        $data_criacao = Document::childValue($body_pedido, 'dataPedidoComanda');
        $this->setDataCriacao(Helper::now($data_criacao));
        $this->setTipo(\PedidoTipo::ENTREGA);
        $this->setEstado(\PedidoEstado::AGENDADO);
        // agenda para o mesmo horário do pedido
        $this->setDataAgendamento($this->getDataCriacao());
        $this->customer = new \ZCliente();
        $nome = Document::childValue($body_pedido, 'nome');
        $this->customer->setNomeCompleto(Filter::name($nome));
        $email = Document::childValue($body_pedido, 'email');
        $this->customer->setEmail(strtolower($email));
        $telefones_node = Document::findChild($body_pedido, 'telefones', false);
        if (!is_null($telefones_node)) {
            $index = 1;
            $telefone_list = $telefones_node->getElementsByTagName('telefone');
            foreach ($telefone_list as $telefone) {
                $ddd = Document::childValue($telefone, 'ddd');
                $numero = Document::childValue($telefone, 'numero');
                $this->customer->setFone($index, $ddd . $numero);
                $index++;
                if ($index > 2) {
                    break;
                }
            }
        }
        $entregar = Document::childValue($body_pedido, 'togo');
        if ($entregar == 'false') {
            $this->localization = new \ZLocalizacao();
            $this->localization->setCEP(Document::childValue($body_pedido, 'cep'));
            $this->localization->setTipo(\LocalizacaoTipo::CASA);
            $this->localization->setLogradouro(Document::childValue($body_pedido, 'logradouro'));
            $this->localization->setNumero(Document::childValue($body_pedido, 'logradouroNum'));
            $this->localization->setComplemento(Document::childValue($body_pedido, 'complemento', false));
            $this->localization->setReferencia(Document::childValue($body_pedido, 'referencia', false));
            $this->localization->setMostrar('Y');

            $this->district = new \ZBairro();
            $this->district->setNome(Document::childValue($body_pedido, 'bairro'));
            $this->district->setValorEntrega(floatval(Document::childValue($body_pedido, 'vlrTaxa')));

            $this->city = new \ZCidade();
            $this->city->setNome(Document::childValue($body_pedido, 'cidade'));

            $this->state = new \ZEstado();
            $this->state->setUF(Document::childValue($body_pedido, 'estado'));

            $this->country = new \ZPais();
            // $this->country->setCodigo(Document::childValue($body_pedido, 'pais'));

        }
        //TODO
        // foreach ($itens as $item) {
        //     $codigo = $item->getElementsByTagName('codCardapio')->item(0)->nodeValue;
        //     $temp = $item->getElementsByTagName('codProdutoPdv');
        //     $codigo_pdv = $temp->length > 0?$temp->item(0)->nodeValue:null;
        //     $temp = $item->getElementsByTagName('codPai');
        //     $codigo_pai = $temp->length > 0?$temp->item(0)->nodeValue:null;
        //     $descricao = $item->getElementsByTagName('descricaoCardapio')->item(0)->nodeValue;
        // }
    }

    public function load($data)
    {
        //TODO
    }

    public function findOrder()
    {
        //TODO
    }

    public function process()
    {
        //TODO
    }

    public function store()
    {
        $changes = array();
        //TODO
        $changes[] = array('code' => 2981, 'estado' => \PedidoEstado::AGENDADO);
        return $changes;
    }

    public function changes($limit = null)
    {
        //TODO
    }

    public function apply($updates)
    {
        //TODO
        return count($updates) > 0;
    }
}
