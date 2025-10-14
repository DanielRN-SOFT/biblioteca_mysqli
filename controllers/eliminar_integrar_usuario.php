<?php
// Si el metodo de envio es POST ejecuta la accion
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Confirmar de que ID contenga un dato valido o no vacio
    if (isset($_POST["id"]) && !empty($_POST["id"])) {
        // Requerimos utilizar el modelo
        require_once '../models/MYSQL.php';
        //Instancia del modelo
        $mysql = new MySQL();
        // Conexion con la base de datos
        $mysql->conectar();

        // Capturar el ID 
        $id = $_POST["id"];
        // Capturar el estado
        $estado = $_POST["estado"];

        // Determina si se quiere eliminar o reintegrar un usuario
        if ($estado == "Activo") {
            $nuevoEstado = "Inactivo";
            $mensaje = "Usuario eliminado exitosamente";
        } else {
            $nuevoEstado = "Activo";
            $mensaje = "Usuario reintegrado nuevamente";
        }

        //Efectuar la consulta
        $cambiarUsuario = $mysql->efectuarConsulta("UPDATE usuario set estado = '$nuevoEstado' WHERE id = $id");

        // En caso de que la consulta se realice correctamente envie un mensaje de confirmacion
        if ($cambiarUsuario) {
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
}
