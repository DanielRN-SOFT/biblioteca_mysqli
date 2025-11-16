<?php
// Determinar si se envio el formulario por POST en LIBRO
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Requerir el modelo a utilizar
    require_once '../models/MYSQL.php';

    // Instancia de la clase
    $mysql = new MySQL();

    // Conexion a la BD
    $mysql->conectar();

    //Listar las categorias generales
    $consultaCategorias = $mysql->efectuarConsulta("SELECT id, nombre_categoria FROM categoria WHERE estado = 'Activo'");

    $categorias = [];

    while ($fila = $consultaCategorias->fetch_assoc()) {
        $categorias[] = $fila;
    }


    // Enviar los datos via JSON para utilizarlos en JavaScript
    echo json_encode([
        "categorias" => $categorias
    ]);

    $mysql->desconectar();
}
