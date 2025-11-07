<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../models/MYSQL.php';

$mysql = new MySQL();
$mysql->conectar();

$IDusuario = $_SESSION["IDusuario"];
$mostrarTodos = isset($_GET["all"]) && $_GET["all"] === "true";

// Total de vencidos
$consultaTotal = "
    SELECT COUNT(*) AS total
    FROM prestamo
    INNER JOIN reserva ON prestamo.id_reserva = reserva.id
    INNER JOIN usuario ON reserva.id_usuario = usuario.id
    WHERE prestamo.estado = 'Vencido'
      AND usuario.id = $IDusuario
      AND DATE(prestamo.fecha_devolucion) < NOW()
";
$totalRes = $mysql->efectuarConsulta($consultaTotal);
$total = $totalRes->fetch_assoc()["total"];

// Si se pide todo, no aplicamos el LIMIT
$limit = $mostrarTodos ? "" : "LIMIT 5";

$consultaVencidos = "
    SELECT DISTINCT libro.titulo
    FROM prestamo
    INNER JOIN reserva ON prestamo.id_reserva = reserva.id
    INNER JOIN usuario ON reserva.id_usuario = usuario.id
    INNER JOIN reserva_has_libro ON reserva.id = reserva_has_libro.reserva_id
    INNER JOIN libro ON reserva_has_libro.libro_id = libro.id
    WHERE prestamo.estado = 'Vencido'
      AND usuario.id = $IDusuario
      AND DATE(prestamo.fecha_devolucion) < NOW()
    $limit
";

$resultado = $mysql->efectuarConsulta($consultaVencidos);
$librosVencidos = [];

while ($fila = $resultado->fetch_assoc()) {
    $librosVencidos[] = $fila['titulo'];
}

echo json_encode([
    "success" => $total > 0,
    "libros" => $librosVencidos,
    "total" => $total
]);
