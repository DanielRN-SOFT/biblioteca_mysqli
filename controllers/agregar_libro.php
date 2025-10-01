<?php
//se verifica si los datos se han enviado via POST
if($_SERVER["REQUEST_METHOD"] == "POST"){
    //decision para verificar que se enviaron todos los datos correctamente
    if(
        isset($_POST["titulo"]) && !empty($_POST["titulo"]) &&
        isset($_POST["autor"]) && !empty($_POST["autor"]) &&
        isset($_POST["isbn"]) && !empty($_POST["isbn"]) &&
        isset($_POST["categoria"]) && !empty($_POST["categoria"]) &&
        isset($_POST["disponibilidad"]) && !empty($_POST["disponibilidad"]) &&
        isset($_POST["cantidad"]) && !empty($_POST["cantidad"])
    ){
        require_once '../models/MYSQL.php';
        $mysql = new MYSQL();
        $mysql->conectar();
        //sanitizacion de los datos
        $titulo= filter_var($_POST["titulo"], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $autor= filter_var($_POST["autor"], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $isbn= filter_var($_POST["isbn"], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $categoria= filter_var($_POST["categoria"], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $disponibilidad= filter_var($_POST["disponibilidad"], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $cantidad= filter_var($_POST["cantidad"], FILTER_SANITIZE_NUMBER_INT);
        $agregarLibro= $mysql->efectuarConsulta("INSERT INTO libro(titulo, autor, ISBN, categoria, disponibilidad, cantidad) VALUES('$titulo', '$autor', '$isbn', '$categoria', '$disponibilidad', $cantidad)");
        if($agregarLibro){
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
    }
}


?>