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

            $consultaReservas = $mysql->efectuarConsulta("SELECT 1 FROM reserva_has_libro JOIN reserva ON reserva.id = reserva_has_libro.reserva_id WHERE libro_id = $id AND reserva.estado = 'Pendiente'");
            if (mysqli_num_rows($consultaReservas) > 0) {
                echo json_encode([
                    "success" => false,
                    "message" => "No es posible eliminar este libro ya que tiene reservas pendientes asociadas a su nombre"
                ]);
                exit();
            }
            $consultaPrestamos = $mysql->efectuarConsulta("SELECT 1 FROM reserva_has_libro JOIN reserva ON reserva.id = reserva_has_libro.reserva_id JOIN prestamo ON prestamo.id_reserva = reserva.id WHERE libro_id = $id AND prestamo.estado = 'Prestado'");
            if (mysqli_num_rows($consultaPrestamos) > 0) {
                echo json_encode([
                    "success" => false,
                    "message" => "No es posible eliminar este libro ya que tiene prestamos sin devolver asociados a su nombre"
                ]);
                exit();
            }
            $nuevoEstado = "Inactivo";
            $mensaje =  "Libro eliminado";
        } else {
            $nuevoEstado = "Activo";
            $mensaje = "Libro reintegrado nuevamente";
        }

        //Efectuar la consulta
        $cambiarLibro = $mysql->efectuarConsulta("UPDATE libro set estado = '$nuevoEstado' WHERE id = $id");

        // En caso de que la consulta se realice correctamente envie un mensaje de confirmacion
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
}
