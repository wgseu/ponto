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
 * Informa se há descontos nos produtos em determinados dias da semana, o
 * preço pode subir ou descer e ser agendado para ser aplicado
 */
class Promocao extends Model
{
    /**
     * Local onde o preço será aplicado
     */
    const LOCAL_LOCAL = 'local';
    const LOCAL_MESA = 'mesa';
    const LOCAL_COMANDA = 'comanda';
    const LOCAL_BALCAO = 'balcao';
    const LOCAL_ENTREGA = 'entrega';
    const LOCAL_ONLINE = 'online';

    /**
     * Informa a regra para decidir se ainda pode vender com essa promoção
     */
    const FUNCAO_VENDAS_MENOR = 'menor';
    const FUNCAO_VENDAS_IGUAL = 'igual';
    const FUNCAO_VENDAS_MAIOR = 'maior';

    /**
     * Informa a regra para decidir se o cliente consegue comprar mais nessa
     * promoção
     */
    const FUNCAO_CLIENTE_MENOR = 'menor';
    const FUNCAO_CLIENTE_IGUAL = 'igual';
    const FUNCAO_CLIENTE_MAIOR = 'maior';

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
     * The model's default values for attributes.
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
        'data_arquivado',
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
        return $this->belongsTo('App\Models\Promocao', 'promocao_id');
    }

    /**
     * Permite fazer promoção para qualquer produto dessa categoria
     */
    public function categoria()
    {
        return $this->belongsTo('App\Models\Categoria', 'categoria_id');
    }

    /**
     * Informa qual o produto participará da promoção de desconto ou terá
     * acréscimo
     */
    public function produto()
    {
        return $this->belongsTo('App\Models\Produto', 'produto_id');
    }

    /**
     * Informa se essa promoção será aplicada nesse serviço
     */
    public function servico()
    {
        return $this->belongsTo('App\Models\Servico', 'servico_id');
    }

    /**
     * Bairro que essa promoção se aplica, somente serviços
     */
    public function bairro()
    {
        return $this->belongsTo('App\Models\Bairro', 'bairro_id');
    }

    /**
     * Zona que essa promoção se aplica, somente serviços
     */
    public function zona()
    {
        return $this->belongsTo('App\Models\Zona', 'zona_id');
    }

    /**
     * Permite alterar o preço do produto para cada integração
     */
    public function integracao()
    {
        return $this->belongsTo('App\Models\Integracao', 'integracao_id');
    }
}
