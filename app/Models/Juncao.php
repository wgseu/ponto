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
use App\Interfaces\ValidateInsertInterface;
use App\Interfaces\ValidateInterface;
use Illuminate\Database\Eloquent\Model;

/**
 * Junções de mesas, informa quais mesas estão juntas ao pedido
 */
class Juncao extends Model implements
    ValidateInterface,
    ValidateInsertInterface
{
    use ModelEvents;

    /**
     * Estado a junção da mesa. Associado: a mesa está junta ao pedido,
     * Liberado: A mesa está livre, Cancelado: A mesa está liberada
     */
    public const ESTADO_ASSOCIADO = 'associado';
    public const ESTADO_LIBERADO = 'liberado';
    public const ESTADO_CANCELADO = 'cancelado';

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'juncoes';

    public const CREATED_AT = 'data_movimento';
    public const UPDATED_AT = null;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'mesa_id',
        'pedido_id',
        'estado',
    ];

    /**
     * The model's default values for attributes.
     *
     * @var array
     */
    protected $attributes = [
        'estado' => self::ESTADO_ASSOCIADO,
    ];

    /**
     * Mesa que está junta ao pedido
     */
    public function mesa()
    {
        return $this->belongsTo(Mesa::class, 'mesa_id');
    }

    /**
     * Pedido a qual a mesa está junta, o pedido deve ser de uma mesa
     */
    public function pedido()
    {
        return $this->belongsTo(Pedido::class, 'pedido_id');
    }

    public function validate()
    {
        $errors = [];
        $pedido = $this->pedido;
        $juncao = self::where('mesa_id', $this->mesa_id)
            ->where('estado', self::ESTADO_ASSOCIADO)
            ->when($this->exists, function ($query) {
                return $query->where('id', '<>', $this->id);
            });
        $mesa_pedido = Pedido::where('mesa_id', $this->mesa_id)
            ->where('estado', Pedido::ESTADO_ABERTO);
        // Não pode juntar mesa com pedido aberto
        if ($mesa_pedido->exists()) {
            $errors['mesa_id'] = __('messages.mesa_associated_ordem');
        }
        // Não pode juntar uma mesa já associada
        if ($juncao->exists()) {
            $errors['mesa_id'] = __('messages.mesa_id_exists');
        }
        // Não pode juntar uma mesa com ela mesma
        if ($this->mesa_id == $pedido->mesa->id) {
            $errors['mesa_id'] = __('messages.mesa_id_same');
        }
        // Não pode juntar uma mesa com o estado do pedido diferente de aberto
        if ($pedido->estado != Pedido::ESTADO_ABERTO) {
            $errors['pedido_id'] = __('messages.pedido_id_closed');
        }
        // O tipo do pedido deve ser mesa
        if ($pedido->tipo != Pedido::TIPO_MESA) {
            $errors['pedidoid'] = __('messages.pedido_id_incompatible');
        }
        return $errors;
    }

    public function onInsert()
    {
        $errors = [];
        // Não pode criar um junçao o estado diferente de associado
        if ($this->estado != self::ESTADO_ASSOCIADO) {
            $errors['estado'] = __('messages.join_must_be_associated');
        }
        return $errors;
    }
}
