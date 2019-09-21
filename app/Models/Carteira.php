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
 * Informa uma conta bancária ou uma carteira financeira
 */
class Carteira extends Model
{
    /**
     * Tipo de carteira, 'Bancaria' para conta bancária, 'Financeira' para
     * carteira financeira da empresa ou de sites de pagamentos, 'Credito' para
     * cartão de crédito e 'Local' para caixas e cofres locais
     */
    const TIPO_BANCARIA = 'bancaria';
    const TIPO_FINANCEIRA = 'financeira';
    const TIPO_CREDITO = 'credito';
    const TIPO_LOCAL = 'local';

    /**
     * Ambiente de execução da API usando o token
     */
    const AMBIENTE_TESTE = 'teste';
    const AMBIENTE_PRODUCAO = 'producao';

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'carteiras';

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
        'tipo',
        'carteira_id',
        'banco_id',
        'descricao',
        'conta',
        'agencia',
        'transacao',
        'limite',
        'token',
        'ambiente',
        'logo_url',
        'cor',
        'ativa',
        'data_desativada',
    ];

    /**
     * The model's default values for attributes.
     *
     * @var array
     */
    protected $attributes = [
        'transacao' => 0,
        'ativa' => true,
    ];

    /**
     * Informa a carteira superior, exemplo: Banco e cartões como subcarteira
     */
    public function carteira()
    {
        return $this->belongsTo('App\Models\Carteira', 'carteira_id');
    }

    /**
     * Código local do banco quando a carteira for bancária
     */
    public function banco()
    {
        return $this->belongsTo('App\Models\Banco', 'banco_id');
    }
}
