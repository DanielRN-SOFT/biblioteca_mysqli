<?php
//metodo a utilizar
if($_SERVER["REQUEST_METHOD"] == "POST"){
    if(isset($_POST["IDreserva"]) && !empty($_POST["IDlibro"])){

        // Sanitizacion de datos
        $IDreserva = filter_var($_POST["IDreserva"], FILTER_SANITIZE_NUMBER_INT);
        $IDlibro = filter_var($_POST["IDlibro"], FILTER_SANITIZE_NUMBER_INT);

        // =================
        // Cadena de conexion
        // =================

        require_once '../models/MYSQL.php';
        $mysql = new MySQL();
        $mysql->conectar();

        // Consulta para seleccionar el libro a editar 
        $consulta = $mysql->efectuarConsulta("SELECT libro.id, libro.titulo FROM libro JOIN reserva_has_libro ON reserva_has_libro.libro_id = libro.id WHERE reserva_has_libro.libro_id = $IDlibro AND reserva_has_libro.reserva_id = $IDreserva");
        $reservas = $consulta->fetch_assoc();

        // Enviar los datos via JSON
        echo json_encode($reservas);

        // Desconexion de la base de datos
        $mysql->desconectar();
    }
}


?>