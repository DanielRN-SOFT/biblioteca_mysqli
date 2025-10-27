<?php

session_start();
$IDusuario = $_SESSION["IDusuario"];

//====================
// Conexion a la BD
// ===================
require_once '../models/MYSQL.php';
$mysql = new MySQL();
$mysql->conectar();


// Consulta a ejecutar
$consultaLibros = $mysql->efectuarConsulta("SELECT libro.titulo, COUNT(reserva_has_libro.libro_id) AS cantidad FROM reserva_has_libro JOIN libro ON libro.id = reserva_has_libro.libro_id JOIN reserva ON reserva.id = reserva_has_libro.reserva_id WHERE reserva.id_usuario = $IDusuario AND reserva.estado = 'Aprobada' GROUP BY reserva_has_libro.libro_id  
ORDER BY cantidad DESC LIMIT 5;");

$data = [];

// Llenado del arreglo
while ($fila = mysqli_fetch_assoc($consultaLibros)) {
    $data[] = $fila;
}

// Envio de la informacion
header('Content-Type:application/json');
echo json_encode($data);
