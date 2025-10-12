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
        $estado = filter_var($_POST["estado"], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $opcion = $_POST["opcion"];

        // Validar que la reserva no este aprobada ya
       if($estado == "Aprobada"){
        echo json_encode([
            "success" => false,
            "message" => "Esa reserva ya fue aprobada"
        ]);
        exit();
       }
            if ($opcion == "Aprobar") {
                $nuevoEstado = "Aprobada";
                $mensaje = "Aprobacion de reserva completada";
                
                // Insertar en prestamo
                $insertPrestamo = $mysql->efectuarConsulta("INSERT INTO prestamo(id_reserva,fecha_prestamo,fecha_devolucion) VALUES($IDreserva, NOW(), DATE_ADD(NOW(), INTERVAL 5 DAY))");
            } 

            if($opcion == "Rechazar"){
                $nuevoEstado = "Rechazada";
                $mensaje = "Rechazo de reserva completada";
            }

            // Ejecucion de la consulta
            $cambiarEstado = $mysql->efectuarConsulta("UPDATE reserva SET estado ='$nuevoEstado' WHERE id = $IDreserva");

            if ($cambiarEstado) {
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
