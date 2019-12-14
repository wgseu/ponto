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
use Illuminate\Support\Carbon;
use App\Interfaces\ValidateInterface;
use Illuminate\Database\Eloquent\Model;
use App\Interfaces\ValidateInsertInterface;

/**
 * Avaliação de atendimento e outros serviços do estabelecimento
 */
class Avaliacao extends Model implements
    ValidateInterface,
    ValidateInsertInterface
{
    use ModelEvents;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'avaliacoes';

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
        'metrica_id',
        'cliente_id',
        'pedido_id',
        'produto_id',
        'estrelas',
        'comentario',
        'data_avaliacao',
    ];

    /**
     * Métrica de avaliação
     */
    public function metrica()
    {
        return $this->belongsTo(Metrica::class, 'metrica_id');
    }

    /**
     * Informa o cliente que avaliou esse pedido ou produto, obrigatório quando
     * for avaliação de produto
     */
    public function cliente()
    {
        return $this->belongsTo(Cliente::class, 'cliente_id');
    }

    /**
     * Pedido que foi avaliado, quando nulo o produto deve ser informado
     */
    public function pedido()
    {
        return $this->belongsTo(Pedido::class, 'pedido_id');
    }

    /**
     * Produto que foi avaliado
     */
    public function produto()
    {
        return $this->belongsTo(Produto::class, 'produto_id');
    }

    public function metrics()
    {
        if (!is_null($this->pedido_id) && !is_null($this->produto_id)) {
            $metrica = $this->metrica;
            $total = self::where('produto_id', $this->produto_id)
                ->limit($metrica->quantidade)
                ->orderBy('id', 'DESC')
                ->avg('estrelas');
            $produto = $this->produto;
            $produto->avaliacao = $total;
            $produto->save();
            return;
        }
        $metrica = $this->metrica;
        $estrelas = self::where('metrica_id', $this->metrica_id)
            ->limit($metrica->quantidade)
            ->orderBy('id', 'DESC')
            ->avg('estrelas');
        $metrica->avaliacao = $estrelas / $metrica->quantidade;
        $metrica->save();
    }

    public function validate()
    {
    }

    public function onInsert()
    {
        $errors = [];
        $avaliacao = self::where('pedido_id', $this->pedido_id)->first();
        if (!is_null($avaliacao)) {
            $errors['pedido_id'] = __('messages.order_have_evaluation');
        }
        $pedido = $this->pedido;
        $days = Carbon::now()->diff($pedido->data_criacao)->days;
        if (!is_null($pedido) && $days > 7) {
            $errors['pedido_id'] = __('messages.order_more_week');
        }
        return $errors;
    }
}
