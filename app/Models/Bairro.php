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
 * Bairro de uma cidade
 */
class Bairro extends Model implements ValidateInterface
{
    use ModelEvents;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'bairros';

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
        'cidade_id',
        'nome',
        'valor_entrega',
        'disponivel',
        'mapeado',
        'entrega_minima',
        'entrega_maxima',
    ];

    /**
     * The model's default values for attributes.
     *
     * @var array
     */
    protected $attributes = [
        'disponivel' => true,
        'mapeado' => false,
    ];

    /**
     * Cidade a qual o bairro pertence
     */
    public function cidade()
    {
        return $this->belongsTo('App\Models\Cidade', 'cidade_id');
    }

    public function validate()
    {
        $errors = [];
        if (!is_null($this->entrega_minima) && !is_null($this->entrega_maxima) ) {
            if ($this->entrega_minima > $this->entrega_maxima){
                $errors['entrega_minima'] = __('messagens.error_time_delivery');
            }
        }
        if ($this->valor_entrega < 0){
            $errors['value_delivery_negative'] = __('messagens.error_time_delivery');
        }
        if (!empty($errors)) {
            throw ValidationException::withMessages($errors);
        }
    }
}
