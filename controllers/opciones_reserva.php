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
        $estadoBD = filter_var($_POST["estado"], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $opcion = filter_var($_POST["opcion"], FILTER_SANITIZE_FULL_SPECIAL_CHARS);

        // Arreglo de errores
        $errores = [];

        // Si la opcion es aprobar
        if ($opcion == "Aprobar") {
            $nuevoEstado = "Aprobada";
            $mensaje = "Aprobacion de reserva completada";

            // Insertar en prestamo la reserva aprobada
            $insertPrestamo = $mysql->efectuarConsulta("INSERT INTO prestamo(id_reserva,fecha_prestamo,fecha_devolucion, estado) VALUES($IDreserva, NOW(), DATE_ADD(NOW(), INTERVAL 5 DAY), 'Vigente')");

            if ($estadoBD === "Rechazada") {
                // Seleccionar libros a restar 
                $libros = $mysql->efectuarConsulta("SELECT libro_id FROM reserva_has_libro WHERE reserva_id = $IDreserva");

                // restar de nuevo al inventario en caso de aprobar la reserva que fue rechazada
                while ($fila = $libros->fetch_assoc()) {
                    $ID = $fila["libro_id"];
                    $updateInventario = $mysql->efectuarConsulta("UPDATE libro set cantidad = cantidad - 1 WHERE libro.id = $ID");

                    if (!$updateInventario) {
                        $errores = "Error al restar inventario";
                    }
                }
            }

            if (!$insertPrestamo) {
                $errores = "Error al insertar PRESTAMO";
            }
        }

        // Si la opcion es rechazar
        if ($opcion == "Rechazar") {
            // $deletePrestamo = $mysql->efectuarConsulta("UPDATE prestamo set estado = 'Cancelado' WHERE id_reserva = $IDreserva");

            // Eliminar prestamo
            $deletePrestamo = $mysql->efectuarConsulta("DELETE FROM prestamo WHERE id_reserva = $IDreserva");
            if (!$deletePrestamo) {
                $errores = "Error al eliminar PRESTAMO";
            }

            // Asignar nuevo el nuevo estado
            $nuevoEstado = "Rechazada";
            $mensaje = "Rechazo de reserva completada";


            // Seleccionar libros a sumar 
            $libros = $mysql->efectuarConsulta("SELECT libro_id FROM reserva_has_libro WHERE reserva_id = $IDreserva");

            // Sumar de nuevo al inventario en caso de rechazar la reserva
            while ($fila = $libros->fetch_assoc()) {
                $ID = $fila["libro_id"];
                $updateInventario = $mysql->efectuarConsulta("UPDATE libro set cantidad = cantidad + 1 WHERE libro.id = $ID");
            }
        }

        // Actualizar el estado de la reserva
        $cambiarEstado = $mysql->efectuarConsulta("UPDATE reserva SET estado ='$nuevoEstado' WHERE id = $IDreserva");

        if (!$cambiarEstado) {
            $errores = "Error al cambiar estado de la RESERVA";
        }

        $mysql->desconectar();

        if (count($errores) == 0) {
            echo json_encode([
                "success" => true,
                "message" => $mensaje
            ]);
            exit();
        } else {
            echo json_encode([
                "success" => true,
                "message" => "Ocurrio un error..."
            ]);
            exit();
        }
    }
}
