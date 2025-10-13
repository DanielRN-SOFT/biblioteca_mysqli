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

        // Realizar una accion dependiendo del estado
        if ($estado == "Pendiente" || $estado == "Cancelada") {
            if ($estado == "Pendiente") {
                $nuevoEstado = "Cancelada";
                $mensaje = "Cancelacion de reserva completada";
            } else if ($estado == "Cancelada") {
                $nuevoEstado = "Pendiente";
                $mensaje = "Reintegracion de reserva completada";
            }

            // Ejecucion de la consulta
            $cambiarEstado = $mysql->efectuarConsulta("UPDATE reserva SET estado ='$nuevoEstado' WHERE id = $IDreserva");

            if ($cambiarEstado) {
                echo json_encode([
                    "success" => true,
                    "message" => $mensaje
                ]);
                exit();
            } else {
                echo json_encode([
                    "success" => false,
                    "message" => "Ocurrio un error..."
                ]);
                exit();
            }
        }
    }
}
