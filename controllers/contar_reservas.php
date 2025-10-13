<?php
// =====================
// Conexion a la base de datos
//======================
require_once '../models/MYSQL.php';
$mysql = new MySQL();
$mysql->conectar();

$IDcliente = $_POST["IDcliente"];
$librosSeleccionados = json_decode($_POST["librosSeleccionados"]);

$consulta = $mysql->efectuarConsulta("SELECT COUNT(*) as numReservas FROM reserva_has_libro JOIN reserva ON reserva.id = reserva_has_libro.reserva_id WHERE reserva.id_usuario = $IDcliente AND reserva.estado = 'Pendiente'");

$conteoReservas = $consulta->fetch_assoc()["numReservas"];
$conteoReservas =  $conteoReservas + count($librosSeleccionados);

if($conteoReservas >= 3){
    echo json_encode([
        "success" => false,
        "message" => "Ha superado el numero de limites de reservas pendientes (maximo 3)"
    ]);
    exit();
}

echo json_encode([
    "success" => true,
    "message" => "Puede continuar con la reserva"
]);


$mysql->desconectar();


?>