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
use App\Interfaces\ValidateUpdateInterface;
use Illuminate\Database\Eloquent\Model;

/**
 * Informa o horário de funcionamento do estabelecimento
 */
class Horario extends Model implements ValidateInterface, ValidateUpdateInterface, ValidateInsertInterface
{
    use ModelEvents;

    /**
     * Modo de trabalho disponível nesse horário, Funcionamento: horário em que
     * o estabelecimento estará aberto, Operação: quando aceitar novos pedidos
     * locais, Entrega: quando aceita ainda pedidos para entrega
     */
    public const MODO_FUNCIONAMENTO = 'funcionamento';
    public const MODO_OPERACAO = 'operacao';
    public const MODO_ENTREGA = 'entrega';

    /**
     * Total number of minutes in one day
     */
    public const MINUTES_PER_DAY = 1440;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'horarios';

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
        'modo',
        'funcao_id',
        'prestador_id',
        'inicio',
        'fim',
        'mensagem',
        'entrega_minima',
        'entrega_maxima',
        'fechado',
    ];

    /**
     * The model's default values for attributes.
     *
     * @var array
     */
    protected $attributes = [
        'modo' => self::MODO_FUNCIONAMENTO,
        'entrega_maxima' => 0,
        'fechado' => false,
    ];

    /**
     * Permite informar o horário de acesso ao sistema para realizar essa
     * função
     */
    public function funcao()
    {
        return $this->belongsTo('App\Models\Funcao', 'funcao_id');
    }

    /**
     * Permite informar o horário de prestação de serviço para esse prestador
     */
    public function prestador()
    {
        return $this->belongsTo('App\Models\Prestador', 'prestador_id');
    }

    /**
     * Regras:
     * Função e prestador não podem ser selecionados juntos,
     * A data de inicio não pode ser maior que o fim,
     * O inicio, o fim, a entrega minima, e a entrega maxima não podem ser negativo,
     * A entrega minima não pode ser maior que a entrega maxima,
     * Horário de inicio e fim não podem ser sobrescritos com o mesmo modo, função, ou prestador,
     * Para o fechamento o modo deve ser funcionamento e não pode haver função e prestador selecionadas.
     */
    public function validate()
    {
        $errors = [];
        if (!is_null($this->funcao_id) && !is_null($this->prestador_id)) {
            $errors['funcao_id'] = __('messages.multiple_selections');
        }
        if ($this->inicio >= $this->fim) {
            $errors['inicio'] = __('messages.invalid_interval_funcionamento');
        } elseif (!$this->fechado && $this->inicio < self::MINUTES_PER_DAY) {
            $errors['inicio'] = __('messages.inicio_invalid');
        } elseif (!$this->fechado && $this->fim >= self::MINUTES_PER_DAY * 8) {
            $errors['fim'] = __('messages.fim_invalid');
        }
        if (
            !is_null($this->entrega_minima)
            && $this->entrega_minima > $this->entrega_maxima
            && $this->entrega_maxima != 0
        ) {
            $errors['entrega_minima'] = __('messages.interval_entrega_invalid');
        } elseif (!is_null($this->entrega_minima) && $this->entrega_minima < 0) {
            $errors['entrega_minima'] = __('messages.entrega_minima_cannot_negative');
        }
        if (
            $this->fechado
            && ((!is_null($this->funcao_id)
            || !is_null($this->prestador_id))
            || $this->modo != self::MODO_FUNCIONAMENTO
            )
        ) {
            $errors['entrega_maxima'] = __('messages.close_invalid');
        }
        return $errors;
    }

    public function onInsert()
    {
        $errors = [];
        $horario = self::where('modo', $this->modo)
            ->when($this->funcao_id, function ($query) {
                return $query->where('funcao_id', $this->funcao_id);
            })
            ->when($this->prestador_id, function ($query) {
                return $query->where('prestador_id', $this->prestador_id);
            })
            ->where('fechado', $this->fechado)
            ->get();
        if (!is_null($horario)) {
            foreach ($horario as $h) {
                if ($h->inicio >= $this->inicio && $h->inicio <= $this->fim) {
                    $errors['fim'] = __('messages.interval_existing');
                } elseif ($h->inicio <= $this->inicio && $h->fim >= $this->inicio) {
                    $errors['inicio'] = __('messages.horario_existing');
                }
            }
        }
        return $errors;
    }

    public function onUpdate()
    {
        $errors = [];
        $horario = self::where('modo', $this->modo)
            ->when($this->funcao_id, function ($query) {
                return $query->where('funcao_id', $this->funcao_id);
            })
            ->when($this->prestador_id, function ($query) {
                return $query->where('prestador_id', $this->prestador_id);
            })
            ->where('fechado', $this->fechado)
            ->get();

        if (!is_null($horario)) {
            foreach ($horario as $h) {
                if ($h->id != $this->id) {
                    if ($h->inicio >= $this->inicio && $h->inicio <= $this->fim) {
                        $errors['fim'] = __('messages.interval_existing');
                    } elseif ($h->inicio <= $this->inicio && $h->fim >= $this->inicio) {
                        $errors['inicio'] = __('messages.horario_existing');
                    }
                }
            }
        }
        return $errors;
    }
}
