<?php

require_once '../models/MYSQL.php';

class generarDatosReporte
{

    public function datosReserva($fechaInicio, $fechaFin)
    {
        // Conexion a la BD
        $mysql = new MySQL();
        $mysql->conectar();

        $consulta = "SELECT usuario.nombre, usuario.apellido, reserva.fecha_reserva, reserva.estado, reserva_has_libro.reserva_id, libro.titulo FROM usuario 
            JOIN reserva ON usuario.id = reserva.id_usuario 
            JOIN reserva_has_libro ON reserva.id = reserva_has_libro.reserva_id 
            JOIN libro ON reserva_has_libro.libro_id = libro.id
            WHERE DATE(reserva.fecha_reserva) BETWEEN '$fechaInicio' AND '$fechaFin'";

        $resultado = $mysql->efectuarConsulta($consulta);

        $reservas = [];

        while ($fila = $resultado->fetch_assoc()) {
            $reservas[] = $fila;
        }

        return $reservas;
    }

    public function datosUsuario($fechaInicio, $fechaFin)
    {
        // Conexion a la BD
        $mysql = new MySQL();
        $mysql->conectar();

        $consulta = "SELECT * FROM usuario
            WHERE DATE(usuario.fecha_creacion) BETWEEN '$fechaInicio' AND '$fechaFin'";

        $resultado = $mysql->efectuarConsulta($consulta);

        $usuarios = [];

        while ($fila = $resultado->fetch_assoc()) {
            $usuarios[] = $fila;
        }

        return $usuarios;
    }

    public function datosInventario($fechaInicio, $fechaFin)
    {
        // Conexion a la BD
        $mysql = new MySQL();
        $mysql->conectar();

        $consulta = "SELECT * FROM libro
            WHERE DATE(libro.fecha_creacion) BETWEEN '$fechaInicio' AND '$fechaFin'";

        $resultado = $mysql->efectuarConsulta($consulta);

        $libros = [];

        while ($fila = $resultado->fetch_assoc()) {
            $libros[] = $fila;
        }

        return $libros;
    }
    //PRESTAMOS
    public function datosPrestamos($fechaInicio, $fechaFin)
    {
        $mysql = new MySQL();
        $mysql->conectar();
        $consulta = "SELECT id,id_reserva,fecha_prestamo,fecha_devolucion,estado FROM prestamo WHERE DATE(prestamo.fecha_prestamo) BETWEEN '$fechaInicio' AND '$fechaFin'";
        $resultado = $mysql->efectuarConsulta($consulta);
        $prestamos = [];

        while ($fila = $resultado->fetch_assoc()) {
            $prestamos[] = $fila;
        }

        return $prestamos;
    }
    //  public function datosInventario($fechaInicio, $fechaFin)
    // {
    //     $mysql = new MySQL();
    //     $mysql->conectar();
    //     $consulta = "SELECT * FROM libro WHERE DATE(libro.fecha_creacion) BETWEEN '$fechaInicio' AND '$fechaFin'";
    //     $resultado = $mysql->efectuarConsulta($consulta);
    //     $libros = [];

    //     while ($fila = $resultado->fetch_assoc()) {
    //         $libros[] = $fila;
    //     }

    //     return $libros;
    // }
}
