<?php
require_once '../models/MYSQL.php';
header('Content-Type: application/json; charset=utf-8');
$mysql= new MySQL();
$mysql-> conectar();
$query= $_POST['query'];
$result=$mysql->efectuarConsulta("SELECT id,id_reserva,fecha_prestamo,fecha_devolucion,estado FROM prestamo WHERE id LIKE '%$query%' OR id_reserva LIKE '%$query%' OR fecha_prestamo LIKE '%$query%' OR fecha_devolucion LIKE '%$query%' OR estado LIKE '%$query%' LIMIT 10");
$prestamos=[];
while($row = mysqli_fetch_assoc($result)){
    $prestamos[]=$row;
}
echo json_encode($prestamos);
$mysql->desconectar();
?>