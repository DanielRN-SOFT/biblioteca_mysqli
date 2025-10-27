<?php 

// Requerir el modelo a utilizar
require_once '../models/MYSQL.php';
$mysql = new MySQL();
$mysql->conectar();

// Consulta para seleccionar todos los libros para EDITAR reserva
$consulta = $mysql->efectuarConsulta("SELECT id, titulo, autor, categoria FROM libro WHERE disponibilidad = 'Disponible'");

$libros = [];

while($fila = mysqli_fetch_assoc($consulta)){
    $libros[] = $fila;
}

echo json_encode($libros);

$mysql->desconectar();
?>