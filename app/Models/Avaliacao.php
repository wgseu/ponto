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
use App\Interfaces\AfterSaveInterface;
use App\Interfaces\BeforeSaveInterface;
use App\Interfaces\ValidateInsertInterface;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Model;
use App\Interfaces\ValidateInterface;

/**
 * Avaliação de atendimento e outros serviços do estabelecimento
 */
class Avaliacao extends Model implements
    BeforeSaveInterface,
    ValidateInsertInterface,
    ValidateInterface,
    AfterSaveInterface
{
    use ModelEvents;

    public const UPDATED_AT = null;
    public const CREATED_AT = 'data_avaliacao';

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'avaliacoes';

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
        'publico',
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

    /**
     * Reavalia a métrica e resumo ou o produto
     *
     * @return void
     */
    public function reassess()
    {
        if (is_null($this->metrica_id)) {
            // avaliação resumo
            return;
        }
        $metrica = $this->metrica;
        if (!is_null($this->produto_id)) {
            $total = self::where('produto_id', $this->produto_id)
                ->limit($metrica->quantidade)
                ->orderBy('id', 'DESC')
                ->avg('estrelas');
            $produto = $this->produto;
            $produto->update(['avaliacao' => $total]);
        }
        $estrelas = self::where('metrica_id', $this->metrica_id)
            ->limit($metrica->quantidade)
            ->orderBy('id', 'DESC')
            ->avg('estrelas');
        $metrica->update(['avaliacao' => $estrelas]);

        // força recálculo da avaliação resumida
        $avaliacao_resumo = self::where('pedido_id', $this->pedido_id)
            ->whereNull('metrica_id')->first();
        if (!is_null($avaliacao_resumo)) {
            $avaliacao_resumo->save();
        }
    }

    public function beforeSave($old)
    {
        if (!is_null($this->metrica_id)) {
            // não é avaliação resumo
            return;
        }
        $this->estrelas = self::where('pedido_id', $this->pedido_id)
            ->whereNotNull('metrica_id')
            ->avg('estrelas');
    }

    public function onInsert()
    {
        if (
            !is_null($this->produto_id) &&
            !$this->pedido->itens()->where('produto_id', $this->produto_id)->exists()
        ) {
            return ['pedido_id' => __('messages.cannot_evaluate_non_buy_product')];
        }
    }

    public function validate($old)
    {
        if (is_null($this->metrica_id) && !is_null($this->produto_id)) {
            return ['pedido_id' => __('messages.product_have_no_metric')];
        }
        $query = self::where('pedido_id', $this->pedido_id);
        if (is_null($this->metrica_id)) {
            $query->whereNull('metrica_id');
        } else {
            $query->where('metrica_id', $this->metrica_id);
        }
        if (is_null($this->produto_id)) {
            $query->whereNull('produto_id');
        } else {
            $query->where('produto_id', $this->produto_id);
        }
        if (!is_null($old)) {
            $query->where('id', '<>', $old->id);
        }
        if ($query->exists()) {
            return ['pedido_id' => __('messages.order_have_evaluation')];
        }
        if (!is_null($old) && !$this->isChangeAllowed(['comentario', 'publico', 'estrelas'])) {
            return ['id' => __('messages.evaluation_change_not_allowed')];
        }
        $pedido = $this->pedido;
        if ($pedido->cancelled()) {
            return ['pedido_id' => __('messages.cannot_evaluate_cancelled_order')];
        }
        if (!$pedido->closed()) {
            return ['pedido_id' => __('messages.cannot_evaluate_open_order')];
        }
        $days = Carbon::now()->diff($pedido->data_criacao)->days;
        if ($days > 3) {
            return ['pedido_id' => __('messages.cannot_evaluate_more_3_days')];
        }
    }

    public function afterSave($old)
    {
        $this->reassess();
    }
}
