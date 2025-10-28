<?php

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (
        isset($_POST["IDreserva"]) && !empty($_POST["IDreserva"])
    ) {
        //====================
        // Conexion a la base de datos
        //===================
        require_once '../models/MYSQL.php';
        $mysql = new MySQL();
        $mysql->conectar();

        // Captura de datos
        $IDreserva = filter_var($_POST["IDreserva"], FILTER_SANITIZE_NUMBER_INT);
        $estado = filter_var($_POST["estado"], FILTER_SANITIZE_FULL_SPECIAL_CHARS);

        // Realizar una accion dependiendo del estado
        if ($estado == "Pendiente" || $estado == "Cancelada") {
            if ($estado == "Pendiente") {
                $nuevoEstado = "Cancelada";
                $mensaje = "Cancelacion de reserva completada";

                // Seleccionar libros a descontar 
                $libros = $mysql->efectuarConsulta("SELECT libro_id FROM reserva_has_libro WHERE reserva_id = $IDreserva");

                // Sumar de nuevo al inventario en caso de rechazar la reserva
                while ($fila = $libros->fetch_assoc()) {
                    $ID = $fila["libro_id"];
                    $updateInventario = $mysql->efectuarConsulta("UPDATE libro set cantidad = cantidad + 1 WHERE libro.id = $ID");
                }
            } else if ($estado == "Cancelada") {
                $nuevoEstado = "Pendiente";
                $mensaje = "Reintegracion de reserva completada";

                // Seleccionar libros a descontar 
                $libros = $mysql->efectuarConsulta("SELECT libro_id FROM reserva_has_libro WHERE reserva_id = $IDreserva");


              
                while ($fila = $libros->fetch_assoc()) {
                    // ID de libro
                    $IDlibro = $fila["libro_id"];

                    // Seleccionar libros para saber su stock 
                    $libros = $mysql->efectuarConsulta("SELECT cantidad FROM libro WHERE id = $IDlibro");
                    $cantidad = $libros->fetch_assoc()["cantidad"];
                    // Validar si hay inventario
                    if ($cantidad == 0) {
                        if ($libros) {
                            echo json_encode([
                                "success" => false,
                                "message" => "No hay existencias por el momento en ese libro"
                            ]);
                            exit();
                        }
                    }

                    // restar de nuevo al inventario en caso de reintegrar la reserva 
                    $updateInventario = $mysql->efectuarConsulta("UPDATE libro set cantidad = cantidad - 1 WHERE libro.id = $IDlibro");

                 
                }
            }

            // Ejecucion de la consulta
            $cambiarEstado = $mysql->efectuarConsulta("UPDATE reserva SET estado ='$nuevoEstado' WHERE id = $IDreserva");

            // Si la consulta fue exitosa TRUE
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
