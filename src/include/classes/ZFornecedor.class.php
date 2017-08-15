<?php
/*
	Copyright 2014 da MZ Software - MZ Desenvolvimento de Sistemas LTDA
	Este arquivo é parte do programa GrandChef - Sistema para Gerenciamento de Churrascarias, Bares e Restaurantes.
	O GrandChef é um software proprietário; você não pode redistribuí-lo e/ou modificá-lo.
	DISPOSIÇÕES GERAIS
	O cliente não deverá remover qualquer identificação do produto, avisos de direitos autorais,
	ou outros avisos ou restrições de propriedade do GrandChef.

	O cliente não deverá causar ou permitir a engenharia reversa, desmontagem,
	ou descompilação do GrandChef.

	PROPRIEDADE DOS DIREITOS AUTORAIS DO PROGRAMA

	GrandChef é a especialidade do desenvolvedor e seus
	licenciadores e é protegido por direitos autorais, segredos comerciais e outros direitos
	de leis de propriedade.

	O Cliente adquire apenas o direito de usar o software e não adquire qualquer outros
	direitos, expressos ou implícitos no GrandChef diferentes dos especificados nesta Licença.
*/
/**
 * Fornecedores de produtos
 */
class ZFornecedor
{
    private $id;
    private $empresa_id;
    private $prazo_pagamento;
    private $data_cadastro;

    public function __construct($fornecedor = array())
    {
        if (is_array($fornecedor)) {
            $this->setID(isset($fornecedor['id'])?$fornecedor['id']:null);
            $this->setEmpresaID(isset($fornecedor['empresaid'])?$fornecedor['empresaid']:null);
            $this->setPrazoPagamento(isset($fornecedor['prazopagamento'])?$fornecedor['prazopagamento']:null);
            $this->setDataCadastro(isset($fornecedor['datacadastro'])?$fornecedor['datacadastro']:null);
        }
    }

    /**
     * Identificador do fornecedor
     */
    public function getID()
    {
        return $this->id;
    }

    public function setID($id)
    {
        $this->id = $id;
    }

    /**
     * Empresa do fornecedor
     */
    public function getEmpresaID()
    {
        return $this->empresa_id;
    }

    public function setEmpresaID($empresa_id)
    {
        $this->empresa_id = $empresa_id;
    }

    /**
     * Prazo em dias para pagamento do fornecedor
     */
    public function getPrazoPagamento()
    {
        return $this->prazo_pagamento;
    }

    public function setPrazoPagamento($prazo_pagamento)
    {
        $this->prazo_pagamento = $prazo_pagamento;
    }

    /**
     * Data de cadastro do fornecedor
     */
    public function getDataCadastro()
    {
        return $this->data_cadastro;
    }

    public function setDataCadastro($data_cadastro)
    {
        $this->data_cadastro = $data_cadastro;
    }

    public function toArray()
    {
        $fornecedor = array();
        $fornecedor['id'] = $this->getID();
        $fornecedor['empresaid'] = $this->getEmpresaID();
        $fornecedor['prazopagamento'] = $this->getPrazoPagamento();
        $fornecedor['datacadastro'] = $this->getDataCadastro();
        return $fornecedor;
    }

    public static function getPeloID($id)
    {
        $query = DB::$pdo->from('Fornecedores')
                         ->where(array('id' => $id));
        return new ZFornecedor($query->fetch());
    }

    public static function getPelaEmpresaID($empresa_id)
    {
        $query = DB::$pdo->from('Fornecedores')
                         ->where(array('empresaid' => $empresa_id));
        return new ZFornecedor($query->fetch());
    }

    private static function validarCampos(&$fornecedor)
    {
        $erros = array();
        if (!is_numeric($fornecedor['empresaid'])) {
            $erros['empresaid'] = 'A empresa não foi informada';
        } else {
            $cliente = ZCliente::getPeloID($fornecedor['empresaid']);
            if ($cliente->getTipo() != ClienteTipo::JURIDICA) {
                $erros['empresaid'] = 'A empresa deve ser do tipo jurídica';
            }
        }
        if (!is_numeric($fornecedor['prazopagamento'])) {
            $erros['prazopagamento'] = 'O prazo de pagamento não foi informado';
        } else {
            $fornecedor['prazopagamento'] = intval($fornecedor['prazopagamento']);
        }
        $fornecedor['datacadastro'] = date('Y-m-d H:i:s');
        if (!empty($erros)) {
            throw new ValidationException($erros);
        }
    }

    private static function handleException(&$e)
    {
        if (stripos($e->getMessage(), 'PRIMARY') !== false) {
            throw new ValidationException(array('id' => 'O ID informado já está cadastrado'));
        }
        if (stripos($e->getMessage(), 'EmpresaID_UNIQUE') !== false) {
            throw new ValidationException(array('empresaid' => 'A empresa informada já está cadastrada'));
        }
    }

