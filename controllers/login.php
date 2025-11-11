<?php

// Verificar que se envie por metodo POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (
        isset($_POST["email"]) && !empty($_POST["email"])
        && isset($_POST["password"]) && !empty($_POST["password"])
    ) {

        // Modelo de la BD
        require_once '../models/MYSQL.php';

        // Instancia de la clase
        $mysql = new MySQL();

        // Conexion con la base de datos
        $mysql->conectar();

        // Capturar y sanetizar los datos
        $email = filter_var(trim($_POST["email"]), FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $email = filter_var($email, FILTER_SANITIZE_EMAIL);
       
        $password = $_POST["password"];

        // Realizar la consulta para verificar que existe el usuario
        $consulta = $mysql->efectuarConsulta("SELECT * FROM usuario WHERE email = '$email'");

        // Si se trae una fila EXISTE el usuario
        if ($usuarios = $consulta->fetch_assoc()) {
            // Verificar que el usuario este activo
            if ($usuarios["estado"] == "Activo") {
                if (password_verify($password, $usuarios["password"])) {
                    session_start();

                    $_SESSION["acceso"] = true;
                    $_SESSION["IDusuario"] = $usuarios["id"];
                    $_SESSION["nombreUsuario"] = $usuarios["nombre"];
                    $_SESSION["apellidoUsuario"] = $usuarios["apellido"];
                    $_SESSION["tipoUsuario"] = $usuarios["tipo"];

                    echo json_encode([
                        "success" => true,
                        "message" => "Sesión iniciada"
                    ]);

                    // header("location: ../dist/views/dashboard.php");
                }else{
                    echo json_encode([
                        "success" => false,
                        "message" => "Contraseña incorrecta, por favor vuelva a intentarlo"
                    ]);
                    exit();
                }
            }else{
                echo json_encode([
                    "success" => false,
                    "message" => "Usuario inactivo, por favor intente con otro usuario"
                ]);
                exit();
            }
        } else {
            echo json_encode([
                "success" => false,
                "message" => "Usuario inexistente, por favor vuelva a intentarlo"
            ]);
            exit();
        }
    }else{
        echo json_encode([
            "success" => false,
            "message" => "Todos los campos son obligatorios"
        ]);
        exit();
    }
}
