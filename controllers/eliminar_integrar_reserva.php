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
                $errores = [];
                // Seleccionar libros a descontar 
                $libros = $mysql->efectuarConsulta("SELECT libro_id, libro.titulo, libro.cantidad FROM reserva_has_libro JOIN libro ON reserva_has_libro.libro_id = libro.id WHERE reserva_id = $IDreserva");

                while ($fila = $libros->fetch_assoc()) {
                    // Captura de variables
                    $IDlibro = $fila["libro_id"];
                    $cantidad = $fila["cantidad"];
                    $nombre = $fila["titulo"];

                    // Sumar de nuevo al inventario en caso de cancelar la reserva 
                    $updateInventario = $mysql->efectuarConsulta("UPDATE libro set cantidad = cantidad + 1 WHERE libro.id = $IDlibro");

                    if(!$updateInventario){
                        $errores = "Error en el UPDATE de cantidad de libros en el libro: $nombre";
                    }
                }
            }
        } 

            // Ejecucion de la consulta
            $cambiarEstado = $mysql->efectuarConsulta("UPDATE reserva SET estado ='$nuevoEstado' WHERE id = $IDreserva");

            if(!$cambiarEstado){
                $errores = "Error en el UPDATE de estado de la RESERVA";
            }

            // Si no hay ningun error
            if (count($errores) === 0) {
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

