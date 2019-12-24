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

use App\Util\Number;
use App\Concerns\ModelEvents;
use App\Interfaces\ValidateInterface;
use Illuminate\Database\Eloquent\Model;
use App\Exceptions\ValidationException;

/**
 * Taxas, eventos e serviço cobrado nos pedidos
 */
class Servico extends Model implements ValidateInterface
{
    use ModelEvents;

    /**
     * Tipo de serviço, Evento: Eventos como show no estabelecimento
     */
    public const TIPO_EVENTO = 'evento';
    public const TIPO_TAXA = 'taxa';
    public const ENTREGA_ID = 1;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'servicos';

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
        'nome',
        'descricao',
        'detalhes',
        'tipo',
        'obrigatorio',
        'data_inicio',
        'data_fim',
        'tempo_limite',
        'valor',
        'individual',
        'imagem_url',
        'ativo',
    ];

    /**
     * The model's default values for attributes.
     *
     * @var array
     */
    protected $attributes = [
        'obrigatorio' => true,
        'valor' => 0,
        'individual' => false,
        'ativo' => true,
    ];

    public function validate($old)
    {
        $errors = [];
        if ($this->valor < 0) {
            $errors['valor'] = __('messages.value_negative');
        } elseif (Number::isEqual($this->valor, 0)) {
            $errors['valor'] = __('messages.valor_cannot_zero');
        }
        if ($this->tipo == self::TIPO_EVENTO) {
            if (is_null($this->data_inicio)) {
                $errors['data_inicio'] = __('messages.date_start_cannot_null');
            }
            if (is_null($this->data_fim)) {
                $errors['data_fim'] = __('messages.date_end_cannot_null');
            }
        } else {
            if (!is_null($this->data_inicio)) {
                $errors['data_inicio'] = __('messages.data_inicio_must_empty');
            }
            if (!is_null($this->data_fim)) {
                $errors['data_fim'] = __('messages.data_fim_must_empty');
            }
        }
        return $errors;
    }
}
