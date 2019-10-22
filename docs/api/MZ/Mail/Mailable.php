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

use PHPMailer\PHPMailer\PHPMailer;

/**
 * Implement common operations in processing and sending emails
 */
abstract class Mailable
{
    /**
     * Conteudo HTML do email que será enviado
     * @var string
     */
    private $body;
    public $from = [];
    public $to = [];
    public $reply = [];
    public $subject;

    public function __construct()
    {
        $app = app();
        // Dados default (padrão) do nome e e-mail do remetente dos e-mails
        $from = $app->getSystem()->getSettings()->getValue('mail', 'from');
        $name = $app->getSystem()->getCompany()->getNome();

        $this->from($from, $name);
    }

    abstract public function build();

    /**
     * Normaliza os textos UTF-8 do cabeçalho do email
     */
    private static function escapeHead($string)
    {
        return '=?UTF-8?B?'. base64_encode($string) .'?=';
    }

    public function send()
    {
        $this->build();
        /* begin test */
        if (getenv('MAIL_ENV') == 'testing') {
            echo $this->body;
            exit;
        }
        /* end test */
        $app = app();
        /* get from ini */
        $security = ['', 'ssl', 'tls'];
        $host = get_string_config('Email', 'Servidor');
        $port = get_int_config('Email', 'Porta');
        $ssl  = $security[get_int_config('Email', 'Criptografia', 2)];
        $user = get_string_config('Email', 'Usuario');
        $pass = get_string_config('Email', 'Senha');
    
        $subject = self::EscapeHead($this->subject);
        //begin
        $mail = new PHPMailer();
        $mail->IsSMTP();
        $mail->CharSet = 'UTF-8';
        $mail->SMTPAuth   = true;
        $mail->Host = $host;
        $mail->Port = $port;
        if ($ssl=='ssl') {
            $mail->SMTPSecure = "ssl";
        } elseif ($ssl == 'tls') {
            $mail->SMTPSecure = "tls";
        }
        $mail->SMTPOptions = [
            'ssl' => [
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true
            ]
        ];
        $mail->Username = $user;
        $mail->Password = $pass;
        $mail->SetFrom($this->from[0], $this->from[1]);
        $mail->AddReplyTo($this->reply[0], $this->reply[1]);
        $mail->Subject = $subject;
        $mail->AddAddress($this->to[0], $this->to[1]);
        $mail->MsgHTML($this->body);
        foreach ($attachment as $name => $path) {
            $mail->AddAttachment($path, $name);
        }
        if (!$mail->Send()) {
            throw new \Exception(_t('send.mail.fail'), 500);
        }
        return $this;
    }

    /**
     * Render view from template
     * @param string $template template name
     * @param array $data data to pass to template
     * @return \MZ\Response\HtmlResponse template response object
     */
    public function view($template, $data = [])
    {
        $this->body = render($template, $data);
        return $this;
    }

    public function to($email, $name = null)
    {
        $this->to = [$email, $name];
        return $this;
    }

    public function from($email, $name = null)
    {
        $this->from = [$email, $name];
        return $this;
    }

    public function subject($subject)
    {
        $this->subject = $subject;
        return $this;
    }

    public function reply($email, $name = null)
    {
        $this->reply = [$email, $name];
        return $this;
    }
}
