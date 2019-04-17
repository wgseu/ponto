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
 * Processa o email recebido pelo form de contato
 */
class Contato extends Mailable
{
    public $email;
    public $nome;
    public $assunto;
    public $mensagem;

    public function build()
    {
        $company = app()->getSystem()->getCompany();
        $user = get_string_config('Email', 'Usuario');
        $from = get_string_config('Email', 'From', $user);
        $to = app()->getSystem()->getSettings()->getValue('mail', 'from');
        $data = [
        'message' => $this->mensagem,
        'automatico' => false,
        'from_name' => $this->nome.' - '.$this->email,
        'sitename' => $company->getNome(),
        'sitelogo' => $company->makeImagemURL(),
    ];
        return $this->to($to, $company)
        ->subject($this->assunto)
        ->reply($this->email, $this->nome)
        ->view('email_contato', $data);
    }
}
