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

use App\Util\Date;
use App\Concerns\ModelEvents;
use App\Interfaces\ValidateInterface;
use Illuminate\Database\Eloquent\Model;

/**
 * Informa o horário de funcionamento do estabelecimento
 */
class Horario extends Model implements ValidateInterface
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
        'cozinha_id',
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
     * Ramo ou tipo de cozinha do restaurante que funcionará nesse horário
     */
    public function cozinha()
    {
        return $this->belongsTo('App\Models\Cozinha', 'cozinha_id');
    }

    /**
     * Retorna o horário compeendido ou próximo horário disponível com base no tempo unix
     * @param int $time tempo atual
     * @return self Self instance filled or empty
     */
    public static function loadByAvailable($time = null)
    {
        $week_offset = Date::weekOffset($time);
        // carrega até o final da semana (Sábado)
        $horario = self::where('fim', '>=', $week_offset)
            ->where('modo', Horario::MODO_FUNCIONAMENTO)
            ->where('fechado', false)
            ->orderBy('inicio', 'asc')->first();
        if (is_null($horario)) {
            // carrega na outra semana (domingo)
            $horario = self::where('fechado', false)
                ->where('modo', Horario::MODO_FUNCIONAMENTO)
                ->orderBy('inicio', 'asc')->first();
        }
        return $horario;
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
        $horario = self::where('modo', $this->modo)
            ->when($this->funcao_id, function ($query) {
                return $query->where('funcao_id', $this->funcao_id);
            })
            ->when($this->prestador_id, function ($query) {
                return $query->where('prestador_id', $this->prestador_id);
            })
            ->when($this->exists, function ($query) {
                return $query->where('id', '<>', $this->id);
            })
            ->where(function ($query) {
                $query->where(function ($query) {
                    $query->where('inicio', '>=', $this->inicio)
                    ->where('inicio', '<=', $this->fim);
                })
                ->orWhere(function ($query) {
                    $query->where('inicio', '<=', $this->inicio)
                    ->where('fim', '>=', $this->inicio);
                });
            })
            ->where('fechado', $this->fechado)
            ->count('id');
        if (!is_null($this->funcao_id) && !is_null($this->prestador_id)) {
            $errors['funcao_id'] = __('messages.invalid_selections_funcao_prestador');
        }
        if ($this->inicio >= $this->fim) {
            $errors['inicio'] = __('messages.invalid_interval_funcionamento');
        } elseif (!$this->fechado && $this->inicio < Date::MINUTES_PER_DAY) {
            $errors['inicio'] = __('messages.inicio_invalid');
        } elseif (!$this->fechado && $this->fim >= Date::MINUTES_PER_DAY * 8) {
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
            && (!is_null($this->funcao_id)
            || !is_null($this->prestador_id)
            || $this->modo != self::MODO_FUNCIONAMENTO)
        ) {
            $errors['entrega_maxima'] = __('messages.close_invalid');
        }
        if ($horario > 0) {
            $errors['inicio'] = __('messages.horario_existing');
        }
        return $errors;
    }
}
