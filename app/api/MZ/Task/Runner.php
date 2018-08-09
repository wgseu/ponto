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
namespace MZ\Task;

use MZ\System\Integracao;
use MZ\Exception\RedirectException;

/**
 * Task runner
 */
class Runner
{

    /**
     * Number of processed tasks
     */
    private $processed;
    /**
     * Number of pending work to execute
     */
    private $pending;
    /**
     * Number of failed tasks
     */
    private $failed;
    /**
     * List of errors
     */
    private $errors;
    /**
     * List of task to run
     */
    private $tasks;

    /**
     * Constructor for a new empty instance of Runner
     */
    public function __construct()
    {
        $this->clear();
    }

    /**
     * Number of processed tasks
     * @return mixed return number of processed tasks
     */
    public function getProcessed()
    {
        return $this->processed;
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
     * Number of failed task execution
     * @return mixed return number of failed task execution
     */
    public function getFailed()
    {
        return $this->failed;
    }

    /**
     * List of exception and task errored
     * @return array task exception array list
     */
    public function getErrors()
    {
        return $this->errors;
    }

    /**
     * Add a new task to execute
     * @param  MZ\System\Task $task task to execute
     * @return Runner Self instance
     */
    public function addTask($task)
    {
        $this->tasks[] = $task;
        return $this;
    }

    /**
     * Execute tasks
     * @return integer Number of processed tasks
     */
    public function execute()
    {
        $this->build();
        foreach ($this->tasks as $task) {
            try {
                $this->pending += $task->run();
                $this->processed += 1;
            } catch (\Exception $e) {
                $this->failed += 1;
                $error = [
                    'message' => $e->getMessage(),
                    'code' => $e->getCode(),
                    'task' => $task->getName()
                ];
                if ($e instanceof RedirectException) {
                    $error['redirect'] = $e->getURL();
                }
                $this->errors[] = $error;
                \Log::error('Task[' . $task->getName() . '] - ' . $e->getMessage());
            }
        }
        return $this->getProcessed();
    }

    /**
     * Build tasks
     */
    private function build()
    {
        $integracoes = Integracao::findAll(['ativo' => 'Y']);
        foreach ($integracoes as $integracao) {
            $this->addTask($integracao->getTask());
        }
        return $this;
    }

    /**
     * Clear tasks and status
     */
    private function clear()
    {
        $this->processed = 0;
        $this->pending = 0;
        $this->failed = 0;
        $this->tasks = [];
        $this->errors = [];
        return $this;
    }
}
