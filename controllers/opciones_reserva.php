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

       
            if ($opcion == "Aprobar") {
                $nuevoEstado = "Aprobada";
                $mensaje = "Aprobacion de reserva completada";
            } 

            if($opcion == "Rechazar"){
                $nuevoEstado = "Rechazada";
                $mensaje = "Rechazo de reserva completada";
            }

            // Ejecucion de la consulta
            $cambiarEstado = $mysql->efectuarConsulta("UPDATE reserva_has_libro SET estado ='$nuevoEstado' WHERE reserva_id = $IDreserva AND libro_id = $IDlibro");

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
