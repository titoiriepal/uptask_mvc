<?php

namespace Classes;

use PHPMailer\PHPMailer\PHPMailer;

class Email{

    public $email;
    public $nombre;
    public $token;
    

    public function __construct($email, $nombre, $token){

        $this->email = $email;
        $this->nombre = $nombre;
        $this->token = $token;

    }

    public function enviarConfirmacion(){

        $mail = new PHPMailer();

        //Configuramos SMTP
        $mail->isSMTP();
        $mail->Host = $_ENV['MAIL_HOST'];
        $mail->SMTPAuth = true;
        $mail->Port = $_ENV['MAIL_PORT'];
        $mail->Username = $_ENV['MAIL_USER'];
        $mail->Password = $_ENV['MAIL_PWD'];
        $mail->SMTPSecure = 'tls';

        $mail->setFrom('CrearCuenta@UptaskTito.com'); //Dominio que se cree para la web
        $mail->addAddress($this->email, $this->nombre);//Correo electronico y nombre del remitente
        $mail->Subject = 'Confirma tu cuenta de UpTask Tito';
        $mail->isHTML(true);
        $mail->CharSet = 'UTF-8';

        $contenido = "<html>";
        $contenido .= " <p><strong>Hola " . $this->nombre  . "</strong> Has creado tu cuenta en Up Task, solo debes confirmarla presionando el siguiente enlace</p>";
        $contenido .=  "<p>Presiona aquí: <a href='". $_ENV['APP_URL'] . "/confirmar-cuenta?token=". $this->token ."'>Confirmar Cuenta</a></p>";
        $contenido .= "<p>Si tú no solicitaste esta cuenta, ignora este mensaje</p>";
        $contenido .= "<p>Por favor, <strong> No respondas </strong> a este mensaje</p>";
        $contenido .= "</html>";

        $mail->Body = $contenido;

        $mail->send();
 
    }


    public function enviarInstruccionesPassword(){
        
        
            $mail = new PHPMailer();

            //Configuramos SMTP
            $mail->isSMTP();
            $mail->Host = $_ENV['MAIL_HOST'];
            $mail->SMTPAuth = true;
            $mail->Port = $_ENV['MAIL_PORT'];
            $mail->Username = $_ENV['MAIL_USER'];
            $mail->Password = $_ENV['MAIL_PWD'];
            $mail->SMTPSecure = 'tls';
    
            $mail->setFrom('cambiarpassword@UptaskTito.com'); //Dominio que se cree para la web
            $mail->addAddress($this->email, $this->nombre);//Correo electronico y nombre del remitente
            $mail->Subject = 'Cambia el password de tu cuenta de UpTask Tito';
            $mail->isHTML(true);
            $mail->CharSet = 'UTF-8';
    
            $contenido = "<html>";
            $contenido .= " <p><strong>Hola " . $this->nombre  . "</strong> Has solicitado el cambio de Password de tu cuenta en Up Task, pulsa el siguiente enlace para cambiarlo</p>";
            $contenido .=  "<p>Presiona aquí: <a href='". $_ENV['APP_URL'] . "/reestablecer?token=". $this->token ."'>Cambiar Password</a></p>";
            $contenido .= "<p>Si tú no solicitaste esta cuenta, ignora este mensaje</p>";
            $contenido .= "<p>Por favor, <strong> No respondas </strong> a este mensaje</p>";
            $contenido .= "</html>";
    
            $mail->Body = $contenido;
    
            $res = $mail->send();


 
    }

}