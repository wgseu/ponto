<?php
/**
 * @author: shwdai@gmail.com
 */
class Mailer
{

    private static $from = null;
    function __construct()
    {
    }

    private static function EscapeHead($string, $encoding = 'GB2312')
    {
        $string = mb_convert_encoding($string, $encoding, "UTF-8");
        return '=?' . $encoding . '?B?'. base64_Encode($string) .'?=';
    }
    
    static function SmtpMail($to, $subject, $message, $options = null, $bcc = [], $reply = null, $attachment = [])
    {
        /* settings */
        if (!isset($options['subjectenc'])) {
            $options['subjectenc']  = 'UTF-8';
        }

        if (!isset($options['encoding'])) {
            $options['encoding']    = 'UTF-8';
        }

        if (!isset($options['contentType'])) {
            $options['contentType'] = 'text/plain';
        }

        if ('UTF-8'!=$options['encoding']) {
            $message = mb_convert_encoding($message, $options['encoding'], 'UTF-8');
        }
        global $app;
        /* get from ini */
        $security = ['', 'ssl', 'tls'];
        $host = get_string_config('Email', 'Servidor');
        $port = get_int_config('Email', 'Porta');
        $ssl  = $security[get_int_config('Email', 'Criptografia', 2)];
        $user = get_string_config('Email', 'Usuario');
        $pass = get_string_config('Email', 'Senha');
        $from = get_string_config('Email', 'From', $user);
        $site = $app->getSystem()->getCompany()->getNome();

        $subject = self::EscapeHead($subject, $options['subjectenc']);
        $site = self::EscapeHead($site, $options['subjectenc']);
        $body = $message;
        if (!$reply) {
            $reply = get_string_config('Email', 'Responder', $from);
            $reply_name = $site;
        } else {
            $reply_name = '';
            if (preg_match('/(.*) <(.*)>$/', $reply, $matches)) {
                $reply_name = $matches[1];
                $reply = $matches[2];
            }
        }
        if (!$reply) {
            $reply = $from;
        }
        $to_name = '';
        if (preg_match('/(.*) <(.*)>$/', $to, $matches)) {
            $to_name = $matches[1];
            $to = $matches[2];
        }
        $ishtml = ($options['contentType']=='text/html');
        //begin
        $mail = new PHPMailer();
        $mail->IsSMTP();
        $mail->CharSet = $options['encoding'];
        $mail->SMTPAuth   = true;
        $mail->Host = $host;
        $mail->Port = $port;
        if ($ssl=='ssl') {
            $mail->SMTPSecure = "ssl";
        } elseif ($ssl == 'tls') {
            $mail->SMTPSecure = "tls";
        }
        $mail->Username = $user;
        $mail->Password = $pass;
        $mail->SetFrom($from, $site);
        $mail->AddReplyTo($reply, $reply_name);
        foreach ($bcc as $bo) {
            $mail->AddBCC($bo);
        }
        $mail->Subject = $subject;
        if ($ishtml) {
            $mail->MsgHTML($body);
        } else {
            $mail->Body = $body;
        }
        $mail->AddAddress($to, $to_name);
        foreach ($attachment as $name => $path) {
            $mail->AddAttachment($path, $name);
        }
        return $mail->Send();
    }
}
