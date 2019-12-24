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

use App\Core\Settings;
use App\Concerns\ModelEvents;
use App\Interfaces\ValidateInterface;
use App\Util\Filter;
use Illuminate\Database\Eloquent\Model;

/**
 * Classe que informa detalhes da empresa, parceiro e opções do sistema
 * como a versão do banco de dados e a licença de uso
 */
class Sistema extends Model implements ValidateInterface
{
    use ModelEvents;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'sistemas';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * Opções de impressão e comportamento do sistema
     *
     * @var Settings
     */
    public $options;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'fuso_horario',
        'opcoes',
    ];

    /**
     * @inheritDoc
     */
    public function __construct(array $attributes = [])
    {
        $this->options = new Settings([
            'auto_sair' => false,
            'padrao_imprimir' => true,
            'motivo_cancelamento' => false,
            'fiscal' => [
                'mostrar_campos' => false,
            ],
            'cupom_pedido' => [
                'empresa_cnpj' => true,
                'empresa_endereco' => true,
                'empresa_slogan' => true,
                'empresa_logo' => false,
                'empresa_telefone' => true,
                'empresa_celular' => true,
                'garcom' => true,
                'todos_garcons' => false,
                'atendente' => true,
                'permanencia' => true,
                'divisao_conta' => true,
                'servicos_detalhados' => false,
                'pessoas' => true,
                'pacote_agrupado' => true,
                'descricao' => true,
            ],
            'cupom_entrega' => [
                'endereco_destacado' => true,
            ],
            'cupom_preparo' => [
                'local_destacado' => true,
                'codigo_produto' => false,
                'detalhes_produto' => false,
                'letra_gigante_produto' => false,
                'cliente' => false,
                'descricao_pedido' => false,
                'saldo_comanda' => false,
                'separar_item' => false,
            ],
            'cupom_fechamento' => [
                'produtos' => false,
                'cancelamentos' => true,
            ],
            'cupom' => [
                'via2_pedido' => false,
                'cancelamento_pedido' => true,
                'cancelamento_preparo' => true,
                'senha_balcao' => false,
                'senha_comanda' => false,
                'entrega_antecipada' => false,
                'comprovante_conta' => true,
                'operacao_caixa' => true,
                'pagamento_pedido' => true,
                'fechamento_caixa' => true,
            ],
            'mobile' => [
                'auto_sair' => false,
            ],
            'venda' => [
                'lembrar_garcom' => false,
                'enviar_sair' => false,
                'quantidade_perguntar' => true,
                'peso_automatico' => true,
            ],
            'balcao' => [
                'comissao' => false,
            ],
            'comanda' => [
                'observacao_nome' => true,
                'fila_pesagem' => false,
            ],
            'estoque' => [
                'controlar' => true,
            ],
            'comanda' => [
                'pre_paga' => false
            ],
        ]);
        parent::__construct($attributes);
    }

    /**
     * Retorna a empresa do sistema
     *
     * @return Empresa
     */
    public function getEmpresaAttribute()
    {
        return app('business');
    }

    /**
     * Retorna as opções do sistema
     *
     * @return string
     */
    public function getOpcoesAttribute()
    {
        $this->options->includeDefaults = app('settings')->includeDefaults;
        $this->loadOptions();
        return json_encode(Filter::emptyObject($this->options->getValues()));
    }

    public function setOpcoesAttribute($value)
    {
        $this->options->addValues(json_decode($value ?? '{}', true));
        $this->attributes['opcoes'] = base64_encode(json_encode($this->options->getValues(false)));
    }

    /**
     * Carrega as opções do sistema
     *
     * @return void
     */
    public function loadOptions()
    {
        $this->options->addValues(
            json_decode(base64_decode($this->getAttributeFromArray('opcoes')), true)
        );
    }

    public function validate($old)
    {
        $errors = [];
        $timezone_identifiers = timezone_identifiers_list();
        if (!is_null($this->fuso_horario) && !in_array($this->fuso_horario, $timezone_identifiers)) {
            $errors['fuso_horario'] = __('messages.invalid_timezone');
        }
        return $errors;
    }
}
