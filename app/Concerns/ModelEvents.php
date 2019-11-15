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

namespace App\Concerns;

use App\Interfaces\ValidateInterface;
use Illuminate\Database\Eloquent\Builder;
use App\Exceptions\ValidationException;
use App\Interfaces\ValidateInsertInterface;
use App\Interfaces\ValidateUpdateInterface;

/**
 * Informações sobre o produto, composição ou pacote
 */
trait ModelEvents
{
    /**
     * @inheritDoc
     *
     * @param Builder  $query
     * @return bool
     */
    protected function performInsert(Builder $query)
    {
        if ($this instanceof ValidateInterface) {
            $errors = $this->validate();
            $this->checkErrors($errors);
        }
        if ($this instanceof ValidateInsertInterface) {
            $errors = $this->onInsert();
            $this->checkErrors($errors);
        }
        return parent::performInsert($query);
    }

    /**
     * Check errors existence and throw them
     *
     * @param array $errors
     * @return void
     */
    protected function checkErrors($errors)
    {
        if (!empty($errors)) {
            throw ValidationException::withMessages($errors);
        }
    }

    /**
     * @inheritDoc
     *
     * @param Builder  $query
     * @return bool
     */
    protected function performUpdate(Builder $query)
    {
        if ($this instanceof ValidateInterface) {
            $errors = $this->validate();
            $this->checkErrors($errors);
        }
        if ($this instanceof ValidateUpdateInterface) {
            $errors = $this->onUpdate();
            $this->checkErrors($errors);
        }
        return parent::performUpdate($query);
    }
}
