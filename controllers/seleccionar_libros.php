<?php 

// Requerir el modelo a utilizar
require_once '../models/MYSQL.php';
$mysql = new MySQL();
$mysql->conectar();

// Consulta para seleccionar todos los libros
$consulta = $mysql->efectuarConsulta("SELECT id, titulo FROM libro WHERE disponibilidad = 'Disponible' GROUP BY id");

$data = [];

while($fila = $consulta->fetch_assoc()){
    $data[] = $fila;
}

echo json_encode($data);
header("Content-Type: application/json");

$mysql->desconectar();



?>