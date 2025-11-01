<?php

// Verificar que se envie por POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Decision para confirmar que se hayan enviado todos los datos completos
    if (
        isset($_POST["nombre"]) && !empty($_POST["nombre"])
        &&  isset($_POST["apellido"]) && !empty($_POST["apellido"])
        &&  isset($_POST["email"]) && !empty($_POST["email"])
        &&  isset($_POST["password"]) && !empty($_POST["password"])
        &&  isset($_POST["tipo"]) && !empty($_POST["tipo"])
    ) {
        // require del modelo de la base de datos
        require_once '../models/MYSQL.php';
        $mysql = new MySQL();
        $mysql->conectar();

        // Sanetizacion de datos
        $nombre = filter_var(trim($_POST["nombre"]), FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $apellido = filter_var(trim($_POST["apellido"]), FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $email = filter_var(trim($_POST["email"]), FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $email = filter_var($email, FILTER_SANITIZE_EMAIL);
        $password = password_hash($_POST["password"], PASSWORD_BCRYPT);
        $tipo = filter_var(trim($_POST["tipo"]), FILTER_SANITIZE_FULL_SPECIAL_CHARS);



        // Validacion de email
        if (!filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)) {
            echo json_encode([
                "success" => false,
                "message" => "Ingrese un email VALIDO"
            ]);
            exit();
        }


        // Verificar que no se repita el email
        $verificacionEmail = $mysql->efectuarConsulta("SELECT 1 FROM usuario WHERE email = '$email'");

        if (mysqli_num_rows($verificacionEmail) > 0) {
            echo json_encode([
                "success" => false,
                "message" => "Ingrese un email que no este repetido"
            ]);
            exit();
        }

        // Insercion de usuario
        $insertarUsuario = $mysql->efectuarConsulta("INSERT INTO usuario(nombre, apellido, email, password, tipo, estado, fecha_creacion) VALUES('$nombre', '$apellido', '$email', '$password', '$tipo', 'Activo', NOW())");
        if ($insertarUsuario) {
           echo json_encode([
                "success" => true,
                "message" => "Usuario agregado exitosamente"
            ]);
        } else {
           echo json_encode([
                "success" => false,
                "message" => "Ocurrio un error..."
            ]);
        }

        // Desconectamos la conexion
        $mysql->desconectar();
    }else{
        echo json_encode([
            "success" => false,
            "message" => "Todos los campos son obligatorios"
        ]);
    }
}
