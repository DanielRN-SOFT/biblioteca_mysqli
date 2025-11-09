<?php

// Verificar el metodo de envio
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["libros"]) && !empty($_POST["libros"])) {

        // ==============================
        // Conexion a la base de datos
        // ==============================

        require_once '../models/MYSQL.php';
        $mysql = new MySQL();
        $mysql->conectar();

        // Capturar datos
        $id = $_POST["IDcliente"];
        $fechaAsistencia = $_POST["fechaAsistencia"];
        $estado = "Pendiente";

        // Insertar la reserva
        $insertReserva = $mysql->efectuarConsulta("INSERT INTO reserva(id_usuario, fecha_reserva, fecha_asistencia, estado) VALUES($id, NOW(), '$fechaAsistencia', '$estado')");


        if (!$insertReserva) {
            echo json_encode(['success' => false, 'message' => 'Error al crear la reserva']);
            $mysql->desconectar();
            exit;
        }

        // Seleccionar el ultimoID existente en reservas
        $consultaUltimoID = $mysql->efectuarConsulta("SELECT MAX(id) as IDmax FROM reserva");
        $rowID = mysqli_fetch_assoc($consultaUltimoID);
        $ultimoID = $rowID["IDmax"];

        // Arreglo de errores
        $errores = [];

        // Capturar el arreglo de libros
        $libros = json_decode($_POST["libros"], true);

        // Insertar cada libro en la tabla pivote
        foreach ($libros as $libro) {
            // Sanetizar los datos
            $IDlibro = filter_var($libro, FILTER_SANITIZE_NUMBER_INT);
            $queryPivote = "INSERT INTO reserva_has_libro(reserva_id,libro_id) VALUES($ultimoID, $IDlibro)";


            if (!$mysql->efectuarConsulta($queryPivote)) {
                $errores = "Error con el libro ID $IDlibro";
            }

            // Descontar del inventario
            $updateInventario = $mysql->efectuarConsulta("UPDATE libro set cantidad = cantidad - 1 WHERE libro.id = $IDlibro");
            

            if (!$updateInventario) {
                $errores =  "Error en UPDATE de inventario";
            }
        }

        // Desconexion de la base de datos
        $mysql->desconectar();

        // Si no hay ningun error reserva AGREGADA
        if (count($errores) == 0) {
            echo json_encode([
                "success" => true,
                "message" => "Reserva agregada exitosamente"
            ]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Algunas reservas no se registraron correctamente', 'errores' => $errores]);
        }
    } else {
        echo json_encode([
            "success" => false,
            "message" => "Seleccione por lo menos un libro"
        ]);
    }
}
