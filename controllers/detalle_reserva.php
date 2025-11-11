<?php

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["IDreserva"]) && !empty($_POST["IDreserva"])) {
        require_once '../models/MYSQL.php';

        // Conexion
        $mysql = new MySQL();
        $mysql->conectar();

        // Captura de datos
        $IDreserva = $_POST["IDreserva"];

        // Consulta
        $consultaDetalle = $mysql->efectuarConsulta("SELECT libro.id, libro.titulo, libro.autor, libro.ISBN FROM libro JOIN reserva_has_libro ON reserva_has_libro.libro_id = libro.id WHERE reserva_has_libro.reserva_id = $IDreserva ");

        // Arreglo de detalles
        $detalle = [];
        $categorias = [];

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
