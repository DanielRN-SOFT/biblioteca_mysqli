<?php
require_once '../models/MYSQL.php';
header('Content-Type: application/json; charset=utf-8');
session_start();
$IDusuario = $_SESSION["IDusuario"];
$tipoUsuario = $_SESSION["tipoUsuario"];
$mysql= new MySQL();
$mysql-> conectar();
$query= $_POST['query'];
if($tipoUsuario=="Cliente"){
$result=$mysql->efectuarConsulta("SELECT p.id, p.id_reserva, p.fecha_prestamo, p.fecha_devolucion, p.estado FROM prestamo p INNER JOIN reserva r ON p.id_reserva = r.id INNER JOIN usuario u ON r.id_usuario = u.id WHERE (p.id LIKE '%$query%' OR p.id_reserva LIKE '%$query%' OR p.fecha_prestamo LIKE '%$query%' OR p.fecha_devolucion LIKE '%$query%' OR p.estado LIKE '%$query%') AND u.id = $IDusuario LIMIT 10");
}
if($tipoUsuario=="Administrador"){
    $result=$mysql->efectuarConsulta("SELECT id,id_reserva,fecha_prestamo,fecha_devolucion,estado FROM prestamo WHERE id LIKE '%$query%' OR id_reserva LIKE '%$query%' OR fecha_prestamo LIKE '%$query%' OR fecha_devolucion LIKE '%$query%' OR estado LIKE '%$query%' LIMIT 10");
}
$prestamos=[];
while($row = mysqli_fetch_assoc($result)){
    $prestamos[]=$row;
}
echo json_encode($prestamos);
$mysql->desconectar();
?>