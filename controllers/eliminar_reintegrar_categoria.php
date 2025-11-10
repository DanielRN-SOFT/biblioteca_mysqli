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

        // Determina si se quiere eliminar o reintegrar un libro
        if ($estado == "Activo") {

            $consultaReservas = $mysql->efectuarConsulta("SELECT 1 FROM categoria_has_libro WHERE libro_id = $id");
            if (mysqli_num_rows($consultaReservas) > 0) {
                echo json_encode([
                    "success" => false,
                    "message" => "No es posible eliminar esta categoria ya que tiene libros asociados a su nombre"
                ]);
                exit();
            }
            $nuevoEstado = "Inactivo";
            $mensaje =  "Categoria eliminada";
        } else {
            $nuevoEstado = "Activo";
            $mensaje = "Categoria reintegrada nuevamente";
        }

        //Efectuar la consulta
        $cambiarCategoria = $mysql->efectuarConsulta("UPDATE categoria set estado = '$nuevoEstado' WHERE id = $id");

        // En caso de que la consulta se realice correctamente envie un mensaje de confirmacion
        if ($cambiarCategoria) {
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
