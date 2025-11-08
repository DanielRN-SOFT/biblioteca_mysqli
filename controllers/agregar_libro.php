<?php
header('Content-Type: application/json');
//se verifica si los datos se han enviado via POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    //decision para verificar que se enviaron todos los datos correctamente
    if (
        isset($_POST["titulo"]) && !empty($_POST["titulo"]) &&
        isset($_POST["autor"]) && !empty($_POST["autor"]) &&
        isset($_POST["isbn"]) && !empty($_POST["isbn"]) &&
        isset($_POST["disponibilidad"]) && !empty($_POST["disponibilidad"]) &&
        isset($_POST["cantidad"]) && is_numeric($_POST["cantidad"])
    ) {
        require_once '../models/MYSQL.php';
        require_once 'validar_isbn.php';
        $mysql = new MySQL();
        $mysql->conectar();
        //sanitizacion de los datos
        $titulo = filter_var($_POST["titulo"], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $autor = filter_var($_POST["autor"], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $isbn = filter_var($_POST["isbn"], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $disponibilidad = filter_var($_POST["disponibilidad"], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $cantidad = filter_var($_POST["cantidad"], FILTER_SANITIZE_NUMBER_INT);
        // Capturar el arreglo de categorias
        $categorias = json_decode($_POST["categorias"], true);
        $errores = [];
        //validar el ISBN
        if (!validarISBN($isbn)) {
            echo json_encode([
                "success" => false,
                "message" => "ISBN Invalido"
            ]);
            exit();
        }
        //validamos que el ISBN no se repita
        $ISBNrepetido = $mysql->efectuarConsulta("SELECT 1 from libro where ISBN='$isbn'");
        if (mysqli_num_rows($ISBNrepetido) > 0) {
            echo json_encode([
                "success" => false,
                "message" => "Error el ISBN ya se encuentra registrado"
            ]);
            exit();
        }
        if ($cantidad < 0) {
            echo json_encode([
                "success" => false,
                "message" => "La cantidad debe tener numeros positivos"
            ]);
            exit();
        }

        $agregarLibro = $mysql->efectuarConsulta("INSERT INTO libro(titulo, autor, ISBN, disponibilidad, cantidad,estado,fecha_creacion) VALUES('$titulo', '$autor', '$isbn', '$disponibilidad', '$cantidad','Activo',NOW())");
        $consultaID = $mysql->efectuarConsulta("SELECT id FROM libro WHERE ISBN = '$isbn'");
        $ultimoID = $consultaID->fetch_assoc()["id"];

        if(!$agregarLibro){
            $errores = "Error en el insert del LIBRO";
        }

        foreach($categorias as $IDcategoria){
            $insertCategoria = $mysql->efectuarConsulta("INSERT INTO categoria_has_libro
            (categoria_id, libro_id) VALUES($IDcategoria, $ultimoID)");

            if(!$insertCategoria){
                $errores = "Error en el insert de la categoria: " . $IDcategoria;
            }
        }

        if(count($errores) == 0){
            echo json_encode([
                "success" => true,
                "message" => "Libro agregado exitosamente"
            ]);
        }else{
            echo json_encode([
                "success" => false,
                "message" => "Error al agregar el libro"
            ]);
        }

        $mysql->desconectar();
    }else{
        if(!filter_var($_POST["cantidad"], FILTER_VALIDATE_INT)){
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
