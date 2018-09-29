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
namespace MZ\Association;

use MZ\Payment\Cartao;
use MZ\Logger\Log;

class Card
{
    /**
     * Integration
     * @var \MZ\System\Integracao
     */
    private $integracao;
    /**
     * Dados
     * @var array
     */
    private $dados;
    /**
     * Cartões
     * @var array
     */
    private $cartoes;
    /**
     * Códigos
     * @var array
     */
    private $codigos;

    /**
     * @param \MZ\System\Integracao $integracao
     * @param array $codigos
     */
    public function __construct($integracao, $codigos)
    {
        $this->integracao = $integracao;
        $this->dados = $this->integracao->read();
        $this->cartoes = isset($this->dados['cartoes'])?$this->dados['cartoes']:[];
        $this->codigos = $codigos;
    }

    public function getCartoes()
    {
        return $this->cartoes;
    }

    public function getDados()
    {
        return $this->dados;
    }

    public function update($codigo, $id)
    {
        if (is_null($codigo) || is_null($id)) {
            throw new \Exception('Código ou cartão não informado', 401);
        }
        if (!array_key_exists($codigo, $this->codigos)) {
            throw new \Exception('O cartão informado não existe no iFood', 404);
        }
        $cartao = Cartao::findByID($id);
        if (!$cartao->exists() && is_numeric($id)) {
            throw new \Exception('Cartão não encontrado', 401);
        }
        $this->cartoes[$codigo] = $cartao->getID();
        $this->dados = isset($this->dados)?$this->dados:[];
        $this->dados['cartoes'] = $this->cartoes;
        $this->integracao->write($this->dados);
        return $cartao;
    }

    public function findAll()
    {
        $codigos = $this->codigos;
        foreach ($codigos as $index => $value) {
            $codigos[$index]['cartao'] = new Cartao();
            $codigos[$index]['status'] = 'empty';
            $codigos[$index]['icon'] = 'save';
        }
        foreach ($this->cartoes as $index => $id) {
            $cartao = Cartao::findByID($id);
            $status = '';
            if (!$cartao->exists()) {
                $status = 'empty';
            }
            $codigos[$index]['cartao'] = $cartao;
            $codigos[$index]['status'] = $status;
            $codigos[$index]['icon'] = 'save';
        }
        return $codigos;
    }
}
