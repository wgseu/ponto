<?php

/**
 * Copyright 2014 da GrandChef - GrandChef Desenvolvimento de Sistemas LTDA
 *
 * Este arquivo é parte do programa GrandChef - Sistema para Gerenciamento de Restaurantes e Afins.
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
 * @author Equipe GrandChef <desenvolvimento@grandchef.com.br>
 */

namespace App\Rules;

use App\Exceptions\Exception;
use App\Models\Item;
use App\Models\Grupo;
use App\Models\Pacote;
use App\Models\Composicao;
use App\Util\Mask;
use App\Util\Number;

/**
 * Montagem de pacote, informa a formação de um pacote
 */
class Montagem extends Item
{
    /**
     * Grupos formados
     *
     * @var array
     */
    private $grupos;

    /**
     * Itens do pedido
     *
     * @var array
     */
    public $itens = [];

    /**
     * Cria um verificador de montagem de pacote
     *
     * @param  array  $attributes
     * @return void
     */
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
    }

    /**
     * Lista de grupos formados
     * @return array lista de grupos
     */
    public function getGrupos()
    {
        return $this->grupos;
    }

    /**
     * Informa a nova lista de grupos formados
     * @param  array $grupos nova list
     * @return self Própria instância
     */
    public function setGrupos($grupos)
    {
        $this->grupos = [];
        foreach ($grupos as $grupo) {
            $this->addGroup($grupo);
        }
        return $this;
    }

    /**
     * Lista de itens após montado
     * @return array lista de itens
     */
    public function getItens()
    {
        return $this->itens;
    }

    /**
     * Informa a lista de itens manualmente, cada item deve ser um array com chaves item e formacoes
     *
     * @param  array $itens nova list
     * @return self
     */
    public function setItens($itens)
    {
        $this->itens = [];
        foreach ($itens as $info) {
            $this->addItem($info['item'], $info['formacoes']);
        }
        return $this;
    }

    /**
     * Adiciona um grupo na montagem do pacote
     *
     * @param Grupo $grupo grupo que será adicionado
     * @return self
     */
    public function addGroup($grupo)
    {
        $this->grupos[$grupo->id] = [
            'grupo' => $grupo,
            'somatorio' => 0,
            'pacotes' => []
        ];
        return $this;
    }

    /**
     * Adiciona um pacote em um grupo e pré-calcula mínimos e máximos
     * @param Pacote $pacote pacote que será adicionado
     * @param array $composicoes lista de composições adicionadas e retiradas do pacote
     * @param int $item_index índice do item ao qual esse pacote foi associado
     */
    private function addPacote($pacote, $composicoes, $item_index)
    {
        $item = $this->itens[$item_index]['item'];
        // TODO: verificar se o pacote está ativo
        $produto = $pacote->produto;
        // verifica se a propriedade ou o produto está em algum grupo do pacote
        if (!isset($this->grupos[$pacote->grupo_id])) {
            if (is_null($pacote->propriedade_id)) {
                $msg_key = 'messages.product_not_in_package';
                $descricao = $produto->descricao;
            } else {
                $msg_key = 'messages.property_not_in_package';
                $propriedade = $pacote->propriedade;
                $descricao = $propriedade->nome;
            }
            $produto_pacote = $this->produto;
            throw new Exception(__(
                $msg_key,
                [
                    'item' => $descricao,
                    'package' => $produto_pacote->descricao
                ]
            ));
        }
        $grupo = $this->grupos[$pacote->grupo_id]['grupo'];
        if ($grupo->tipo == Grupo::TIPO_INTEIRO) {
            // retorna a quantidade para unitária
            $minimo = $this->quantidade;
        } else {
            if (isset($this->grupos[$pacote->grupo_id]['minimo'])) {
                $minimo = $this->grupos[$pacote->grupo_id]['minimo'];
                $minimo = min($item->quantidade, $minimo);
            } else {
                $minimo = $item->quantidade;
            }
        }
        $preco = $produto->preco_venda + $pacote->acrescimo;
        if (isset($this->grupos[$pacote->grupo_id]['menor'])) {
            $menor = $this->grupos[$pacote->grupo_id]['menor'];
            $menor = min($preco, $menor);
        } else {
            $menor = $preco;
        }
        if (isset($this->grupos[$pacote->grupo_id]['maior'])) {
            $maior = $this->grupos[$pacote->grupo_id]['maior'];
            $maior = max($preco, $maior);
        } else {
            $maior = $preco;
        }
        $this->grupos[$pacote->grupo_id]['minimo'] = $minimo;
        $this->grupos[$pacote->grupo_id]['menor'] = $menor;
        $this->grupos[$pacote->grupo_id]['maior'] = $maior;
        $this->grupos[$pacote->grupo_id]['pacotes'][] = [
            'pacote' => $pacote,
            'preco' => $preco,
            'adicional' => $composicoes['preco'],
            'composicoes' => $composicoes['itens'],
            'item_index' => $item_index
        ];
    }

    /**
     * Adiciona um item e suas formações à montagem do pacote
     *
     * @param Item $item item do pacote
     * @param array $formacoes lista de formações do item
     * @param bool $principal informa se o item é o item principal
     *
     * @return self
     */
    public function addItem($item, $formacoes = [], $principal = false)
    {
        $item_index = count($this->itens);
        // reindexa as formações para facilitar a busca na validação
        $formacoes_indexed = [];
        foreach ($formacoes as $formacao) {
            if (!is_null($formacao->composicao_id)) {
                $formacoes_indexed['c' . $formacao->composicao_id] = $formacao;
            } else {
                $formacoes_indexed['p' . $formacao->pacote_id] = $formacao;
            }
        }
        $this->itens[$item_index] = [
            'item' => $item,
            'formacoes' => $formacoes_indexed,
        ];
        $pacote_principal = null;
        $preco_composicoes = 0;
        $composicoes = [];
        foreach ($formacoes as $formacao) {
            if (!is_null($formacao->composicao_id)) {
                $composicao = $formacao->composicao;
                // TODO: verificar se a composição está ativa e não é uma composição básica
                if ($composicao->tipo == Composicao::TIPO_OPCIONAL) {
                    $preco_composicoes -= $composicao->valor;
                } else {
                    $preco_composicoes += $composicao->valor;
                }
                $composicoes[] = $composicao;
            } else {
                $pacote = $formacao->pacote;
                if (is_null($pacote->propriedade_id)) {
                    if ($pacote_principal !== null) {
                        throw new Exception(__t('messages.combo_too_many_formation'));
                    }
                    $pacote_principal = $pacote;
                    continue;
                }
                $this->addPacote($pacote, ['itens' => [], 'preco' => 0], $item_index);
            }
        }
        if (!isset($pacote_principal)) {
            if (count($composicoes) > 0) {
                throw new Exception(__('messages.property_cannot_have_composition'));
            }
            // ignora o pacote principal quando não tem propriedades
            if (count($formacoes) == 0 && !$principal) {
                $produto = $item->produto;
                throw new Exception(__(
                    'messages.no_formation_found',
                    ['name' => $produto->descricao]
                ));
            }
            return $this;
        }
        $this->addPacote(
            $pacote_principal,
            ['itens' => $composicoes, 'preco' => $preco_composicoes],
            $item_index
        );
        return $this;
    }

    /**
     * Carrega os grupos montando a estrutura para validação e filtragem do pacote
     */
    public function initialize()
    {
        $this->setGrupos($this->produto->grupos);
    }

    /**
     * Calcula as quantidades e somatórios de cada pacote e grupo formado com base no item do pedido
     */
    protected function quantify()
    {
        foreach ($this->grupos as $grupo_index => $grupo_formado) {
            $grupo = $grupo_formado['grupo'];
            $pacotes = $grupo_formado['pacotes'];
            $acumulado = 0;
            foreach ($pacotes as $pacote_index => $produto_formado) {
                $pacote = $produto_formado['pacote'];
                $item_index = $produto_formado['item_index'];
                $item = $this->itens[$item_index]['item'];
                $minimo = $grupo_formado['minimo'];
                $quantidade = round($item->quantidade / $minimo);
                // TODO: Não corrigir a quantidade, mas sim, lançar uma exceção
                $quantidade = max($quantidade, $pacote->quantidade_minima);
                if ($pacote->quantidade_maxima > 0) {
                    $quantidade = min($quantidade, $pacote->quantidade_maxima);
                }
                $acumulado += $quantidade;
                $this->grupos[$grupo_index]['pacotes'][$pacote_index]['quantidade'] = $quantidade;
            }
            $this->grupos[$grupo_index]['quantidade'] = $acumulado;
        }
    }

    /**
     * Verifica a formação de um item e retorna a descrição dele
     *
     * @param int $index índice do item que será verificado suas formações
     * @param array $pacotes lista de pacotes formados que será verificado
     * @param \Closure $callback função que será chamada para cada formação do item
     * @return string descrição do item principal ou nulo para os outros itens
     */
    private function checkFormacao($index, $pacotes, $callback)
    {
        $descricao = null;
        $formacoes = $this->itens[$index]['formacoes'];
        foreach ($pacotes as $produto_formado) {
            $composicoes = $produto_formado['composicoes'];
            $pacote = $produto_formado['pacote'];
            foreach ($composicoes as $composicao) {
                $key = 'c' . $composicao->id;
                if (!isset($formacoes[$key])) {
                    $produto_composicao = $composicao->produto;
                    $produto = $pacote->produto;
                    $produto_pacote = $this->produto;
                    throw new Exception(__(
                        'messages.subitem_formation_not_found',
                        [
                            'composition' => $produto_composicao->abreviado(),
                            'product' => $produto->descricao,
                            'package' => $produto_pacote->descricao,
                        ]
                    ));
                }
                $formacao = $formacoes[$key];
                $callback($formacao, 1);
                unset($formacoes[$key]);
            }
            $key = 'p' . $pacote->id;
            if (!isset($formacoes[$key])) {
                if (is_null($pacote->propriedade_id)) {
                    $msg_key = 'messages.package_no_product_formation';
                    $item_descricao = $produto->descricao;
                } else {
                    $msg_key = 'messages.package_no_property_formation';
                    $propriedade = $pacote->propriedade;
                    $item_descricao = $propriedade->nome;
                }
                $produto_pacote = $this->produto;
                throw new Exception(__(
                    $msg_key,
                    [
                        'name' => $item_descricao,
                        'package' => $produto_pacote->descricao,
                    ]
                ));
            }
            $multiplicador = $produto_formado['quantidade'];
            $formacao = $formacoes[$key];
            $callback($formacao, $multiplicador);
            unset($formacoes[$key]);
            if ($pacote->propriedade_id !== null) {
                $propriedade = $pacote->propriedade;
                $descricao .= ' ' . $propriedade->abreviado();
            }
        }
        if (count($formacoes) > 0) {
            throw new Exception(__('messages.exceding_formation'));
        }
        return $descricao;
    }

    /**
     * Atualiza as informações do item com base na formação
     *
     * @param int $index índice do item que será atualizado suas informações
     * @param float $quantidade quantidade calculada do item
     * @param float $preco preço de venda calculado do item
     * @param array $pacotes lista de pacotes formados
     */
    private function fix($index, $quantidade, $preco, $pacotes)
    {
        $item = $this->itens[$index]['item'];
        $item->quantidade = $quantidade;
        $item->preco = $preco;
        $item->preco_venda = $preco;
        $descricao = $this->checkFormacao($index, $pacotes, function ($formacao, $quantidade) {
            // corrige a formação
            $formacao->quantidade = $quantidade;
        });
        if ($index == 0) {
            $produto = $item->produto;
            $item->descricao = $produto->descricao . $descricao;
        }
    }

    /**
     * Verifica se o item está montado corretamente no pacote
     *
     * @param int $index índice do item que será verificado
     * @param float $quantidade quantidade calculada do item
     * @param float $preco preço de venda calculado do item
     * @param array $pacotes lista de pacotes formados
     */
    protected function check($index, $quantidade, $preco, $pacotes)
    {
        $item = $this->itens[$index]['item'];
        if (!Number::isEqual($item->quantidade, $quantidade, 0.00005)) {
            $produto = $item->produto;
            throw new Exception(__(
                'messages.item_incorrect_quantity',
                [
                    'name' => $produto->descricao,
                    'expected' => $quantidade,
                    'given' => $item->quantidade,
                ]
            ));
        }
        if (!Number::isEqual($item->preco, $preco)) {
            $produto = $item->produto;
            throw new Exception(__(
                'messages.item_incorrect_price',
                [
                    'name' => $produto->descricao,
                    'expected' => Mask::money($preco, true),
                    'given' => Mask::money($item->preco, true),
                ]
            ));
        }
        $item->preco_venda = $preco;
        $descricao = $this->checkFormacao($index, $pacotes, function ($formacao, $quantidade) {
            if (!Number::isEqual($formacao->quantidade, $quantidade, 0.00005)) {
                throw new Exception(__('messages.formation_incorrect_quantity'));
            }
        });
        if ($index == 0) {
            $produto = $item->produto;
            $descricao = $produto->descricao . $descricao;
            if ($descricao != $item->descricao) {
                throw new Exception(__(
                    'messages.package_incorrect_description',
                    [
                        'given' => $item->descricao,
                        'expected' => $descricao,
                    ]
                ));
            }
        }
    }

    /**
     * Valida a montagem do pacote e lança erros casos existam
     *
     * @return void
     */
    public function verify()
    {
        $this->process($this->check);
    }

    /**
     * Filtra o pacote montado ajustando as quantidade e valores
     *
     * @return void
     */
    public function filter()
    {
        $this->process($this->fix);
    }

    /**
     * Filtra o pacote montado ajustando as quantidade e valores
     *
     * @param \Closure $callback função que será chamada para atribuir o preço e quantidade no item
     *
     * @return void
     */
    protected function process($callback)
    {
        $this->quantify();
        // inicia o item principal
        $produto = $this->produto;
        $preco_principal = $produto->preco_venda;
        $produtos_formados = [];
        foreach ($this->grupos as $grupo_formado) {
            $grupo = $grupo_formado['grupo'];
            $pacotes = $grupo_formado['pacotes'];
            $acumulado = $grupo_formado['quantidade'];
            foreach ($pacotes as $produto_formado) {
                $pacote = $produto_formado['pacote'];
                if ($grupo->funcao == Grupo::FUNCAO_MAXIMO) {
                    $preco = $grupo_formado['maior'];
                } elseif ($grupo->funcao == Grupo::FUNCAO_MINIMO) {
                    $preco = $grupo_formado['menor'];
                } else {
                    // soma e média
                    $preco = $produto_formado['preco'];
                }
                $preco += $produto_formado['adicional'];
                $multiplicador = $produto_formado['quantidade'];
                if ($grupo->tipo == Grupo::TIPO_FRACIONADO) {
                    $multiplicador = $multiplicador / $acumulado;
                }
                $quantidade = $multiplicador * $this->quantidade;
                if ($pacote->propriedade_id === null) {
                    $item_index = $produto_formado['item_index'];
                    $callback($item_index, $quantidade, $preco, [$produto_formado]);
                } else {
                    $preco_principal += $preco * $multiplicador;
                    $produtos_formados[] = $produto_formado;
                }
            }
            if ($grupo->tipo == Grupo::TIPO_FRACIONADO) {
                $quantidade = count($pacotes);
            } else {
                $quantidade = $acumulado;
            }
            if ($quantidade < $grupo->quantidade_minima) {
                throw new Exception(__(
                    'messages.group_need_itens',
                    [
                        'min' => $grupo->quantidade_minima,
                        'found' => $quantidade,
                        'group' => $grupo->descricao,
                        'package' => $produto->descricao,
                    ]
                ));
            }
            if ($quantidade > $grupo->quantidade_maxima && $grupo->quantidade_maxima > 0) {
                throw new Exception(__(
                    'messages.group_max_items_exceded',
                    [
                        'group' => $grupo->descricao,
                        'max' => $grupo->quantidade_maxima,
                        'found' => $quantidade,
                        'package' => $produto->descricao
                    ]
                ));
            }
        }
        // aplica o preço no item principal
        $callback(0, $this->quantidade, $preco_principal, $produtos_formados);
    }
}
