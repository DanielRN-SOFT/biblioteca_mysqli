<?php
function seleccionarDatos($IDtabla)
{
    //SE REQUIERE EL MODELO A USAR
    require_once '../models/MYSQL.php';
    //ESTA ES LA INSTANCIA DE LA CLASE 
    $mysql=new MySQL();
//SE CONECTA A LA BASE DE DATOS
    $mysql->conectar();
    //AQUI SE REALIZA UNA CONSULTA PARA OBTENER LOS DATOS DE LA TABLA
    $consulta=$mysql->efectuarConsulta("SELECT * from libro where id=$IDtabla");
    $editarDatos=$consulta->fetch_assoc();

    //los datos JSON se envian al JS
    echo json_encode($editarDatos);
    $mysql->desconectar();


}
if($_SERVER["REQUEST_METHOD"]=="POST"){
    if(isset($_POST["IDlibro"]) && !empty($_POST["IDlibro"])){
        $id=$_POST["IDlibro"];

    seleccionarDatos($id);
    }
}
?>