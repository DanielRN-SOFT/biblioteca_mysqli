<?php
// Requirir el modelo a utilizar
require_once '../models/MYSQL.php';
header('Content-Type: application/json; charset=utf-8');

// Conexion a la base de datos
$mysql = new MySQL();
$mysql->conectar();


// Captura de las variables
$IDprestamo = $_POST['IDprestamo'];
$estado = filter_var($_POST["estado"], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$IDreserva = $_POST["IDreserva"];
$errores = [];

if ($estado == "Prestado") {
    $nuevoEstado = "Devuelto";
    $mensaje = "Registro de prestamo exitoso";

    // Agregar de nuevo los libros al inventario
    $consultaLibros = $mysql->efectuarConsulta("SELECT libro_id FROM reserva_has_libro WHERE reserva_id = $IDreserva");

    while ($fila = $consultaLibros->fetch_assoc()) {

        $IDlibro = $fila["libro_id"];

        // Añadir de nuevo el libro al inventario
        $updateInventario = $mysql->efectuarConsulta("UPDATE libro SET cantidad = cantidad + 1 
        WHERE id = $IDlibro");

        if (!$updateInventario) {
            $errores = "Error al agregar cantidad en el libro: $IDlibro";
        }
    }

    // Cambiar el estado del prestamo
    $update = $mysql->efectuarConsulta("UPDATE prestamo SET estado= '$nuevoEstado' WHERE id= $IDprestamo");

    if (!$update) {
        $errores = "Error en el cambio de estado";
    }
}

if($estado == "Devuelto"){
    $nuevoEstado = "Prestado";
    $mensaje = "Renovacion de prestamo completada";

    // Captura de la fecha
    $fechaDevolucion = filter_var($_POST["fechaDevolucion"], FILTER_SANITIZE_FULL_SPECIAL_CHARS);

    // Zona horaria
    date_default_timezone_set('America/Bogota');
    // Hora actual
    $hora = date('H:i:s');
    // Fecha completa
    $fechaCompleta = $fechaDevolucion . ' ' . $hora; // YYYY-MM-DD HH:MM:SS

    // Agregar de nuevo los libros al inventario
    $consultaLibros = $mysql->efectuarConsulta("SELECT libro_id, titulo, cantidad FROM reserva_has_libro JOIN libro ON libro.id = reserva_has_libro.libro_id WHERE reserva_id = $IDreserva");

    while ($fila = $consultaLibros->fetch_assoc()) {

        $IDlibro = $fila["libro_id"];
        $titulo = $fila["titulo"];
        $cantidad = $fila["cantidad"];


        if($cantidad <= 0){
            echo json_encode([
                "success" => false,
                "message" => "No hay existencias para el libro: $titulo, intentelo con otro prestamo"
            ]);
            exit();
        }

        // Añadir de nuevo el libro al inventario
        $updateInventario = $mysql->efectuarConsulta("UPDATE libro SET cantidad = cantidad - 1 
        WHERE id = $IDlibro");

        if (!$updateInventario) {
            $errores = "Error al agregar cantidad en el libro: $IDlibro";
        }
    }

    // Cambiar el estado del prestamo
    $update = $mysql->efectuarConsulta("UPDATE prestamo SET estado= '$nuevoEstado', fecha_devolucion = '$fechaCompleta' WHERE id= $IDprestamo");

    if (!$update) {
        $errores = "Error en el cambio de estado";
    }
}




if (count($errores) == 0) {
    echo json_encode([
        "success" => true,
        "message" => $mensaje
    ]);
} else {
    echo json_encode([
        "success" => false,
        "message" => $mensaje
    ]);
}

    $mysql->desconectar();
