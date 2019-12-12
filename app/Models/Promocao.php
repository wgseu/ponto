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
use Illuminate\Support\Facades\DB;
use App\Interfaces\ValidateInterface;
use Illuminate\Database\Eloquent\Model;
use App\Exceptions\ValidationException;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Validation\Rules\Exists;

/**
 * Informa se há descontos nos produtos em determinados dias da semana, o
 * preço pode subir ou descer e ser agendado para ser aplicado
 */
class Promocao extends Model implements ValidateInterface
{
    use ModelEvents;
    use SoftDeletes;

    /**
     * Local onde o preço será aplicado
     */
    public const LOCAL_LOCAL = 'local';
    public const LOCAL_MESA = 'mesa';
    public const LOCAL_COMANDA = 'comanda';
    public const LOCAL_BALCAO = 'balcao';
    public const LOCAL_ENTREGA = 'entrega';
    public const LOCAL_ONLINE = 'online';

    /**
     * Informa a regra para decidir se ainda pode vender com essa promoção
     */
    public const FUNCAO_VENDAS_MENOR = 'menor';
    public const FUNCAO_VENDAS_IGUAL = 'igual';
    public const FUNCAO_VENDAS_MAIOR = 'maior';

    /**
     * Informa a regra para decidir se o cliente consegue comprar mais nessa
     * promoção
     */
    public const FUNCAO_CLIENTE_MENOR = 'menor';
    public const FUNCAO_CLIENTE_IGUAL = 'igual';
    public const FUNCAO_CLIENTE_MAIOR = 'maior';

    public const DELETED_AT = 'data_arquivado';

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'promocoes';

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
        'promocao_id',
        'categoria_id',
        'produto_id',
        'servico_id',
        'bairro_id',
        'zona_id',
        'integracao_id',
        'local',
        'inicio',
        'fim',
        'valor',
        'pontos',
        'parcial',
        'proibir',
        'evento',
        'agendamento',
        'limitar_vendas',
        'funcao_vendas',
        'vendas_limite',
        'limitar_cliente',
        'funcao_cliente',
        'cliente_limite',
        'ativa',
        'chamada',
        'banner_url',
    ];

    /**
     * The model's default values for attributes.
     *
     * @var array
     */
    protected $attributes = [
        'pontos' => 0,
        'parcial' => false,
        'proibir' => false,
        'evento' => false,
        'agendamento' => false,
        'limitar_vendas' => false,
        'funcao_vendas' => self::FUNCAO_VENDAS_MAIOR,
        'vendas_limite' => 0,
        'limitar_cliente' => false,
        'funcao_cliente' => self::FUNCAO_CLIENTE_MAIOR,
        'cliente_limite' => 0,
        'ativa' => true,
    ];

    /**
     * Promoção que originou os pontos do cliente/pedido, se informado a
     * promoção será o resgate e somente pontos gerados por ela poderão ser
     * usados
     */
    public function promocao()
    {
        return $this->belongsTo(Promocao::class, 'promocao_id');
    }

    /**
     * Permite fazer promoção para qualquer produto dessa categoria
     */
    public function categoria()
    {
        return $this->belongsTo(Categoria::class, 'categoria_id');
    }

    /**
     * Informa qual o produto participará da promoção de desconto ou terá
     * acréscimo
     */
    public function produto()
    {
        return $this->belongsTo(Produto::class, 'produto_id');
    }

    /**
     * Informa se essa promoção será aplicada nesse serviço
     */
    public function servico()
    {
        return $this->belongsTo(Servico::class, 'servico_id');
    }

    /**
     * Bairro que essa promoção se aplica, somente serviços
     */
    public function bairro()
    {
        return $this->belongsTo(Bairro::class, 'bairro_id');
    }

    /**
     * Zona que essa promoção se aplica, somente serviços
     */
    public function zona()
    {
        return $this->belongsTo(Zona::class, 'zona_id');
    }

    /**
     * Permite alterar o preço do produto para cada integração
     */
    public function integracao()
    {
        return $this->belongsTo(Integracao::class, 'integracao_id');
    }

    public function validate()
    {
        $errors = [];
        $query = self::whereBetween('inicio', [$this->inicio, $this->fim])
            ->orWhereBetween(
                DB::raw(intval($this->inicio)),
                [
                    DB::raw($this->table . '.' . 'inicio'),
                    DB::raw($this->table . '.' . 'fim')
                ]
            );
        if ($this->exists) {
            $query->where('id', '<>', $this->id);
        }
        if ($this->categoria_id) {
            $query->where('categoria_id', $this->categoria_id);
        }
        if ($this->produto_id) {
            $query->where('produto_id', $this->produto_id);
        }
        if ($this->servico_id) {
            $query->where('servico_id', $this->servico_id);
        }
        if ($this->bairro_id) {
            $query->where('bairro_id', $this->bairro_id);
        }
        if ($this->zona_id) {
            $query->where('zona_id', $this->zona_id);
        }
        if ($this->integracao_id) {
            $query->where('integracao_id', $this->integracao_id);
        }
        if ($this->local) {
            $query->where('local', $this->local);
        }
        if ($this->evento) {
            $query->where('evento', $this->evento);
        }
        if ($this->agendamento) {
            $query->where('agendamento', $this->agendamento);
        }
        if ($query->exists()) {
            $errors['id'] = __('promocao_existing');
        }
        $selecao = !is_null($this->categoria_id) +
            !is_null($this->produto_id) +
            !is_null($this->servico_id);
        if (is_null($this->servico_id) && !is_null($this->bairro_id)) {
            $errors['servico_id'] = __('messages.servico_id_empty');
        } elseif ($selecao > 1) {
            $errors['servico_id'] = __('messages.multiple_selections');
        } elseif ($selecao < 1) {
            $errors['servico_id'] = __('messages.no_selection');
        }
        if (!is_null($this->zona_id) && is_null($this->bairro_id)) {
            $errors['bairro_id'] = __('messages.bairro_id_empty');
        }
        if ($this->inicio >= $this->fim) {
            $errors['inicio'] = __('messages.invalid_interval');
        } elseif (($this->evento == true || $this->agendamento == true) && $this->inicio < time()) {
            $errors['inicio'] = __('messages.promotion_begin_invalid');
        } elseif ($this->evento == false && $this->agendamento == false && $this->inicio < Date::MINUTES_PER_DAY) {
            $errors['inicio'] = __('messages.inicio_invalid');
        }
        if ($this->evento == false && $this->agendamento == false && $this->fim  >= Date::MINUTES_PER_DAY * 8) {
            $errors['fim'] = __('messages.promotion_end_invalid');
        }
        if (is_null($this->promocao_id) && $this->pontos < 0) {
            $errors['pontos'] = __('messages.points_not_negative');
        } elseif (!is_null($this->promocao_id) && $this->pontos > 0) {
            $errors['pontos'] = _('messages.points_must_be_negative');
        }
        if ($this->agendamento == true && $this->valor <= 0) {
            $errors['valor'] = __('messages.value_cannot_zero');
        }
        return $errors;
    }
}
