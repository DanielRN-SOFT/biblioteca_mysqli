<?php
require_once '../models/MYSQL.php';
header('Content-Type: application/json; charset=utf-8');
$mysql= new MySQL();
$mysql-> conectar();
$id= $_POST['idPrestamo'];
$update= $mysql->efectuarConsulta("UPDATE prestamo SET estado='Cancelado' WHERE id= $id");
if($update){
    echo json_encode([
        "success" => true,
        "message" => "Préstamo actualizado exitosamente"
    ]);
} else {
    echo json_encode([
        "success" => false,
        "message" => "Error al actualizar el préstamo"
    ]);
}
$mysql->desconectar();
?>