<?php

// Verificar que se envie por POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Decision para confirmar que se hayan enviado todos los datos completos
    if (
        isset($_POST["nombre"]) && !empty($_POST["nombre"])
        &&  isset($_POST["apellido"]) && !empty($_POST["apellido"])
        &&  isset($_POST["email"]) && !empty($_POST["email"])
        &&  isset($_POST["tipo"]) && !empty($_POST["tipo"])
    ) {

        // Requerir el modelo a utilizar
        require_once '../models/MYSQL.php';

        // Instanciar la clase MYSQL
        $mysql = new MySQL();

        // Conexion a la base de datos
        $mysql->conectar();

        // ID del usuario a editar
        $id = $_POST["IDusuario"];

        // Sanetizacion de datos
        $nombre = filter_var(trim($_POST["nombre"]), FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $apellido = filter_var(trim($_POST["apellido"]), FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $email = filter_var(trim($_POST["email"]), FILTER_SANITIZE_EMAIL);
        $oldPassword = $_POST["oldPassword"];
        $tipo = filter_var(trim($_POST["tipo"]), FILTER_SANITIZE_FULL_SPECIAL_CHARS);

        //Verificar que se haya ingresado un email valido
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            echo json_encode([
                "success" => false,
                "message" => "Ingrese un email valido"
            ]);
            exit();
        }

        // Verificar que no exista ese email en la BD
        $verificacionEmail = $mysql->efectuarConsulta("SELECT 1 FROM usuario WHERE email = '$email' AND id != $id");

        if (mysqli_num_rows($verificacionEmail) > 0) {
            echo json_encode([
                "success" => false,
                "message" => "Ingrese un email que no este repetido"
            ]);
            exit();
        }

        // Consulta para traer la contraseña de la bd
        $passwordBD = $mysql->efectuarConsulta("SELECT password FROM usuario where id = $id");
        $passwordBD = $passwordBD->fetch_assoc()["password"];

        // En caso de que el usuario quiera cambiar su contraseña
        if (
            isset($_POST["oldPassword"]) && !empty($_POST["oldPassword"])
            &&  isset($_POST["newPassword"]) && !empty($_POST["newPassword"])
        ) {
           
            // Verificar que la contraseña de la bd coincida con la que ingrese el usuario
            if(password_verify($_POST["oldPassword"], $passwordBD)){
                $newPassword = password_hash($_POST["newPassword"], PASSWORD_BCRYPT);
            }else{
                echo json_encode([
                    "success" => false,
                    "message" => "Contraseña incorrecta, intentelo de nuevo"
                ]);
                exit();
            }
        }else{
            // En caso de que no, newPassword va ser igual a la password de la BD
            $newPassword = $passwordBD;
        }


        // Si se pasan todas la validaciones ejecutar el UPDATE

        $update = $mysql->efectuarConsulta("UPDATE usuario SET nombre = '$nombre', apellido = '$apellido', email = '$email', password = '$newPassword', tipo = '$tipo' WHERE id = $id");

        // Si la consulta resulta exitosa, enviar JSON de confirmacion
        if($update){
            echo json_encode([
                "success" => true,
                "message" => "Usuario editado exitosamente"
            ]);
               
        }else{
            // Si no, es porque ocurrio un error
            echo json_encode([
                "success" => false,
                "message" => "Ocurrio un error al editar "
            ]);
         
        }

        // Desconectamos la conexion
        $mysql->desconectar();
    }
}
