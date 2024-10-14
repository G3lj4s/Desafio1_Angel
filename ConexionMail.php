<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;

require_once 'PHPMailer-master/src/PHPMailer.php';
require_once 'PHPMailer-master/src/SMTP.php';
require_once 'Config.php';

class ConexionMail {
    public static function obtenerConexionMail() {
        $mail = new PHPMailer();
        
        try {
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = $GLOBALS['mailUsername']; 
            $mail->Password   = $GLOBALS['mailPassword'];        
            $mail->SMTPSecure = 'ssl';                   
            $mail->Port       = 465;                       
    
            return $mail; 
        } catch (Exception $e) {
            return null; 
        }
    }
    public static function mandarNuevaPassword(Usuario $usuario){
        $mail = self::obtenerConexionMail();

        if ($mail === null) {
            return false;
        }
        
        $mail->setFrom('callejasvgn@gmail.com', 'Risk_Angel'); // Emisor
        $mail->addAddress($usuario->getEmail(), $usuario->getName()); // Destinatario
        $mail->isHTML(true);
        $mail->Subject = 'Se ha cambiado tu clave';
        
        $mail->Body = '¡Hola ' . $usuario->getName() . '!' . '<br>' .
                      'Tu contraseña ha sido cambiada exitosamente.' . '<br>' .
                      'Si no has realizado este cambio, por favor contacta con soporte inmediatamente.' . '<br>' .
                      'Nueva contraseña: <b>' . $usuario->getPassword() . '</b>' . '<br>' .
                      'Saludos,<br>' .
                      'El equipo de soporte.';
        
        $mail->AltBody = 'Hola ' . $usuario->getName() . ', tu contraseña ha sido cambiada exitosamente. Si no has realizado este cambio, por favor contacta con soporte inmediatamente. Nueva contraseña: ' . $usuario->getPassword() . ' Saludos, El equipo de soporte.';
        
        if ($mail->send()) {
            return true;
        } else {
            return $mail->ErrorInfo;
        }
         
    }
}