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
 * Informações de cliente físico ou jurídico. Clientes, empresas,
 * funcionários, fornecedores e parceiros são cadastrados aqui
 */
class Cliente extends Model
{
    /**
     * Informa o tipo de pessoa, que pode ser física ou jurídica
     */
    const TIPO_FISICA = 'fisica';
    const TIPO_JURIDICA = 'juridica';

    /**
     * Informa o gênero do cliente do tipo pessoa física
     */
    const GENERO_MASCULINO = 'masculino';
    const GENERO_FEMININO = 'feminino';

    /**
     * Informa o estado da conta do cliente
     */
    const STATUS_INATIVO = 'inativo';
    const STATUS_ATIVO = 'ativo';
    const STATUS_BLOQUEADO = 'bloqueado';

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'clientes';

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
        'tipo' => self::TIPO_FISICA,
        'status' => self::STATUS_INATIVO,
    ];

    /**
     * Informa se esse cliente faz parte da empresa informada
     */
    public function empresa()
    {
        return $this->belongsTo('App\Models\Cliente', 'empresa_id');
    }
}
