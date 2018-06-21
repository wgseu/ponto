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

use MZ\Payment\FormaPagto;
use MZ\Invoice\Nota;
use MZ\Invoice\Imposto;

class NFeUtil extends \NFe\Common\Util
{

    public static function toAmbiente($ambiente)
    {
        switch ($ambiente) {
            case Nota::AMBIENTE_HOMOLOGACAO:
                return \NFe\Core\Nota::AMBIENTE_HOMOLOGACAO;
            case Nota::AMBIENTE_PRODUCAO:
                return \NFe\Core\Nota::AMBIENTE_PRODUCAO;
        }
        throw new \Exception('Ambiente de emissão "'.$ambiente.'" desconhecido', 500);
    }

    public static function toImposto($imposto)
    {
        switch ($imposto->getGrupo()) {
            case Imposto::GRUPO_PIS:
                throw new \Exception('Imposto "PIS" não suportado', 500);
            case Imposto::GRUPO_COFINS:
                throw new \Exception('Imposto "COFINS" não suportado', 500);
            case Imposto::GRUPO_IPI:
                throw new \Exception('Imposto "IPI" não suportado', 500);
            case Imposto::GRUPO_II:
                throw new \Exception('Imposto "II" não suportado', 500);
            default: // Imposto::GRUPO_ICMS:
                $nome = 'ICMS';
                $codigo = $imposto->getCodigo();
                if ($imposto->isSimples()) {
                    $nome .= 'SN';
                    switch ($codigo) {
                        case 103:
                        case 300:
                        case 400:
                            $codigo = 102;
                            break;
                        case 203:
                            $codigo = 202;
                            break;
                    }
                } else {
                    switch ($codigo) {
                        case 41:
                        case 50:
                            $codigo = 40;
                            break;
                    }
                }
                $nome .= self::padDigit($codigo, 2);
                break;
        }
        $_imposto = \NFe\Entity\Imposto::criaPeloNome($nome, false);
        if ($_imposto === false) {
            throw new \Exception('Não foi possível criar o imposto "'.$nome.'"', 500);
        }
        $_imposto->setTributacao($imposto->getCodigo());
        return $_imposto;
    }

    public static function toFormaPagamento($forma)
    {
        switch ($forma) {
            case FormaPagto::TIPO_DINHEIRO:
                return \NFe\Entity\Pagamento::FORMA_DINHEIRO;
            case FormaPagto::TIPO_CARTAO:
                return \NFe\Entity\Pagamento::FORMA_CREDITO;
            case FormaPagto::TIPO_CHEQUE:
                return \NFe\Entity\Pagamento::FORMA_CHEQUE;
            case FormaPagto::TIPO_CONTA:
                return \NFe\Entity\Pagamento::FORMA_CREDIARIO;
            case FormaPagto::TIPO_CREDITO:
                return \NFe\Entity\Pagamento::FORMA_CREDIARIO;
            case FormaPagto::TIPO_TRANSFERENCIA:
                return \NFe\Entity\Pagamento::FORMA_DEBITO;
        }
        return \NFe\Entity\Pagamento::FORMA_OUTROS;
    }

    public static function toBandeira($descricao)
    {
        $descricao = mb_strtolower($descricao);
        if (mb_strpos($descricao, 'visa') !== false) {
            return \NFe\Entity\Pagamento::BANDEIRA_VISA;
        }
        if (mb_strpos($descricao, 'mastercard')) {
            return \NFe\Entity\Pagamento::BANDEIRA_MASTERCARD;
        }
        if (mb_strpos($descricao, 'amex') !== false) {
            return \NFe\Entity\Pagamento::BANDEIRA_AMEX;
        }
        if (mb_strpos($descricao, 'sorocred') !== false) {
            return \NFe\Entity\Pagamento::BANDEIRA_SOROCRED;
        }
        return \NFe\Entity\Pagamento::BANDEIRA_OUTROS;
    }

    /**
     * Corrige a codificação do texto apenas para o estado do Mato Grosso
     */
    public function fixEncoding($text)
    {
        global $app;

        $estado = $app->getSystem()->getState();
        if ($estado->getUF() == 'MT') {
            return \NFeUtil::removeAccent($text);
        }
        return $text;
    }

    public static function removeAccent($str)
    {
        return iconv('UTF-8', 'ASCII//TRANSLIT', str_replace(['ª', 'º'], ['', ''], self::StripAccents($str)));
    }
    
    public static function stripAccents($str)
    {
        return strtr(
            utf8_decode($str),
            utf8_decode('àáâãäçèéêëìíîïñòóôõöùúûüýÿÀÁÂÃÄÇÈÉÊËÌÍÎÏÑÒÓÔÕÖÙÚÛÜÝ'),
            'aaaaaceeeeiiiinooooouuuuyyAAAAACEEEEIIIINOOOOOUUUUY'
        );
    }
}
