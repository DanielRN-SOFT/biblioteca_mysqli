<?php
session_start();
header('Content-Type: application/json; charset=utf-8');

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if (
        isset($_POST["nombre"]) && !empty($_POST["nombre"])
        &&  isset($_POST["apellido"]) && !empty($_POST["apellido"])
        &&  isset($_POST["email"]) && !empty($_POST["email"])
    ) {
        // Requerir el modelo a utilizar
        require_once '../models/MYSQL.php';

        // Instancia de la clase
        $mysql = new MySQL();

        // Conexion a la BD
        $mysql->conectar();

        // Capturar los datos del formulario
        $idUsuario = $_SESSION['IDusuario'];

        // Sanitización de datos
        $nombre = filter_var(trim($_POST["nombre"]), FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $apellido = filter_var(trim($_POST["apellido"]), FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $email = filter_var(trim($_POST["email"]), FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $email = filter_var($email, FILTER_SANITIZE_EMAIL);

        // Verificar que se haya ingresado un email válido
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            echo json_encode([
                "success" => false,
                "message" => "Ingrese un email válido"
            ]);
            exit();
        }

        // Verificar que no exista ese email en la BD
        $verificacionEmail = $mysql->efectuarConsulta("SELECT 1 FROM usuario WHERE email = '$email' AND id != $idUsuario");

        if (mysqli_num_rows($verificacionEmail) > 0) {
            echo json_encode([
                "success" => false,
                "message" => "Ingrese un email que no esté repetido"
            ]);
            exit();
        }

        // Obtener contraseña actual de la BD
        $passwordBD = $mysql->efectuarConsulta("SELECT password FROM usuario WHERE id = $idUsuario");
        $passwordBD = $passwordBD->fetch_assoc()["password"];

        // Variable para la nueva contraseña
        $newPassword = $passwordBD; // Por defecto, mantener la actual
        $cambiarPassword = false; // Bandera para saber si se quiere cambiar

        // Si el usuario quiere cambiar su contraseña
        if (
            isset($_POST["oldPassword"]) && !empty($_POST["oldPassword"])
            &&  isset($_POST["newPassword"]) && !empty($_POST["newPassword"])
        ) {
            $cambiarPassword = true;
            $newPassword = password_hash($_POST["newPassword"], PASSWORD_BCRYPT);
        }

        if (empty($_POST["oldPassword"])) {
            echo json_encode([
                "success" => false,
                "message" => "Ingrese su contraseña actual para actualizar su perfil"
            ]);
            exit();
        }
        // Si quiere cambiar contraseña, verificar que la antigua sea correcta
        if (!password_verify($_POST["oldPassword"], $passwordBD)) {
            echo json_encode([
                "success" => false,
                "message" => "La contraseña actual es incorrecta"
            ]);
            exit();
        } else {
            $update = $mysql->efectuarConsulta("UPDATE usuario SET nombre='$nombre', apellido='$apellido', email='$email', password='$newPassword' WHERE id=$idUsuario");

            if ($update) {
                echo json_encode([
                    "success" => true,
                    "message" => "Perfil actualizado exitosamente"
                ]);
            } else {
                echo json_encode([
                    "success" => false,
                    "message" => "Ocurrió un error al actualizar el perfil"
                ]);
            }
        }
    } else {
        echo json_encode([
            "success" => false,
            "message" => "Faltan campos por rellenar, Intentelo de nuevo"
        ]);
        exit();
    }



    // Desconectar de la BD
    $mysql->desconectar();
}
