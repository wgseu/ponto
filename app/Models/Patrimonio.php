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

use Illuminate\Database\Eloquent\Model;

/**
 * Informa detalhadamente um bem da empresa
 */
class Patrimonio extends Model
{
    /**
     * Estado de conservação do bem
     */
    const ESTADO_NOVO = 'novo';
    const ESTADO_CONSERVADO = 'conservado';
    const ESTADO_RUIM = 'ruim';

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'patrimonios';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The model's default values for attributes.
     *
     * @var array
     */
    protected $attributes = [
        'altura' => 0,
        'largura' => 0,
        'comprimento' => 0,
        'estado' => self::ESTADO_NOVO,
        'custo' => 0,
        'valor' => 0,
        'ativo' => true,
    ];

    /**
     * Empresa a que esse bem pertence
     */
    public function empresa()
    {
        return $this->belongsTo('App\Models\Cliente', 'empresa_id');
    }

    /**
     * Fornecedor do bem
     */
    public function fornecedor()
    {
        return $this->belongsTo('App\Models\Fornecedor', 'fornecedor_id');
    }
}
