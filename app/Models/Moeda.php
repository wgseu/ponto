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
use App\Exceptions\SafeValidationException;
use App\Interfaces\ValidateInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\ValidationException;

/**
 * Moedas financeiras de um país
 */
class Moeda extends Model implements ValidateInterface
{
    use ModelEvents;

    public const UPDATED_AT = 'data_atualizacao';
    public const CREATED_AT = null;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'moedas';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'nome',
        'simbolo',
        'codigo',
        'divisao',
        'fracao',
        'formato',
        'conversao',
        'ativa',
    ];

    /**
     * The model's default values for attributes.
     *
     * @var array
     */
    protected $attributes = [
        'ativa' => false,
    ];

    /**
     * Regras:
     * Para o país ativo a conversão da moeda ver ser igual a 1;
     * Se a moeda estiver ativa a conversão não pode ser nula;
     * A conversão não pode ser negativa.
     * Formato deve conter :value, espaço e simbolo;
     * A divisao deve conter um valor valido
     */
    public function validate()
    {
        $errors = [];
        $formato = ' ' . $this->formato . ' ';
        $formato = strpos($formato, ' :value ');
        $simbolo = explode(' ', $this->formato);
        $divisao = [1, 10, 100, 1000, 10000];
        $empresa = Empresa::find(1);
        if (!is_null($empresa)) {
            $pais = $empresa->pais;
            if ($pais->moeda_id == $this->id && $this->conversao != 1) {
                $errors['conversao'] = __('messages.pais_active_conversion_different_1');
            }
        }
        if ($this->ativa && is_null($this->conversao)) {
            $errors['conversao'] = __('messages.moeda_active_null_conversion');
        }
        if ($this->conversao <= 0) {
            $errors['conversao'] = __('messages.moeda_conversion_cannot_negative_zero');
        }
        if (count($simbolo) < 2) {
            $errors['formato'] = __('messages.moeda_invalid_format');
        }
        if ($formato === false) {
            $errors['formato'] = __('messages.moeda_invalid_format');
        }
        if (!in_array($this->divisao, $divisao)) {
            $errors['divisao'] = __('messages.moeda_invalid_divisao');
        }
        if (!empty($errors)) {
            throw SafeValidationException::withMessages($errors);
        }
    }
}
