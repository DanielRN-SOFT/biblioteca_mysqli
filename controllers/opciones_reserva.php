<?php

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (
        isset($_POST["IDreserva"]) && !empty($_POST["IDreserva"])
        && isset($_POST["IDlibro"]) && !empty($_POST["IDreserva"])
    ) {
        //====================
        // Conexion a la base de datos
        //===================
        require_once '../models/MYSQL.php';
        $mysql = new MySQL();
        $mysql->conectar();

        // Captura de datos
        $IDreserva = filter_var($_POST["IDreserva"], FILTER_SANITIZE_NUMBER_INT);
        $IDlibro = filter_var($_POST["IDlibro"], FILTER_SANITIZE_NUMBER_INT);
        $opcion = filter_var($_POST["opcion"], FILTER_SANITIZE_FULL_SPECIAL_CHARS);

        // Arreglo de errores
        $errores = [];

        // Si la opcion es aprobar
        if ($opcion == "Aprobar") {
            $nuevoEstado = "Aprobada";
            $mensaje = "Aprobacion de reserva completada";

            // Insertar en prestamo la reserva aprobada
            $insertPrestamo = $mysql->efectuarConsulta("INSERT INTO prestamo(id_reserva,fecha_prestamo,fecha_devolucion, estado) VALUES($IDreserva, NOW(), DATE_ADD(NOW(), INTERVAL 5 DAY), 'Vigente')");
            if(!$insertPrestamo){
                $errores = "Error al insertar PRESTAMO";
            }
        }

        // Si la opcion es rechazar
        if ($opcion == "Rechazar") {
            $deletePrestamo = $mysql->efectuarConsulta("UPDATE prestamo set estado = 'Cancelado' WHERE id_reserva = $IDreserva");

            if(!$deletePrestamo){
                $errores = "Error al eliminar PRESTAMO";
            }
            // $deletePrestamo = $mysql->efectuarConsulta("DELETE FROM prestamo WHERE id_reserva = $IDreserva");
            $nuevoEstado = "Rechazada";
            $mensaje = "Rechazo de reserva completada";
        }

        // Ejecucion de la consulta
        $cambiarEstado = $mysql->efectuarConsulta("UPDATE reserva SET estado ='$nuevoEstado' WHERE id = $IDreserva");

        if(!$cambiarEstado){
            $errores = "Errro al cambiar estado de la RESERVA";
        }

        if (count($errores) == 0) {
            echo json_encode([
                "success" => true,
                "message" => $mensaje
            ]);
        } else {
            echo json_encode([
                "success" => true,
                "message" => "Ocurrio un error..."
            ]);
        }
    }
}
