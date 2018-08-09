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
namespace MZ\Util;

/**
 * Document helper
 */
class Document
{
    /**
     * Find and return child node value from tag name
     * @param  DOMElement $parent parent element of child
     * @param  string $tag tag name to find child
     * @param  bool   $required throws exception when child does not exists
     * @param  mixed  $default default values when child does not exists
     * @return string child node value or null when required is false
     */
    public static function childValue($parent, $tag, $required = true, $default = null)
    {
        $child = self::findChild($parent, $tag, $required);
        if (is_null($child)) {
            return $default;
        }
        return $child->nodeValue;
    }
    /**
     * Find and return child node from tag name
     * @param  DOMElement $parent parent element of child
     * @param  string $tag tag name to find child
     * @param  bool   $required throws exception when child does not exists
     * @return string child node or null when required is false
     */
    public static function findChild($parent, $tag, $required = true)
    {
        $childs = $parent->getElementsByTagName($tag);
        if ($childs->length == 0 && $required) {
            throw new \Exception(
                sprintf('Child node "%s" not found in parent "%s"', $tag, $parent->nodeName),
                404
            );
        } elseif ($childs->length == 0) {
            return null;
        }
        return $childs->item(0);
    }
}
