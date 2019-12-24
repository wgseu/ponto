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
use App\Interfaces\AfterInsertInterface;
use App\Interfaces\AfterSaveInterface;
use App\Interfaces\AfterUpdateInterface;
use App\Interfaces\BeforeInsertInterface;
use App\Interfaces\BeforeSaveInterface;
use App\Interfaces\BeforeUpdateInterface;
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
            $errors = $this->validate(null);
            $this->checkErrors($errors);
        }
        if ($this instanceof ValidateInsertInterface) {
            $errors = $this->onInsert();
            $this->checkErrors($errors);
        }
        if ($this instanceof BeforeSaveInterface) {
            $this->beforeSave(null);
        }
        if ($this instanceof BeforeInsertInterface) {
            $this->beforeInsert();
        }
        $result = parent::performInsert($query);
        if ($this instanceof AfterInsertInterface) {
            $this->afterInsert();
        }
        if ($this instanceof AfterSaveInterface) {
            $this->afterSave(null);
        }
        return $result;
    }

    /**
     * @inheritDoc
     *
     * @param Builder  $query
     * @return bool
     */
    protected function performUpdate(Builder $query)
    {
        $previous = (clone $this)->setRawAttributes($this->getOriginal());
        $this->syncChanges();
        if ($this instanceof ValidateInterface) {
            $errors = $this->validate($previous);
            $this->checkErrors($errors);
        }
        if ($this instanceof ValidateUpdateInterface) {
            $errors = $this->onUpdate($previous);
            $this->checkErrors($errors);
        }
        if ($this instanceof BeforeSaveInterface) {
            $this->beforeSave($previous);
        }
        if ($this instanceof BeforeUpdateInterface) {
            $this->beforeUpdate($previous);
        }
        $result = parent::performUpdate($query);
        if ($this instanceof AfterUpdateInterface) {
            $this->afterUpdate($previous);
        }
        if ($this instanceof AfterSaveInterface) {
            $this->afterSave($previous);
        }
        return $result;
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
     * Check if only allowed fields has changed
     *
     * @param array $fields
     * @return boolean
     */
    public function isChangeAllowed($fields)
    {
        return empty(array_diff_key($this->getChanges(), array_flip($fields)));
    }
}
