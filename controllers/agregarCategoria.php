<?php
header('Content-Type: application/json');
//se verifica si los datos se han enviado via POST
if ($_SERVER["REQUEST_METHOD"] == "POST"){
   //decision para verificar que se enviaron todos los datos correctamente
    if (
        isset($_POST["categoria"]) && !empty($_POST["categoria"])
    ){
        require_once '../models/MYSQL.php';
        $mysql = new MySQL();
        $mysql->conectar();
        //sanitizacion de los datos
        $categoria = filter_var($_POST["categoria"],FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        //validar que no se repita la categoria
        $categoriaRepetida=$mysql->efectuarConsulta("SELECT 1 FROM categoria WHERE nombre_categoria='$categoria'");
        if(mysqli_num_rows($categoriaRepetida)>0){
            echo json_encode([
                "success"=> false,
                "message"=> "La categoria ya se encuentra registrada"
            ]);
            exit();
        }
        $agregarCategoria=$mysql->efectuarConsulta("INSERT INTO categoria(nombre_categoria,estado)VALUES('$categoria','Activo')");
        if($agregarCategoria){
            echo json_encode([
                "success"=>true,
                "message"=>"Categoria agregada correctamente"
            ]);
        }else{
            echo json_encode([
                "succes"=>false,
                "message"=>"Error al agregar"
            ]);
        }
        $mysql->desconectar();
    } 
}


?>