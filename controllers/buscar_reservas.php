<?php
require_once '../models/MYSQL.php';
header('Content-Type: application/json');
$mysql= new MySQL();
$mysql-> conectar();
$query= trim($_POST['query']);
$result=$mysql->efectuarConsulta("SELECT r.id,u.nombre,u.apellido,r.fecha_reserva,l.titulo,r.estado FROM reserva r INNER JOIN usuario u ON u.id=r.id_usuario INNER JOIN reserva_has_libro rh ON r.id = rh.reserva_id INNER JOIN libro l ON rh.libro_id = l.id  WHERE r.id LIKE '%$query%' OR u.nombre LIKE '%$query%' OR u.apellido LIKE '%$query%' OR r.fecha_reserva LIKE '%$query%' OR l.titulo LIKE '%$query%' OR r.estado LIKE '%$query%' LIMIT 10");
$reservas=[];
while($row = mysqli_fetch_assoc($result)){
    $reservas[]=$row;
}
echo json_encode($reservas);
$mysql->desconectar();
?>