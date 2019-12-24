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
 * Cartões utilizados na forma de pagamento em cartão
 */
class Cartao extends Model implements ValidateInterface
{
    use ModelEvents;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'cartoes';

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
        'forma_id',
        'carteira_id',
        'bandeira',
        'taxa',
        'dias_repasse',
        'taxa_antecipacao',
        'imagem_url',
        'ativo',
    ];

    /**
     * The model's default values for attributes.
     *
     * @var array
     */
    protected $attributes = [
        'taxa' => 0,
        'dias_repasse' => 30,
        'taxa_antecipacao' => 0,
        'ativo' => true,
    ];

    /**
     * Forma de pagamento associada à esse cartão ou vale
     */
    public function forma()
    {
        return $this->belongsTo(Forma::class, 'forma_id');
    }

    /**
     * Carteira de entrada de valores no caixa
     */
    public function carteira()
    {
        return $this->belongsTo(Carteira::class, 'carteira_id');
    }

    /**
     * Regras:
     * Taxa, dias_repasse, e taxa_antecipacao não podem ser negativas
     * Um cartão não pode ser criado desativado.
     */
    public function validate($old)
    {
        $errors = [];
        if ($this->taxa < 0) {
            $errors['taxa'] = __('messages.taxa_cannot_negative');
        } elseif ($this->dias_repasse < 0) {
            $errors['dias_repasse'] = __('messages.dias_repasse_negative');
        } elseif ($this->taxa_antecipacao < 0) {
            $errors['taxa_antecipacao'] = __('messages.taxa_antecipacao_negative');
        }
        if (!$this->exists && !$this->ativo) {
            $errors['ativo'] = __('messages.create_cartao_desativado');
        }
        return $errors;
    }
}
