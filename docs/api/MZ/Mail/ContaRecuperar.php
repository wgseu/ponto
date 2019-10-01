<?php
/**
 * Copyright (c) 2018 GrandChef Desenvolvimento de Sistemas Ltda - All Rights Reserved
 *
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 *
 * @author Equipe de Desenvolvimento do GrandChef <desenvolvimento@grandchef.com.br>
 */
namespace MZ\Mail;

/**
 * Monta o email de recuperação de senha do cliente
 */
class ContaRecuperar extends Mailable
{
    /**
     * Cliente que deseja recuperar a conta
     * @var \MZ\Account\Cliente
     */
    public $cliente;

    public function build()
    {
        $data = [
            'company' => app()->getSystem()->getCompany(),
            'cliente_secreto' => $this->cliente->getSecreto(),
            'cliente_nome' => $this->cliente->getNome(),
            'automatico' => true,
            'from_name' => app()->getSystem()->getCompany()->getFantasia(),
        ];
        return $this->view('email_recuperar', $data)
            ->to($this->cliente->getEmail(), $this->cliente->getNome())
            ->subject(_t('recover.title'));
    }
}
