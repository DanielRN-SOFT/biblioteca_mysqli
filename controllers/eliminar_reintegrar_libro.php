<?php

// Funcion para ejecutar el cambio de estado de un usuario
// Ya sea para eliminar (INACTIVO) o Reintegrar(ACTIVO)
function cambiarEstadoLibro($id, $estado)
{
    // Requerimos utilizar el modelo
    require_once '../models/MYSQL.php';

    //Instancia del modelo
    $mysql = new MySQL();
    // Conexion con la base de datos
    $mysql->conectar();

    //Efectuar la consulta
    $cambiarLibro = $mysql->efectuarConsulta("UPDATE libro set estado = '$estado' WHERE id = $id");

    // Determinar si eliminar o reintegrar empleado 
    if ($estado == "Activo") {
        $mensaje = "Libro reintegrado nuevamente";
    } else {
        $mensaje =  "Libro eliminado";
    }

    // En caso de que la consulta se realice correctmente envie un mensaje de confirmacion
    if ($cambiarLibro) {
        echo json_encode([
            "success" => true,
            "message" => $mensaje
        ]);
    } else {
        // Si no es porque ocurrio un error
        echo json_encode([
            "success" => false,
            "message" => "Ocurrio un error..."
        ]);
    }
    // Desconectamos la conexion
    $mysql->desconectar();
}


// Si el metodo de envio es POST ejecuta la accion
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Confirmar de que ID contenga un dato valido o no vacio
    if (isset($_POST["id"]) && !empty($_POST["id"])) {
        // Capturar el ID 
        $id = $_POST["id"];
        // Capturar el estado
        $estado = $_POST["estado"];

        // Determina si se quiere eliminar o reintegrar un usuario
        if ($estado == "Activo") {
            cambiarEstadoLibro($id, "Inactivo");
        } else {
            cambiarEstadoLibro($id, "Activo");
        }
    }
}