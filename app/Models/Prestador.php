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
 * Prestador de serviço que realiza alguma tarefa na empresa
 */
class Prestador extends Model implements ValidateInterface
{
    use ModelEvents;

    /**
     * Vínculo empregatício com a empresa, funcionário e autônomo são pessoas
     * físicas, prestador é pessoa jurídica
     */
    const VINCULO_FUNCIONARIO = 'funcionario';
    const VINCULO_PRESTADOR = 'prestador';
    const VINCULO_AUTONOMO = 'autonomo';

    const CREATED_AT = 'data_cadastro';
    const UPDATED_AT = null;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'prestadores';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'codigo',
        'pin',
        'funcao_id',
        'cliente_id',
        'empresa_id',
        'vinculo',
        'porcentagem',
        'pontuacao',
        'remuneracao',
        'data_termino',
    ];

    /**
     * The model's default values for attributes.
     *
     * @var array
     */
    protected $attributes = [
        'vinculo' => self::VINCULO_FUNCIONARIO,
        'porcentagem' => 0,
        'pontuacao' => 0,
        'remuneracao' => 0,
    ];

    /**
     * Função do prestada na empresa
     */
    public function funcao()
    {
        return $this->belongsTo('App\Models\Funcao', 'funcao_id');
    }

    /**
     * Cliente que representa esse prestador, único no cadastro de prestadores
     */
    public function cliente()
    {
        return $this->belongsTo('App\Models\Cliente', 'cliente_id');
    }

    /**
     * Informa a empresa que gerencia os colaboradores, nulo para a empresa do
     * próprio estabelecimento
     */
    public function empresa()
    {
        return $this->belongsTo('App\Models\Prestador', 'empresa_id');
    }

    public function validate()
    {
    }
}
