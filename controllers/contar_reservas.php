<?php
// =====================
// Conexion a la base de datos
//======================
require_once '../models/MYSQL.php';
$mysql = new MySQL();
$mysql->conectar();

// Capturar el ID del cliente
$IDcliente = $_POST["IDcliente"];

// Capturar el arreglo de libros
$librosSeleccionados = json_decode($_POST["librosSeleccionados"]);

// Ejecutar la consulta
$consulta = $mysql->efectuarConsulta("SELECT COUNT(*) as numReservas FROM reserva_has_libro JOIN reserva ON reserva.id = reserva_has_libro.reserva_id WHERE reserva.id_usuario = $IDcliente AND reserva.estado = 'Pendiente'");

// Conteo de las reservas en la BD
$conteoReservasBD = $consulta->fetch_assoc()["numReservas"];
// Conteo de los libros seleccionados en el form
$conteoReservas =  $conteoReservasBD + count($librosSeleccionados);

// Verificar que no exceda el limite de 3 reservas x usuario
if($conteoReservas > 3){
    echo json_encode([
        "success" => false,
        "message" => "Ha superado el limite de libros en reservas pendientes (maximo 3)"
    ]);
    exit();
}

// Devolver true si pasa la validacion
echo json_encode([
    "success" => true,
    "message" => "Puede continuar con la reserva"
]);

// Desconexion de la base de datos
$mysql->desconectar();


?>