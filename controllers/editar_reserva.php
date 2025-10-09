<?php

// Metodo a utilizar
if($_SERVER["REQUEST_METHOD"] == "POST"){
    if(isset($_POST["libro"]) && !empty($_POST["libro"])){
        try{
            // =====================
            // Conexion a la base de datos
            // =====================

            require_once '../models/MYSQL.php';
            $mysql = new MySQL();
            $mysql->conectar();

            // Sanetizacion de datos
            $IDlibroNuevo = filter_var($_POST["libro"], FILTER_SANITIZE_NUMBER_INT);
            $IDreserva = filter_var($_POST["reserva_id"], FILTER_SANITIZE_NUMBER_INT);
            $IDlibroViejo = filter_var($_POST["libro_id"], FILTER_SANITIZE_NUMBER_INT);

            // Ejecucion de la consulta en la tabla pivote
            $update = $mysql->efectuarConsulta("UPDATE reserva_has_libro set libro_id = $IDlibroNuevo WHERE reserva_id = $IDreserva AND libro_id = $IDlibroViejo");

            if ($update) {
                echo json_encode([
                    "success" => true,
                    "message" => "Actualizacion completada"
                ]);
            } else {
                echo json_encode([
                    "success" => false,
                    "message" => "Ocurrio un error en el UPDATE..."
                ]);
            }

            $mysql->desconectar();

        }catch (Exception $ex){
            $excepcion = $ex->getMessage();
            $duplicacion = substr($excepcion, 0, 9);
            if($duplicacion == "Duplicate"){
                echo json_encode([
                    "success" => false,
                    "message" => "La reserva ya tiene ese libro registrado"
                ]);
            }else{
                $excepcion = "hola";
            }
           
        }
      
    }
}




?>