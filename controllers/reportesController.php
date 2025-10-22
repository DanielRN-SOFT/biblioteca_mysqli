<?php
// controllers/EmpleadoController.php
require_once __DIR__ . '/../config/conexion.php';
class reportesController {
public function reportesPrestamos() {
$conexion = Conexion::conectar();
$consulta = "SELECT id,id_reserva,fecha_prestamo,fecha_devolucion,estado FROM prestamo";
$resultado = $conexion->query($consulta);
$prestamos = [];

while ($fila = $resultado->fetch_assoc()) {
$prestamos[] = $fila;
}

return $prestamos;
}
}