<?php 

// Requerir el modelo a utilizar
require_once '../models/MYSQL.php';
$mysql = new MySQL();
$mysql->conectar();

// Capturar lo ingresado por el usuario
$query = $_POST["query"];
// Consulta para seleccionar todos los libros
$consulta = $mysql->efectuarConsulta("SELECT id, nombre_categoria, descripcion FROM categoria WHERE nombre_categoria LIKE '%$query%' LIMIT 10");

// Arreglo con todos los resultados de la consulta
$categorias = [];

// Ciclo para llenar el arreglo
while($fila = mysqli_fetch_assoc($consulta)){
    $categorias[] = $fila;
}

// Envio de la info en JSON
echo json_encode($categorias); 



?>