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
 * Monta o email referente a nota fiscal do cliente
 */

class NotaFiscal extends Mailable
{
    public $destinatario;
    public $modo;
    public $filters;
    public $files;

    public function build()
    {
        $company = app()->getSystem()->getCompany();
        $pass = get_string_config('Email', 'Senha', '');
        if ($pass == '') {
            throw new \Exception('O serviço de E-mail não foi configurado', 500);
        }
        $user = get_string_config('Email', 'Usuario');
        $from = get_string_config('Email', 'From', $user);
        $empresa_nome = $company->getNome();
        $msg = 'Segue em anexo nota fiscal';
        if ($this->modo == 'contador') {
            $msg = 'Segue em anexo os arquivos XML das notas fiscais';
        }
        $data = [
            'message' => $msg,
            'automatico' => false,
            'filters' => $this->filters,
            'from_name' => $empresa_nome.' - '.$from,
            'sitename' => $empresa_nome,
            'files' => $this->files,
            'sitelogo' => $company->makeImagemURL(false, 'empresa.png'),
        ];
        if ($this->modo == 'contador') {
            $template = 'email_nota_contador';
        } else {
            $template = 'email_nota_consumidor';
        }
        return $this->to($this->destinatario->getEmail(), $this->destinatario->getNome())
            ->reply($company->getEmail(), $empresa_nome)
            ->subject('Nota fiscal eletrônica')
            ->view($template, $data);
    }
}
