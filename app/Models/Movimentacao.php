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
use Illuminate\Support\Facades\DB;
use App\Interfaces\ValidateInterface;
use Illuminate\Database\Eloquent\Model;
use App\Exceptions\ValidationException;

/**
 * Movimentação do caixa, permite abrir diversos caixas na conta de
 * operadores
 */
class Movimentacao extends Model implements ValidateInterface
{
    use ModelEvents;

    public const CREATED_AT = 'data_abertura';
    public const UPDATED_AT = null;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'movimentacoes';


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'sessao_id',
        'caixa_id',
        'aberta',
        'iniciador_id',
        'fechador_id',
        'data_fechamento',
    ];

    /**
     * The model's default values for attributes.
     *
     * @var array
     */
    protected $attributes = [
        'aberta' => true,
    ];

    /**
     * Sessão do dia, permite abrir vários caixas no mesmo dia com o mesmo
     * código da sessão
     */
    public function sessao()
    {
        return $this->belongsTo('App\Models\Sessao', 'sessao_id');
    }

    /**
     * Caixa a qual pertence essa movimentação
     */
    public function caixa()
    {
        return $this->belongsTo('App\Models\Caixa', 'caixa_id');
    }

    /**
     * Funcionário que abriu o caixa
     */
    public function iniciador()
    {
        return $this->belongsTo('App\Models\Prestador', 'iniciador_id')->withTrashed();
    }

    /**
     * Funcionário que fechou o caixa
     */
    public function fechador()
    {
        return $this->belongsTo('App\Models\Prestador', 'fechador_id');
    }

    /**
     * Cria sessão caso não tenha e salva movimentação
     */
    public function createSessaoOrSave()
    {
        DB::transaction(function () {
            $horario = Horario::loadByAvailable();
            if (is_null($horario) || is_null($horario->cozinha_id)) {
                throw new ValidationException(['cozinha' => __('messages.session_not_kitchen_or_not_exists')]);
            }
            if (is_null($this->sessao_id)) {
                $sessao = new Sessao();
                $sessao->fill([
                    'cozinha_id' => $horario->cozinha_id,
                    'data_inicio' => Carbon::now(),
                ]);
                $sessao->save();
                $this->sessao_id = $sessao->id;
            }
            $this->save();
        });
    }

    /**
     * Salva movimentação e fecha sessões atreladas
     */
    public function closeOrSave($prestador)
    {
        DB::transaction(function () use ($prestador) {
            if ($this->aberta == false) {
                $this->data_fechamento = Carbon::now();
                $this->fechador_id = $prestador->id;
                $movimentacao = self::where([
                    ['aberta' => true],
                    ['caixa_id' => $this->caixa_id]
                ])->count();
                if ($movimentacao < 2) {
                    $sessoes = $this->sessao()->get();
                    $sessoes->close();
                }
            }
            $this->save();
        });
    }

    public function validate()
    {
        $errors = [];
        $old = $this->fresh();

        $sessao = $this->sessao;
        $global_sessao = Sessao::where('aberta', true)->first();
        if (!is_null($sessao) && !$sessao->aberta && $global_sessao->aberta) {
            $errors['sessao_id'] = __('messages.sessao_closed');
        } elseif (!is_null($old) && $old->sessao_id != $this->sessao_id) {
            $errors['sessao_id'] = __('messages.sessao_changed');
        }
        $caixa = $this->caixa;
        if (!is_null($caixa) && $caixa->ativa != true) {
            $errors['caixa_id'] = __('messages.caixa_inactive', ['descricao' => $caixa->descricao]);
        }
        $count = self::where([
            ['aberta' => true],
            ['sessao_id' => $this->sessao_id]
        ])->count();
        $pedidos = Pedido::where([
            ['sessao_id', $this->sessao_id],
            ['estado', '<>', Pedido::ESTADO_CANCELADO],
            ['estado', '<>', Pedido::ESTADO_CONCLUIDO],
        ])->count();
        $pagamentos = Pagamento::where([
            ['movimentacao_id', $this->id],
            ['estado', '<>', Pagamento::ESTADO_CANCELADO],
            ['estado', '<>', Pagamento::ESTADO_PAGO],
        ])->count();
        if (!$this->exists && $this->aberta != true) {
            $errors['aberta'] = __('messages.aberta_create_closed');
        } elseif ($this->aberta != true && $count < 2 && $pedidos > 0) {
            $errors['aberta'] = __('messages.aberta_orders', ['pedidos' => $pedidos]);
        } elseif ($this->aberta != true && $pagamentos > 0) {
            $errors['aberta'] = __('messages.aberta_payments', ['pagamentos' => $pagamentos]);
        }
        $movimentacao = self::where([
            ['sessao_id', $this->sessao_id],
            ['caixa_id', $this->caixa_id],
            ['iniciador_id', $this->iniciador_id],
            ['aberta', true]
        ])->first();
        $iniciador = $this->iniciador;
        if (!is_null($movimentacao) && !$this->exists) {
            $errors['iniciador_id'] = __('messages.iniciador_initiated');
        } elseif (!is_null($old) && $old->iniciador_id != $this->iniciador_id) {
            $errors['iniciador_id'] = __('messages.iniciador_changed');
        }
        if (!is_null($this->fechador_id) && $this->aberta) {
            $errors['fechador_id'] = __('messages.fechador_mustbe_empty');
        } elseif (is_null($this->fechador_id) && $this->aberta != true) {
            $errors['fechador_id'] = __('messages.fechador_cannot_empty');
        }
        if (!is_null($old) && $old->data_abertura != $this->data_abertura) {
            $errors['data_abertura'] = __('messages.data_abertura_changed');
        }
        if (!is_null($this->data_fechamento) && $this->aberta == true) {
            $errors['data_fechamento'] = __('messages.data_fechamento_mustbe_empty');
        } elseif (is_null($this->data_fechamento) && $this->aberta != true) {
            $errors['data_fechamento'] = __('messages.data_fechamento_cannot_empty');
        }
        return $errors;
    }
}
