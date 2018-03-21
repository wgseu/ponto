<?php
function mail_custom($emails = [], $subject, $message, $reply = null, $attachment = [])
{
    global $__empresa__;
    settype($emails, 'array');

    $options = [
        'contentType' => 'text/html',
        'encoding' => 'UTF-8',
    ];
    $to = array_shift($emails);
    return Mailer::SmtpMail($to, $subject, $message, $options, $emails, $reply, $attachment);
}

function mail_recuperar($cliente)
{
    global $__empresa__;

    $vars = [
        'cliente_secreto' => $cliente->getSecreto(),
        'cliente_nome' => $cliente->getNome(),
        'automatico' => true,
        'from_name' => $__empresa__->getNome(),
        'sitename' => $__empresa__->getNome(),
        'sitelogo' => get_image_url($__empresa__->getImagem(), 'cliente', 'empresa.png'),
    ];
    $message = render('email_recuperar', $vars);
    /* begin test */
    //echo $message;
    //exit;
    /* end test */
    $subject = "Recuperar conta";
    return mail_custom($cliente->getEmail(), $subject, $message);
}

function mail_confirmacao($cliente)
{
    global $__empresa__;

    $vars = [
        'cliente_secreto' => $cliente->getSecreto(),
        'cliente_nome' => $cliente->getNome(),
        'automatico' => true,
        'from_name' => $__empresa__->getNome(),
        'sitename' => $__empresa__->getNome(),
        'sitelogo' => get_image_url($__empresa__->getImagem(), 'cliente', 'empresa.png'),
    ];
    $message = render('email_confirmacao', $vars);
    /* begin test */
    //echo $message;
    //exit;
    /* end test */
    $subject = "Confirmação de cadastro";
    return mail_custom($cliente->getEmail(), $subject, $message);
}

function mail_contato($email, $nome, $assunto, $mensagem)
{
    global $__empresa__;

    $user = get_string_config('Email', 'Usuario');
    $from = get_string_config('Email', 'From', $user);
    $to = $__empresa__->getNome().' <'.$from.'>';
    $vars = [
        'clientphone' => $fone,
        'message' => $mensagem,
        'automatico' => false,
        'from_name' => $nome.' - '.$email,
        'sitename' => $__empresa__->getNome(),
        'sitelogo' => get_image_url($__empresa__->getImagem(), 'cliente', 'empresa.png'),
    ];
    $message = render('email_contato', $vars);
    /* begin test */
    //echo $message;
    //exit;
    /* end test */
    $reply = "{$nome} <{$email}>";
    return mail_custom($to, $assunto, $message, $reply);
}

function mail_nota($email, $nome, $modo, $filters, $files = [])
{
    global $__empresa__;

    $pass = get_string_config('Email', 'Senha', '');
    if ($pass == '') {
        throw new Exception('O serviço de E-mail não foi configurado', 500);
    }
    $assunto = 'Nota fiscal eletrônica';
    $user = get_string_config('Email', 'Usuario');
    $from = get_string_config('Email', 'From', $user);
    $empresa_nome = $__empresa__->getNome();
    $to = $empresa_nome.' <'.$from.'>';
    $msg = 'Segue em anexo nota fiscal';
    if ($modo == 'contador') {
        $msg = 'Segue em anexo os arquivos XML das notas fiscais';
    }
    $vars = [
        'message' => $msg,
        'automatico' => false,
        'filters' => $filters,
        'from_name' => $empresa_nome.' - '.$from,
        'sitename' => $empresa_nome,
        'sitelogo' => get_image_url($__empresa__->getImagem(), 'cliente', 'empresa.png'),
    ];
    if ($modo == 'contador') {
        $message = render('email_nota_contador', $vars);
    } else {
        $message = render('email_nota_consumidor', $vars);
    }
    /* begin test */
    // echo $message;
    // exit;
    /* end test */
    $reply = "{$empresa_nome} <{$from}>";
    if (!mail_custom($to, $assunto, $message, $reply, $files)) {
        throw new Exception('Não foi possível enviar o E-mail', 500);
    }
}
