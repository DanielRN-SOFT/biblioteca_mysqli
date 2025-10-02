<?php

function cambiarEstado($id, $estado)
{
    // Requerimos utilizar el modelo
    require_once '../models/MYSQL.php';

    //Instancia del modelo
    $mysql = new MySQL();
    $mysql->conectar();

    //Efectuar la consulta
    $cambiarUsuario = $mysql->efectuarConsulta("UPDATE usuarios set estado = '$estado' WHERE id = $id");


    // Determinar si eliminar o reintegrar empleado 
    if ($estado == "Activo") {
        $mensaje = "Usuario reintegrado nuevamente";
    } else {
        $mensaje =  "Usuario eliminado";
    }


    // En caso de que la consulta se halla realizado envie un mensaje de confirmacion
    if ($cambiarUsuario) {
        echo json_encode([
            "success" => true,
            "message" => $mensaje
        ]);
    } else {
        echo json_encode([
            "success" => false,
            "message" => "Ocurrio un error..."
        ]);
    }

    // Desconectamos la conexion
    $mysql->desconectar();
}
// Si el metodo es POST ejecuta la accion
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["id"]) && !empty($_POST["id"])) {
        $id = $_POST["id"];
        $estado = $_POST["estado"];

        if ($estado == "Activo") {
            cambiarEstado($id, "Inactivo");
        } else {
            cambiarEstado($id, "Activo");
        }
    }
}
