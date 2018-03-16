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
 * @author  Francimar Alves <mazinsw@gmail.com>
 */
namespace MZ\System;

/**
 * Base Task
 */
abstract class Task
{
    /**
     * Number of pending work to execute
     */
    private $pending;
    /**
     * Custom data
     */
    private $data;

    /**
     * Constructor for a new empty instance of Task
     */
    public function __construct()
    {
        $this->pending = 0;
    }

    /**
     * Number of pending work to execute
     * @return mixed return number of pending work to execute
     */
    public function getPending()
    {
        return $this->pending;
    }

    /**
     * Number of pending work to execute
     * @param  integer $pending pending work count
     * @return Task self instance
     */
    protected function setPending($pending)
    {
        $this->pending = $pending;
        return $this;
    }

    /**
     * Custom data
     * @return mixed return custom data
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * Custom data
     * @param  mixed $data custom data
     * @return Task self instance
     */
    public function setData($data)
    {
        $this->data = $data;
        return $this;
    }

    /**
     * Execute task
     * @return integer Number of pending work
     */
    abstract public function run();
}
