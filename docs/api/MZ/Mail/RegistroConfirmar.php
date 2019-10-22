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
 * Monta o email de confirmação do registro do cliente
 */
class RegistroConfirmar extends Mailable
{
    /**
     * Cliente que se cadastrou
     * @var \MZ\Account\Cliente
     */
    public $cliente;

    public function build()
    {
        $company = app()->getSystem()->getCompany();
        $data = [
            'cliente_secreto' => $this->cliente->getSecreto(),
            'cliente_nome' => $cliente->getNome(),
            'automatico' => true,
            'from_name' => $company->getNome(),
            'sitename' => $company->getNome(),
            'sitelogo' => $company->makeImagemURL(false, 'empresa.png'),
        ];
        return $this->view('email_confirmacao', $data)
            ->to($this->cliente->getEmail(), $this->cliente->getNome())
            ->subject('Confirmação de cadastro');
    }
}
