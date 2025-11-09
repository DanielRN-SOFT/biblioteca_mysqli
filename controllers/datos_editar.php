<?php
// Determinar si se envio el formulario por POST en USUARIO
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["IDusuario"]) && !empty($_POST["IDusuario"])) {
        // Capturar el ID
        $id = $_POST["IDusuario"];

        // Requerir el modelo a utilizar
        require_once '../models/MYSQL.php';

        // Instancia de la clase
        $mysql = new MySQL();

        // Conexion a la BD
        $mysql->conectar();

        // Realizar la consulta para obtener los datos de la tabla
        $consulta = $mysql->efectuarConsulta("SELECT * FROM usuario WHERE id = $id");
        $datosEditar = $consulta->fetch_assoc();

        // Enviar los datos via JSON para utilizarlos en JavaScript
        echo json_encode($datosEditar);

        $mysql->desconectar();
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
        $consultaCategoriasSeleccionadas = $mysql->efectuarConsulta("SELECT id, nombre_categoria FROM categoria
        JOIN categoria_has_libro ON categoria.id = categoria_id WHERE libro_id = $IDlibro");

        $categoriasSeleccionadas = [];

        while($fila = $consultaCategoriasSeleccionadas->fetch_assoc()){
            $categoriasSeleccionadas[] = $fila;
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
            "categoriasSeleccionadas" => $categoriasSeleccionadas,
            "categorias" => $categorias
        ]);

        $mysql->desconectar();
    }
}
