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

        // ID del cliente
        $id = $_POST["IDcliente"];
        $estado = "Pendiente";

        // Insertar la reserva
        $insertReserva = $mysql->efectuarConsulta("INSERT INTO reserva(id_usuario, fecha_reserva, estado) VALUES($id, now(), '$estado')");


        if (!$insertReserva) {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Error al crear la reserva']);
            $mysql->desconectar();
            exit;
        }

        // Seleccionar el ultimoID existente en reservas
        $consultaUltimoID = $mysql->efectuarConsulta("SELECT MAX(id) as IDmax FROM reserva");
        $rowID = mysqli_fetch_assoc($consultaUltimoID);
        $ultimoID = $rowID["IDmax"];


        $errores = [];

        // $ultimoID = $mysql->ultimoID();

        // Capturar el arreglo de libros
        $libros = json_decode($_POST["libros"], true);

        // Insertar cada producto en la tabla pivote
        foreach ($libros as $libro) {
            // Sanetizar los datos
            $IDlibro = $libro;
           

            $queryPivote = "INSERT INTO reserva_has_libro(reserva_id,libro_id) VALUES($ultimoID, $IDlibro)";

            if (!$mysql->efectuarConsulta($queryPivote)) {
                $errores = "Error con el libro ID $IDlibro";
            }

            // // Insertar en la tabla pivote
            // $insertPivote = $mysql->efectuarConsulta("INSERT INTO reserva_has_libro(reserva_id,libro_id, estado) VALUES($ultimoID, $libro, '$estado')");
        }

        $mysql->desconectar();
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
