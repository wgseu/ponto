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
namespace MZ\Template;

use MZ\Util\Filter;

/**
 * Proccess template files
 */
class Custom extends Engine
{
    /**
     * Render a template file and returns it's content
     * @param  string $tFile template name
     * @return string template content processed
     */
    public function render($tFile)
    {
        ob_start();
        // TODO: remove retrocompatibility
        // Begin retrocompatibility
        foreach ($this->getProperties() as $k => $v) {
            ${$k} = $v;
        }
        // End retrocompatibility
        include $this->__template($tFile);
        return ob_get_clean();
    }

    /**
     * Get values from _GET variable
     * @param  string $key key to retrieve value
     * @param  string $default default value when key not found
     * @return mixed string or default value
     */
    public function get($key, $default = null)
    {
        if (!array_key_exists($key, $_GET)) {
            return $default;
        }
        return $_GET[$key];
    }

    /**
     * Get values from _POST variable
     * @param  string $key key to retrieve value
     * @param  string $default default value when key not found
     * @return mixed string or default value
     */
    public function post($key, $default = null)
    {
        if (!array_key_exists($key, $_POST)) {
            return $default;
        }
        return $_POST[$key];
    }

    /**
     * Escape value for secure echo
     * @param  string $value value to be escaped
     * @return string value escaped
     */
    public function escape($value)
    {
        return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
    }

    private function __parseecho($matches)
    {
        return $this->__replace('<?php echo $this->escape(' . $matches[1] . '); ?>');
    }

    private function __parseraw($matches)
    {
        return $this->__replace('<?php echo ' . $matches[1] . '; ?>');
    }

    private function __parsestmt($matches)
    {
        return $this->__replace('<?php ' . $matches[1] . '; ?>');
    }

    private function __parseelseif($matches)
    {
        return $this->__replace('<?php } else if(' . $matches[1] . ') { ?>');
    }

    private function __parseloop($matches)
    {
        return $this->__replace(
            "<?php if(is_array($matches[1])){foreach($matches[1] AS $matches[2]) { ?>$matches[3]<?php }} ?>"
        );
    }

    private function __parseloopkeyval($matches)
    {
        return $this->__replace(
            "<?php if(is_array($matches[1])){".
            "foreach($matches[1] AS $matches[2]=>$matches[3]) { ?>$matches[4]<?php }} ?>"
        );
    }

    private function __parseif($matches)
    {
        return $this->__replace("<?php if($matches[1]) { ?>$matches[2]<?php } ?>");
    }

    private function __parse($tFile, $cFile)
    {
        $fileContent = file_get_contents($tFile);
        if ($fileContent === false) {
            throw new \Exception('Can\'t get content of template file "'.$tFile.'"', 500);
        }
        $fileContent = preg_replace('/^(\xef\xbb\xbf)/', '', $fileContent); //EFBBBF
        $fileContent = preg_replace_callback(
            "/\<\!\-\-\s*\\\$\{(.+?)\}\s*\-\-\>/is",
            [$this, '__parsestmt'],
            $fileContent
        );
        $fileContent = preg_replace(
            "/\{(\\\$[a-zA-Z0-9_\[\]\\\ \-\'\,\%\*\/\.\(\)\>\'\"\$\x7f-\xff]+)\}/s",
            '<?php echo $this->escape(\\1); ?>',
            $fileContent
        );
        $fileContent = preg_replace(
            "/\{\{(\\\$[a-zA-Z0-9_\[\]\\\ \-\'\,\%\*\/\.\(\)\>\'\"\$\x7f-\xff]+)\}\}/s",
            '<?php echo \\1; ?>',
            $fileContent
        );
        $fileContent = preg_replace_callback("/\\\$\{(.+?)\}/is", [$this, '__parseecho'], $fileContent);
        $fileContent = preg_replace_callback("/\\\$\{\{(.+?)\}\}/is", [$this, '__parseraw'], $fileContent);
        $fileContent = preg_replace_callback(
            "/\<\!\-\-\s*\{else\s*if\s+(.+?)\}\s*\-\-\>/is",
            [$this, '__parseelseif'],
            $fileContent
        );
        $fileContent = preg_replace_callback(
            "/\<\!\-\-\s*\{elif\s+(.+?)\}\s*\-\-\>/is",
            [$this, '__parseelseif'],
            $fileContent
        );
        $fileContent = preg_replace("/\<\!\-\-\s*\{else\}\s*\-\-\>/is", '<?php } else { ?>', $fileContent);
        for ($i = 0; $i < 5; ++$i) {
            $fileContent = preg_replace_callback(
                "/\<\!\-\-\s*\{loop\s+(\S+)\s+(\S+)\s+(\S+)\s*\}\s*\-\-\>(.+?)\<\!\-\-\s*\{\/loop\}\s*\-\-\>/is",
                [$this, '__parseloopkeyval'],
                $fileContent
            );
            $fileContent = preg_replace_callback(
                "/\<\!\-\-\s*\{loop\s+(\S+)\s+(\S+)\s*\}\s*\-\-\>(.+?)\<\!\-\-\s*\{\/loop\}\s*\-\-\>/is",
                [$this, '__parseloop'],
                $fileContent
            );
            $fileContent = preg_replace_callback(
                "/\<\!\-\-\s*\{if\s+(.+?)\}\s*\-\-\>(.+?)\<\!\-\-\s*\{\/if\}\s*\-\-\>/is",
                [$this, '__parseif'],
                $fileContent
            );
        }
        //Add for call <!--{include othertpl}-->
        $fileContent = preg_replace(
            "#<!--\s*{\s*include\s+([^\{\}]+)\s*\}\s*-->#i",
            '<?php include $this->__template(\'\1\'); ?>',
            $fileContent
        );
        xmkdir(dirname($cFile), 0775);
        if (file_put_contents($cFile, $fileContent) === false) {
            throw new \Exception('Can\'t write parsed template into file "'.$cFile.'"', 500);
        }
    }

    private function __replace($string)
    {
        return str_replace('\"', '"', $string);
    }

    private function __template($tFile)
    {
        $path = $this->getSettings()->getValue('path');
        $dir_template = $path['template'];
        $dir_compiled = $path['compiled'];
        $pattern = '/\.[\w]+$/';
        if (preg_match($pattern, $tFile)) {
            $tFileN = preg_replace($pattern, '', $tFile);
            $tFile = $dir_template . DIRECTORY_SEPARATOR . $tFile;
        } else {
            $tFileN = $tFile;
            $tFile = $dir_template . DIRECTORY_SEPARATOR . $tFileN . '.html';
        }
        $cFile = $dir_compiled . DIRECTORY_SEPARATOR . str_replace(DIRECTORY_SEPARATOR, '_', $tFileN) . '.php';
        if (false === file_exists($tFile)) {
            throw new \Exception('Template file "'.$cFile.'" not found', 404);
        }
        if (false === file_exists($cFile) || @filemtime($tFile) > @filemtime($cFile)) {
            $this->__parse($tFile, $cFile);
        }
        return $cFile;
    }
}
