<?php
// config/conexion.php

// Se centraliza la conexión a la base de datos
class Conexion {
public static function conectar() {
$conn = new mysqli("bts4vahkrnelqbarqbkj-mysql.services.clever-cloud.com", "ueidhxxqptq4fxou", "UxxjUzE9KHlkkZBAupKi", "bts4vahkrnelqbarqbkj");
if ($conn->connect_error) {
die("Error de conexión: " . $conn->connect_error);
}
return $conn;
}
}