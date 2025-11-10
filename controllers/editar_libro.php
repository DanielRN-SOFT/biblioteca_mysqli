<?php
//se verifica si los datos se han enviado via POST

use Dom\Document;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    //decision para verificar que se enviaron todos los datos correctamente
    if (
        isset($_POST["titulo"]) && !empty($_POST["titulo"]) &&
        isset($_POST["autor"]) && !empty($_POST["autor"]) &&
        isset($_POST["cantidad"]) && $_POST["cantidad"] !== "" && is_numeric($_POST["cantidad"])
    ) {
        require_once '../models/MYSQL.php';
        require_once '../controllers/validar_isbn.php';
        $id = $_POST["IDlibro"];
        $id = $_POST["IDlibro"];
        $mysql = new MySQL();
        $mysql->conectar();
        $disponibilidad = isset($_POST["disponibilidad"]) ? $_POST["disponibilidad"] : "";
        //sanitizacion de los datos
        $titulo = filter_var(trim($_POST["titulo"]), FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $autor = filter_var(trim($_POST["autor"]), FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $disponibilidad = filter_var(trim($_POST["disponibilidad"]), FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $cantidad = filter_var($_POST["cantidad"], FILTER_SANITIZE_NUMBER_INT);

        // Obtener las categorias
        $categorias = json_decode($_POST["categorias"], true);
        $errores = [];


        if ($cantidad < 0) {
            echo json_encode([
                "success" => false,
                "message" => "La cantidad debe tener numeros positivos"
            ]);
            exit();
        }
        // Si la cantidad es 0 → disponibilidad automática a "No Disponible"
        if ($cantidad === 0) {
            $disponibilidad = "No Disponible";
        }

        

        // Eliminar las categorias de los libros
        $deleteCategorias = $mysql->efectuarConsulta("DELETE FROM categoria_has_libro WHERE libro_id = $id");

        foreach ($categorias as $IDcategoria) {
            $insertCategoria = $mysql->efectuarConsulta("INSERT INTO categoria_has_libro
            (categoria_id, libro_id) VALUES($IDcategoria, $id)");

            if (!$insertCategoria) {
                $errores = "Error en el insert de la categoria: " . $IDcategoria;
            }
        }

        $update = $mysql->efectuarConsulta("UPDATE libro SET titulo = '$titulo', autor = '$autor', disponibilidad = '$disponibilidad',cantidad = '$cantidad' WHERE id = $id");

            if (!$update) {
            $errores = "Error en el UPDATE";
        }

        if (count($errores) == 0) {
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
    } else {
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

