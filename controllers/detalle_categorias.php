<?php

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["IDlibro"]) && !empty($_POST["IDlibro"])) {
        require_once '../models/MYSQL.php';

        // Conexion
        $mysql = new MySQL();
        $mysql->conectar();

        // Captura de datos
        $IDlibro = $_POST["IDlibro"];

        // Consulta
        $consultaDetalle = $mysql->efectuarConsulta("SELECT nombre_categoria FROM categoria JOIN categoria_has_libro ON categoria.id = categoria_id WHERE libro_id = $IDlibro");

        // Arreglo de categorias
        $categorias = [];

        // Llenar el arreglo
        while ($fila = $consultaDetalle->fetch_assoc()) {
            $categorias[] = $fila;
        }

        header('Content-Type:application/json');
        // Enviar los datos en JSON
        echo json_encode(
            [
                'success' => true,
                'detalle' => $categorias
            ]
        );

        $mysql->desconectar();
    } else {
        echo json_encode(['success' => false, 'message' => 'ID de categoria no válido']);
    }
}
