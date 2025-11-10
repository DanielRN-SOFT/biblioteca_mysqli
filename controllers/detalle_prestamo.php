<?php

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["IDprestamo"]) && !empty($_POST["IDprestamo"])) {
        require_once '../models/MYSQL.php';

        // Conexion
        $mysql = new MySQL();
        $mysql->conectar();

        // Captura de datos
        $IDprestamo = $_POST["IDprestamo"];

        // Consulta
        $consultaDetalle = $mysql->efectuarConsulta("SELECT usuario.nombre, usuario.apellido, prestamo.id_reserva, libro.id, libro.titulo, libro.autor, libro.ISBN FROM libro JOIN reserva_has_libro ON reserva_has_libro.libro_id = libro.id JOIN reserva ON reserva.id = reserva_has_libro.reserva_id JOIN prestamo ON reserva.id = prestamo.id_reserva JOIN usuario ON reserva.id_usuario = usuario.id WHERE prestamo.id = $IDprestamo");

        // Arreglo de detalles
        $detalle = [];

        // Llenar el arreglo
        while ($fila = $consultaDetalle->fetch_assoc()) {
            $IDlibro = $fila["id"];
            $detalle[] = $fila;

            $consultaCategorias = $mysql->efectuarConsulta("SELECT libro_id , nombre_categoria FROM categoria 
        JOIN categoria_has_libro ON categoria.id = categoria_id WHERE libro_id = $IDlibro");


            while ($filaCategorias = $consultaCategorias->fetch_assoc()) {
                $categorias[] = $filaCategorias;
            }
        }

        header('Content-Type:application/json');
        // Enviar los datos en JSON
        echo json_encode(
            [
                'success' => true,
                'detalle' => $detalle,
                'categorias' => $categorias
            ]
        );

        $mysql->desconectar();
    } else {
        echo json_encode(['success' => false, 'message' => 'ID de reserva no válido']);
    }
}
