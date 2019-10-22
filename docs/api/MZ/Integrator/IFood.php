<?php

/**
 * Copyright 2014 da MZ Software - MZ Desenvolvimento de Sistemas LTDA
 *
 * Este arquivo é parte do programa GrandChef - Sistema para Gerenciamento de Churrascarias, Bares e Restaurantes.
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
 * @author Equipe GrandChef <desenvolvimento@mzsw.com.br>
 */

namespace MZ\Integrator;

/**
 * Kromax Service and Task
 */
class IFood extends \MZ\System\Task
{
    const NAME = 'ifood';
    const CARDS = [
        'RAM' => ['name' => 'AMERICAN EXPRESS (Crédito)'],
        'DNREST' => ['name' => 'DINERS (Crédito)'],
        'REC' => ['name' => 'ELO (Crédito)'],
        'RHIP' => ['name' => 'HIPERCARD (Crédito)'],
        'RDREST' => ['name' => 'MASTERCARD (Crédito)'],
        'VSREST' => ['name' => 'VISA (Crédito)'],
        'RED' => ['name' => 'ELO (Débito)'],
        'MEREST' => ['name' => 'MASTERCARD (Débito)'],
        'VIREST' => ['name' => 'VISA (Débito)'],
        'BANRD' => ['name' => 'BANRICOMPRAS (Débito)'],
        'VVREST' => ['name' => 'ALELO REFEICAO (Vale)'],
        'RSODEX' => ['name' => 'SODEXO (Vale)'],
        'TRE' => ['name' => 'TICKET RESTAURANTE (Vale)'],
        'VALECA' => ['name' => 'VALE CARD (Vale)'],
        'VR_SMA' => ['name' => 'VR SMART (Vale)'],
        'AM' => ['name' => 'AMEX (Online)'],
        'DNR' => ['name' => 'DINERS (Online)'],
        'ELO' => ['name' => 'ELO (Online)'],
        'MC' => ['name' => 'MASTERCARD (Online)'],
        'VIS' => ['name' => 'VISA (Online)'],
        'CRE' => ['name' => 'Crédito iFood']
    ];

    /**
     * Name of the task
     * @return string task name
     */
    public function getName()
    {
        $integracao = $this->getData();
        return $integracao->getNome();
    }

    /**
     * Execute task
     * @return integer Number of pending work
     */
    public function run()
    {
        return $this->getPending();
    }
}
