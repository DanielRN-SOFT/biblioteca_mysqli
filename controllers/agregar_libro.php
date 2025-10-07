<?php
header('Content-Type: application/json');
//se verifica si los datos se han enviado via POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    //decision para verificar que se enviaron todos los datos correctamente
    if (
        isset($_POST["titulo"]) && !empty($_POST["titulo"]) &&
        isset($_POST["autor"]) && !empty($_POST["autor"]) &&
        isset($_POST["isbn"]) && !empty($_POST["isbn"]) &&
        isset($_POST["categoria"]) && !empty($_POST["categoria"]) &&
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
        $categoria = filter_var($_POST["categoria"], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $disponibilidad = filter_var($_POST["disponibilidad"], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $cantidad = filter_var($_POST["cantidad"], FILTER_SANITIZE_NUMBER_INT);

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

        $agregarLibro = $mysql->efectuarConsulta("INSERT INTO libro(titulo, autor, ISBN, categoria, disponibilidad, cantidad,estado) VALUES('$titulo', '$autor', '$isbn', '$categoria', '$disponibilidad', '$cantidad','Activo')");
        if ($agregarLibro) {
            echo json_encode([
                "success" => true,
                "message" => "Libro agregado exitosamente"
            ]);
        } else {
            echo json_encode([
                "success" => false,
                "message" => "Error al agregar el libro"
            ]);
        }
        $mysql->desconectar();
    }
}
