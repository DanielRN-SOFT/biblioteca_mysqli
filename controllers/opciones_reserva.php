<?php

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (
        isset($_POST["IDreserva"]) && !empty($_POST["IDreserva"])
    ) {
        // ENVIAR EMAIL
        require_once './phpMailer.php';
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
            // Asignacion de variables
            $nuevoEstado = "Aprobada";
            $mensaje = "Aprobacion de reserva completada";
            $IDusuario = filter_var($_POST["IDusuario"], FILTER_SANITIZE_NUMBER_INT);

            // Seleccionar los datos del usuario para el envio del email
            $consultaUsuario = $mysql->efectuarConsulta("SELECT id, nombre, apellido, email FROM usuario WHERE id = $IDusuario");
            $datosUsuario = $consultaUsuario->fetch_assoc();

            // Fechas de prestamo
            $consultaFechas = $mysql->efectuarConsulta("SELECT NOW() as hoy, DATE_ADD(NOW(), INTERVAL 5 DAY) as diasPosteriores");
            $fechas = $consultaFechas->fetch_assoc();


            // Insertar en prestamo la reserva aprobada
            $insertPrestamo = $mysql->efectuarConsulta("INSERT INTO prestamo(id_reserva,fecha_prestamo,fecha_devolucion, estado) VALUES($IDreserva, NOW(), DATE_ADD(NOW(), INTERVAL 5 DAY), 'Prestado')");

            // Verificar que no haya error en el insert de prestamos
            if (!$insertPrestamo) {
                $errores = "Error al insertar PRESTAMO";
            }

                enviarCorreo($datosUsuario["email"], $datosUsuario["nombre"], $datosUsuario["apellido"], $fechas["hoy"], $fechas["diasPosteriores"]);
            
        }

        // Si la opcion es rechazar
        if ($opcion == "Rechazar") {
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
                "success" => false,
                "message" => "Ocurrio un error..."
            ]);
            exit();
        }
    }
}