    public static function cadastrar($fornecedor)
    {
        $_fornecedor = $fornecedor->toArray();
        self::validarCampos($_fornecedor);
        try {
            $_fornecedor['id'] = DB::$pdo->insertInto('Fornecedores')->values($_fornecedor)->execute();
        } catch (Exception $e) {
            self::handleException($e);
            throw $e;
        }
        return self::getPeloID($_fornecedor['id']);
    }

    public static function atualizar($fornecedor)
    {
        $_fornecedor = $fornecedor->toArray();
        if (!$_fornecedor['id']) {
            throw new ValidationException(array('id' => 'O id do fornecedor não foi informado'));
        }
        self::validarCampos($_fornecedor);
        $campos = array(
            'empresaid',
            'prazopagamento',
        );
        try {
            $query = DB::$pdo->update('Fornecedores');
            $query = $query->set(array_intersect_key($_fornecedor, array_flip($campos)));
            $query = $query->where('id', $_fornecedor['id']);
            $query->execute();
        } catch (Exception $e) {
            self::handleException($e);
            throw $e;
        }
        return self::getPeloID($_fornecedor['id']);
    }

    public static function excluir($id)
    {
        if (!$id) {
            throw new Exception('Não foi possível excluir o fornecedor, o id do fornecedor não foi informado');
        }
        $query = DB::$pdo->deleteFrom('Fornecedores')
                         ->where(array('id' => $id));
        return $query->execute();
    }

    private static function initSearch($nome)
    {
        $query = DB::$pdo->from('Fornecedores f')
                         ->leftJoin('Clientes c ON c.id = f.empresaid');
        $nome = trim($nome);
        if ($nome == '') {
            # não faz nada
        } elseif (check_email($nome)) {
            $query = $query->where('c.email', $nome);
        } elseif (check_cnpj($nome)) {
            $query = $query->where('c.cpf', \MZ\Util\Filter::digits($nome));
        } elseif (check_fone($nome, true)) {
            $_fone = \MZ\Util\Filter::digits($nome);
            $_ddd = substr($_fone, 0, 2).'%';
            if (strlen($_fone) == 10) {
                $_fone = $_ddd . substr($_fone, 2, 8);
            } elseif (strlen($_fone) <= 9) {
                $_fone = '%' . $_fone;
            } else {
                $_fone = $_ddd . substr($_fone, 3);
            }
            $query = $query->where('(c.fone1 LIKE ? OR c.fone2 LIKE ?)', $_fone, $_fone);
        } else {
            $keywords = preg_split('/[\s,]+/', $nome);
            $words = '';
            foreach ($keywords as $word) {
                $words .= '%'.$word.'%';
                $query = $query->orderBy('IF(LOCATE(?, CONCAT(" ", c.nome, " ", COALESCE(c.sobrenome, ""))) = 0, '.
                    '256, LOCATE(?, CONCAT(" ", c.nome, " ", COALESCE(c.sobrenome, "")))) ASC, IF(LOCATE(?, '.
                    'CONCAT(c.nome, " ", COALESCE(c.sobrenome, ""))) = 0, 256, LOCATE(?, CONCAT(c.nome, " ", COALESCE(c.sobrenome, "")))) ASC',
                    ' '.$word, ' '.$word, $word, $word);
            }
            $query = $query->where('CONCAT(c.nome, " ", COALESCE(c.sobrenome, "")) LIKE ?', $words);
        }
        $query = $query->orderBy('c.nome ASC');
        return $query;
    }

    public static function getTodos($nome = null, $inicio = null, $quantidade = null)
    {
        $query = self::initSearch($nome);
        if (!is_null($inicio) && !is_null($quantidade)) {
            $query = $query->limit($quantidade)->offset($inicio);
        }
        $_fornecedores = $query->fetchAll();
        $fornecedores = array();
        foreach ($_fornecedores as $fornecedor) {
            $fornecedores[] = new ZFornecedor($fornecedor);
        }
        return $fornecedores;
    }

    public static function getCount($nome = null)
    {
        $query = self::initSearch($nome);
        return $query->count();
    }

    private static function initSearchDaEmpresaID($empresa_id)
    {
        return   DB::$pdo->from('Fornecedores')
                         ->where(array('empresaid' => $empresa_id))
                         ->orderBy('id ASC');
    }

    public static function getTodosDaEmpresaID($empresa_id, $inicio = null, $quantidade = null)
    {
        $query = self::initSearchDaEmpresaID($empresa_id);
        if (!is_null($inicio) && !is_null($quantidade)) {
            $query = $query->limit($quantidade)->offset($inicio);
        }
        $_fornecedores = $query->fetchAll();
        $fornecedores = array();
        foreach ($_fornecedores as $fornecedor) {
            $fornecedores[] = new ZFornecedor($fornecedor);
        }
        return $fornecedores;
    }

    public static function getCountDaEmpresaID($empresa_id)
    {
        $query = self::initSearchDaEmpresaID($empresa_id);
        return $query->count();
    }
}
