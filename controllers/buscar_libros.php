<?php 

// Requerir el modelo a utilizar
require_once '../models/MYSQL.php';
$mysql = new MySQL();
$mysql->conectar();

// Capturar lo ingresado por el usuario
$query = $_POST["query"];
// Consulta para seleccionar todos los libros
$consulta = $mysql->efectuarConsulta("SELECT id, titulo, autor, categoria FROM libro WHERE disponibilidad = 'Disponible' AND titulo LIKE '%$query%' OR disponibilidad = 'Disponible' AND autor LIKE '%$query%' LIMIT 10");

// Arreglo con todos los resultados de la consulta
$libros = [];

// Ciclo para llenar el arreglo
while($fila = mysqli_fetch_assoc($consulta)){
    $libros[] = $fila;
}

// Envio de la info en JSON
echo json_encode($libros);

require_once '../models/MYSQL.php';
$mysql= new MySQL();
$mysql-> conectar();
$query= $_POST['query'];
$result=$mysql->efectuarConsulta("SELECT titulo,autor,ISBN,categoria,disponibilidad,cantidad FROM libro WHERE titulo LIKE '%$query%' OR autor LIKE '%$query%' OR ISBN LIKE '%$query%' OR categoria LIKE '%$query%' OR disponibilidad LIKE '%$query%' LIMIT 10");
$libros=[];
while($row = mysqli_fetch_assoc($result)){
    $libros[]=$row;
}
echo json_encode($libros);
$mysql->desconectar();
?>