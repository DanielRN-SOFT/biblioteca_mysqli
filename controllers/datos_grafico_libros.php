<?php

//====================
// Conexion a la BD
// ===================

require_once '../models/MYSQL.php';
$mysql = new MySQL();
$mysql->conectar();


$consultaLibros = $mysql->efectuarConsulta("SELECT libro.titulo, COUNT(reserva_has_libro.libro_id) AS cantidad FROM reserva_has_libro JOIN libro ON libro.id = reserva_has_libro.libro_id GROUP BY reserva_has_libro.libro_id  
ORDER BY cantidad DESC LIMIT 5;");

$data = [];

while($fila = mysqli_fetch_assoc($consultaLibros)){
    $data[] = $fila;
}

header('Content-Type:application/json');
echo json_encode($data);

?>