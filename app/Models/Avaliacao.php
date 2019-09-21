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
 * Avaliação de atendimento e outros serviços do estabelecimento
 */
class Avaliacao extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'avaliacoes';

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
    protected $fillable = [
        'metrica_id',
        'cliente_id',
        'pedido_id',
        'produto_id',
        'estrelas',
        'comentario',
        'data_avaliacao',
    ];

    /**
     * Métrica de avaliação
     */
    public function metrica()
    {
        return $this->belongsTo('App\Models\Metrica', 'metrica_id');
    }

    /**
     * Informa o cliente que avaliou esse pedido ou produto, obrigatório quando
     * for avaliação de produto
     */
    public function cliente()
    {
        return $this->belongsTo('App\Models\Cliente', 'cliente_id');
    }

    /**
     * Pedido que foi avaliado, quando nulo o produto deve ser informado
     */
    public function pedido()
    {
        return $this->belongsTo('App\Models\Pedido', 'pedido_id');
    }

    /**
     * Produto que foi avaliado
     */
    public function produto()
    {
        return $this->belongsTo('App\Models\Produto', 'produto_id');
    }
}
