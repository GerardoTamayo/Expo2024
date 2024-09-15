<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../../librerias/phpmailer651/src/Exception.php';
require '../../librerias/phpmailer651/src/PHPMailer.php';
require '../../librerias/phpmailer651/src/SMTP.php';

class Props
{
    public static function sendMail($address, $subject, $message, $attachmentPath = null)
    {

        $mail = new PHPMailer(true);

        try {

            // Configuración del servidor

            // $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      // Habilitar salida de depuración detallada
            $mail->isSMTP();                                            // Enviar usando SMTP
            $mail->Host       = 'smtp.gmail.com';                     // Configurar el servidor SMTP para enviar a través de Gmail
            $mail->SMTPAuth   = true;                                   // Habilitar autenticación SMTP
            $mail->Username   = 'waterproofdevice1@gmail.com';                     // Nombre de usuario SMTP
            $mail->Password   = 'nhqmlqfwlgpbqeng';                               // Contraseña SMTP
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            // Habilitar cifrado TLS implícito
            $mail->Port       = 465;                                    // Puerto TCP para conectarse; usa 587 si has configurado `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`
            // Destinatarios
            $mail->setFrom('waterproofdevice1@gmail.com', 'Distribuidora TMG'); // Quien lo envía
            $mail->addAddress($address);     // Agregar un destinatario
            $mail->CharSet = 'UTF-8'; //caracteres especiales
            $mail->isHTML(true);
            $mail->Subject = $subject;
            $mail->Body = $message;

            // Añadir adjunto
            if ($attachmentPath) {
                $mail->addAttachment($attachmentPath);
            }

            $mail->send();
            return true;
        } catch (Exception $e) {
            return false;
        }
    }
}
