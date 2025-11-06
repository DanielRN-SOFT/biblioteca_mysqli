<?php

// Verificar que se envie por metodo POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (
        isset($_POST["nombre"]) && !empty($_POST["nombre"])
        && isset($_POST["apellido"]) && !empty($_POST["apellido"])
        && isset($_POST["email"]) && !empty($_POST["email"])
        && isset($_POST["password"]) && !empty($_POST["password"])
    ) {

        // Modelo de la BD
        require_once '../models/MYSQL.php';

        // Instancia de la clase
        $mysql = new MySQL();

        // Conexion con la base de datos
        $mysql->conectar();

        // Capturar y sanetizar los datos
        $nombre = filter_var(trim($_POST["nombre"]), FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $apellido = filter_var(trim($_POST["apellido"]), FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $email = filter_var(trim($_POST["email"]), FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $email = filter_var($email, FILTER_SANITIZE_EMAIL);
        $password = $_POST["password"];
        $confirmarPassword = $_POST["confirmarPassword"];

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            echo json_encode([
                "success" => false,
                "message" => "Ingrese un email que sea valido"
            ]);
            exit();
        }

        if ($password != $confirmarPassword) {
            echo json_encode([
                "success" => false,
                "message" => "Ambas contraseñas tienen que coincidir"
            ]);
            exit();
        }

        // Si pasa la validacion, se encripta la password
        $password = password_hash($_POST["password"], PASSWORD_BCRYPT);

        // Realizar la consulta para verificar que existe el usuario
        $consulta = $mysql->efectuarConsulta("SELECT * FROM usuario WHERE email = '$email'");

        // Si no trae ninguna fila NO EXISTE el usuario
        if (!$usuarios = $consulta->fetch_assoc()) {
            $insertarUsuario = $mysql->efectuarConsulta("INSERT INTO usuario(nombre, apellido, email, password, tipo, estado, fecha_creacion) VALUES('$nombre', '$apellido', '$email', '$password', 'Cliente', 'Activo', NOW())");

            if ($insertarUsuario) {
                echo json_encode([
                    "success" => true,
                    "message" => "Usuario creado exitosamente"
                ]);
                exit();
            }
        } else {
            echo json_encode([
                "success" => false,
                "message" => "Email ya registrado, ingrese otro email"
            ]);
            exit();
        }
    } else {
        echo json_encode([
            "success" => false,
            "message" => "Todos los campos son obligatorios"
        ]);
        exit();
    }
}
