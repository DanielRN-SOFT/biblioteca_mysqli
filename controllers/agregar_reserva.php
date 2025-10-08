<?php

// Verificar el metodo de envio
if($_SERVER["REQUEST_METHOD"] == "POST"){
    if(isset($_POST["libros"]) && !empty($_POST["libros"])){
     
            // ==============================
            // Conexion a la base de datos
            // ==============================

            require_once '../models/MYSQL.php';
            $mysql = new MySQL();
            $mysql->conectar();

            // ID del cliente
            $id = $_POST["IDcliente"];

            // Insertar la reserva como tal
            $insertReserva = $mysql->efectuarConsulta("INSERT INTO reserva(id_usuario, fecha_reserva, estado) VALUES($id, now(), 'Pendiente')");

            // $consultaUltimoID = $mysql->efectuarConsulta("SELECT MAX(id) as IDmax FROM reserva");
            // Seleccionar el ultimoID existente en reservas
            $ultimoID = $mysql->ultimoID();

            // Capturar el arreglo de libros
            $libros = $_POST["libros"];

            // Recorrer el arreglo de los libros
            foreach ($libros as $libro) {
                // Sanetizar los datos
                $libro = filter_var($libro, FILTER_SANITIZE_NUMBER_INT);
                // Insertar en la tabla pivote
                $insertPivote = $mysql->efectuarConsulta("INSERT INTO reserva_has_libro(reserva_id,libro_id) VALUES($ultimoID, $libro)");
            }

                echo json_encode([
                    "success" => true,
                    "message" => "Reserva agregada exitosamente"
                ]);
            
        
       


    }else{
        echo json_encode([
            "success" => false,
            "message" => "Seleccione por lo menos un libro"
        ]);
    }
}





?>