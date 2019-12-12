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

namespace App\Models;

use App\Concerns\ModelEvents;
use App\Interfaces\ValidateInterface;
use Illuminate\Database\Eloquent\Model;

/**
 * Cardápios para cada integração ou local de venda
 */
class Cardapio extends Model implements ValidateInterface
{
    use ModelEvents;

    /**
     * O cardápio será exibido para vendas nesse local
     */
    public const LOCAL_LOCAL = 'local';
    public const LOCAL_MESA = 'mesa';
    public const LOCAL_COMANDA = 'comanda';
    public const LOCAL_BALCAO = 'balcao';
    public const LOCAL_ENTREGA = 'entrega';
    public const LOCAL_ONLINE = 'online';

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'cardapios';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'cozinha_id',
        'produto_id',
        'composicao_id',
        'pacote_id',
        'cliente_id',
        'integracao_id',
        'local',
        'acrescimo',
        'disponivel',
    ];

    /**
     * The model's default values for attributes.
     *
     * @var array
     */
    protected $attributes = [
        'acrescimo' => 0,
        'disponivel' => true,
    ];

    /**
     * Permite mostrar o cardápio somente dessa cozinha
     */
    public function cozinha()
    {
        return $this->belongsTo(Cozinha::class, 'cozinha_id');
    }

    /**
     * Produto que faz parte desse cardápio
     */
    public function produto()
    {
        return $this->belongsTo(Produto::class, 'produto_id');
    }

    /**
     * Composição que faz parte desse cardápio
     */
    public function composicao()
    {
        return $this->belongsTo(Composicao::class, 'composicao_id');
    }

    /**
     * Pacote que faz parte desse cardápio
     */
    public function pacote()
    {
        return $this->belongsTo(Pacote::class, 'pacote_id');
    }

    /**
     * Permite exibir um cardápio diferenciado somente para esse cliente
     */
    public function cliente()
    {
        return $this->belongsTo(Cliente::class, 'cliente_id');
    }

    /**
     * Permite exibir o cardápio somente nessa integração
     */
    public function integracao()
    {
        return $this->belongsTo(Integracao::class, 'integracao_id');
    }

    /**
     * Regras:
     * Produto, composição, pacote não podem ser selecionados juntos, sendo obrigatória a escolha de um,
     * O Deconto não pode ser superior ao preço de venda do produto,
     * Cliente e integração não podem ser selecinados juntos, escolha opcional.
     */
    public function validate()
    {
        $errors = [];
        $produto = $this->produto;
        $count = 0;
        if (!is_null($this->produto_id)) {
            $count += 1;
        }
        if (!is_null($this->composicao_id)) {
            $count += 1;
        }
        if (!is_null($this->pacote_id)) {
            $count += 1;
        }

        if ($count != 1) {
            $errors['produto_id'] = __('messages.error_selection_multiple_product');
        }
        if (!is_null($produto) && $produto->preco_venda + $this->acrescimo < 0) {
            $errors['acrescimo'] = __('messages.total_cannot_negativo');
        }
        if (!is_null($this->cliente_id) && !is_null($this->integracao_id)) {
            $errors['cliente_id'] = __('messages.cliente_cannot_associated_integracao');
        }
        return $errors;
    }
}
