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
 * Comanda individual, permite lançar pedidos em cartões de consumo
 */
class Comanda extends Model implements ValidateInterface
{
    use ModelEvents;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'comandas';

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
        'numero',
        'nome',
        'ativa',
    ];

    /**
     * The model's default values for attributes.
     *
     * @var array
     */
    protected $attributes = [
        'ativa' => true,
    ];

    /**
     * Regras:
     * Uma comanda não pode ser desativada se houver pedidos relacionado a elas que não estejam concluidos ou cancelados
     * Uma comanda não pode ser criada ja desativada.
     */
    public function validate($old)
    {
        $errors = [];
        $old_comanda = $this->fresh();
        if ($this->exists && $old_comanda->ativa && !$this->ativa) {
            $pedido = Pedido::where('comanda_id', $this->id)
                ->where('estado', '<>', Pedido::ESTADO_CONCLUIDO)
                ->where('estado', '<>', Pedido::ESTADO_CANCELADO);
            if ($pedido->exists()) {
                $errors['ativa'] = __('messages.comanda_ativa_open');
            }
        }
        if (!$this->exists && !$this->ativa) {
            $errors['ativa'] = __('messages.comanda_inativa_invalid');
        }
        return $errors;
    }
}
