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
 * Formas de pagamento disponíveis para pedido e contas
 */
class Forma extends Model implements ValidateInterface
{
    use ModelEvents;

    /**
     * Tipo de pagamento
     */
    const TIPO_DINHEIRO = 'dinheiro';
    const TIPO_CREDITO = 'credito';
    const TIPO_DEBITO = 'debito';
    const TIPO_VALE = 'vale';
    const TIPO_CHEQUE = 'cheque';
    const TIPO_CREDIARIO = 'crediario';
    const TIPO_SALDO = 'saldo';

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'formas';

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
        'tipo',
        'carteira_id',
        'descricao',
        'min_parcelas',
        'max_parcelas',
        'parcelas_sem_juros',
        'juros',
        'ativa',
    ];

    /**
     * The model's default values for attributes.
     *
     * @var array
     */
    protected $attributes = [
        'min_parcelas' => 1,
        'max_parcelas' => 1,
        'parcelas_sem_juros' => 1,
        'juros' => 0,
        'ativa' => true,
    ];

    /**
     * Carteira que será usada para entrada de valores no caixa
     */
    public function carteira()
    {
        return $this->belongsTo('App\Models\Carteira', 'carteira_id');
    }

    public function validate()
    {
    }
}
