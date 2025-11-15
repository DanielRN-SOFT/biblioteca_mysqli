<?php 

// Requerir el modelo a utilizar
require_once '../models/MYSQL.php';
$mysql = new MySQL();
$mysql->conectar();

// Capturar lo ingresado por el usuario
$query = filter_var($_POST["query"], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
// Consulta para seleccionar todos los libros
$consulta = $mysql->efectuarConsulta("SELECT id, nombre_categoria FROM categoria WHERE nombre_categoria LIKE '%$query%' AND estado = 'Activo' LIMIT 10");

// Arreglo con todos los resultados de la consulta
$categorias = [];

// Ciclo para llenar el arreglo
while($fila = mysqli_fetch_assoc($consulta)){
    $categorias[] = $fila;
}

// Envio de la info en JSON
echo json_encode($categorias); 



?>