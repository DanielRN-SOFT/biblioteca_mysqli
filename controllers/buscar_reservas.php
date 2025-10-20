<?php
require_once '../models/MYSQL.php';
$mysql= new MySQL();
$mysql-> conectar();
$query= $_POST['query'];
$result=$mysql->efectuarConsulta("SELECT id,id_usuario,fecha_reserva,estado FROM reserva WHERE id LIKE '%$query%' OR id_usuario LIKE '%$query%' OR fecha_reserva LIKE '%$query%' OR estado LIKE '%$query%' LIMIT 10");
$reservas=[];
while($row = mysqli_fetch_assoc($result)){
    $reservas[]=$row;
}
echo json_encode($reservas);
$mysql->desconectar();
?>