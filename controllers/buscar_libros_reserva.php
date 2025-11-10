<?php

// Requerir el modelo a utilizar
require_once '../models/MYSQL.php';
$mysql = new MySQL();
$mysql->conectar();

// Capturar lo ingresado por el usuario
$query = $_POST["query"];
// Consulta para seleccionar todos los libros
$consulta = $mysql->efectuarConsulta("SELECT id, titulo, autor FROM libro WHERE disponibilidad = 'Disponible' AND titulo LIKE '%$query%' AND estado = 'Activo' OR disponibilidad = 'Disponible' AND autor LIKE '%$query%' AND estado = 'Activo' LIMIT 10");

// Arreglo con todos los resultados de la consulta
$libros = [];
$categorias = [];

// Ciclo para llenar el arreglo
while ($fila = mysqli_fetch_assoc($consulta)) {
    $IDlibro = $fila["id"];
    $libros[] = $fila;

    $consultaCategorias = $mysql->efectuarConsulta("SELECT libro_id , nombre_categoria FROM categoria 
        JOIN categoria_has_libro ON categoria.id = categoria_id WHERE libro_id = $IDlibro");


    while ($fila = $consultaCategorias->fetch_assoc()) {
        $categorias[] = $fila;
    }
}

// Envio de la info en JSON
echo json_encode([
    "libros" => $libros,
    "categorias" => $categorias
]);
