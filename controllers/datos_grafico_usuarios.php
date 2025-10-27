<?php

//====================
// Conexion a la BD
// ===================

require_once '../models/MYSQL.php';
$mysql = new MySQL();
$mysql->conectar();

$consultaTotalPrestamos = $mysql->efectuarConsulta("SELECT COUNT(*) as conteo FROM prestamo");
$conteoTotalPrestamos = $consultaTotalPrestamos->fetch_assoc()["conteo"];

$consultaPrestamosUsuario = $mysql->efectuarConsulta("SELECT CONCAT(usuario.nombre, ' ', usuario.apellido) as nombre_completo, (COUNT(prestamo.id) / $conteoTotalPrestamos) * 100 as cantidad FROM usuario JOIN reserva ON reserva.id_usuario = usuario.id JOIN prestamo ON prestamo.id_reserva = reserva.id  GROUP BY nombre_completo ORDER BY cantidad DESC LIMIT 5; ");

$data = [];

while($fila = mysqli_fetch_assoc($consultaPrestamosUsuario)){
    $data[] = $fila;
}

header('Content-Type:application/json');
echo json_encode($data);

?>