<?php
//se verifica si los datos se han enviado via POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    //decision para verificar que se enviaron todos los datos correctamente
    if (
        isset($_POST["titulo"]) && !empty($_POST["titulo"]) &&
        isset($_POST["autor"]) && !empty($_POST["autor"]) &&
        isset($_POST["categoria"]) && !empty($_POST["categoria"]) &&
        isset($_POST["disponibilidad"]) && !empty($_POST["disponibilidad"]) &&
        isset($_POST["cantidad"]) && !empty($_POST["cantidad"])
    ) {
        require_once '../models/MYSQL.php';
        require_once '../controllers/validar_isbn.php';
        $id=$_POST["IDlibro"];
        $mysql = new MySQL();
        $mysql->conectar();
        //sanitizacion de los datos
        $titulo = filter_var($_POST["titulo"], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $autor = filter_var($_POST["autor"], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $categoria = filter_var($_POST["categoria"], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $disponibilidad = filter_var($_POST["disponibilidad"], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $cantidad = filter_var($_POST["cantidad"], FILTER_SANITIZE_NUMBER_INT);

       
       if ($cantidad < 0) {
            echo json_encode([
                "success" => false,
                "message" => "La cantidad debe tener numeros positivos"
            ]);
            exit();
        }

        $update = $mysql->efectuarConsulta("UPDATE libro SET titulo = '$titulo', autor = '$autor',categoria = '$categoria', disponibilidad = '$disponibilidad',cantidad = '$cantidad' WHERE id = $id");

        if ($update) {
            echo json_encode([
                "success" => true,
                "message" => "Libro Editado exitosamente"
            ]);
        } else {
            echo json_encode([
                "success" => false,
                "message" => "Error al editar el libro"
            ]);
        }
        $mysql->desconectar();
    }else{
        if (!filter_var($_POST["cantidad"], FILTER_VALIDATE_INT)) {
            echo json_encode([
                "success" => false,
                "message" => "Ingrese un valor valido en la cantidad"
            ]);
            exit();
        }
        echo json_encode([
            "success" => false,
            "message" => "Todos los campos son obligatorios"
        ]);
    }
}