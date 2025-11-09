<?php

// Funcion para seleccionar datos a editar sin necesidad de crear tantos archivos
function seleccionarDatosEditar($IDtabla, $tabla)
{
    // Requerir el modelo a utilizar
    require_once '../models/MYSQL.php';

    // Instancia de la clase
    $mysql = new MySQL();

    // Conexion a la BD
    $mysql->conectar();

    // Realizar la consulta para obtener los datos de la tabla
    $consulta = $mysql->efectuarConsulta("SELECT * FROM $tabla WHERE id = $IDtabla");
    $datosEditar = $consulta->fetch_assoc();

    // Enviar los datos via JSON para utilizarlos en JavaScript
    echo json_encode($datosEditar);

    $mysql->desconectar();
}


// Determinar si se envio el formulario por POST en USUARIO
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["IDusuario"]) && !empty($_POST["IDusuario"])) {
        // Capturar el ID
        $id = $_POST["IDusuario"];
        
        // Llamar a la funcion de seleccinar
        seleccionarDatosEditar($id, "usuario");
    }
}

// Determinar si se envio el formulario por POST en LIBRO
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["IDlibro"]) && !empty($_POST["IDlibro"])) {
        // Requerir el modelo a utilizar
        require_once '../models/MYSQL.php';

        // Instancia de la clase
        $mysql = new MySQL();

        // Conexion a la BD
        $mysql->conectar();

        // Capturar ID
        $IDlibro = $_POST["IDlibro"];

        // Realizar la consulta para obtener los datos de la tabla
        $consulta = $mysql->efectuarConsulta("SELECT * FROM libro WHERE id = $IDlibro");
        $datosEditar = $consulta->fetch_assoc();

        //Listar las categorias del libro seleccionado
        $consultaCategoriasSelect = $mysql->efectuarConsulta("SELECT id, nombre_categoria FROM categoria
        JOIN categoria_has_libro ON categoria.id = categoria_id WHERE libro_id = $IDlibro");

        $categoriasSelect = [];

        while($fila = $consultaCategoriasSelect->fetch_assoc()){
            $categoriasSelect[] = $fila;
        }

        //Listar las categorias generales
        $consultaCategorias = $mysql->efectuarConsulta("SELECT id, nombre_categoria FROM categoria");

        $categorias = [];

        while ($fila = $consultaCategorias->fetch_assoc()) {
            $categorias[] = $fila;
        }
        

        // Enviar los datos via JSON para utilizarlos en JavaScript
        echo json_encode([
            "datosLibro" => $datosEditar,
            "categoriasSelect" => $categoriasSelect,
            "categorias" => $categorias
        ]);

        $mysql->desconectar();
    }
}
