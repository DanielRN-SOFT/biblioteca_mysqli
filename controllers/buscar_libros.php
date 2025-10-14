<?php
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