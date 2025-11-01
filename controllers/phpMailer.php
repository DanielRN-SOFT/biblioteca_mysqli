
<?php

require '../libs/phpmailer/vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;


function enviarCorreo($correoDestinatario, $nombreDestinatario, $apellidoDestinatario, $fechaPrestamo, $fechaDevolucion){
    $mail = new PHPMailer(true);

    try {
        // Configuración del servidor
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';        // Servidor SMTP de Gmail
        $mail->SMTPAuth   = true;
        $mail->Username   = 'bibliotecamysqli551@gmail.com';   // Tu correo
        $mail->Password   = 'lncz klqn cmeh mypr'; // Contraseña o App Password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // TLS
        $mail->Port       = 587;

        // Remitente y destinatario
        $mail->setFrom('daniel20ramirez06@gmail.com', 'Biblioteca MYSQLI');
        $mail->addAddress($correoDestinatario, $nombreDestinatario . " " . $apellidoDestinatario);

        // Contenido
        $mail->isHTML(true);
        $mail->Subject = 'Confirmacion de reserva completada, su prestamo esta listo.';
        $mail->Body    = '<b>¡Cordial saludo!</b> '. $nombreDestinatario . " " . $apellidoDestinatario . '. Su prestamo ya fue aceptado, por favor acerquese a nuestra biblioteca lo más pronto posible, te esperamos   
    <br> <strong> Fecha de prestamo: </strong> ' . $fechaPrestamo . '
    <br> <strong> Fecha de devolucion: </strong> ' .  $fechaDevolucion . ' <br>
    <i>📚​📕​📗​📘​📙​ Biblioteca_mysqli​ ​📕​📗​📘​📙​📚</i>.';
      
        $mail->send();
    } catch (Exception $e) {
        echo "Error al enviar el correo: {$mail->ErrorInfo}";
    }
}


