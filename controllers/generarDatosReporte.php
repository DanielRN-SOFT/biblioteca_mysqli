<?php

require_once '../models/MYSQL.php';

class generarDatosReporte
{
// ===================
    //! REPORTES GENERALES 
// ===================
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
    // ===================
    //! REPORTES ESPECIFICOS POR TIPO
    // ===================


//* *USUSARIOS
    public function datosTipoUsuarios($fechaInicio, $fechaFin, $tipoInformeDatos)
    {
        $mysql = new MySQL();
        $mysql->conectar();
        if ($tipoInformeDatos === "Usuarios con mas prestamos") {
            $consulta = "SELECT u.nombre, u.apellido, COUNT(p.id) AS total_prestamos FROM usuario u INNER JOIN reserva r ON u.id = r.id_usuario INNER JOIN prestamo p ON r.id = p.id_reserva WHERE DATE(p.fecha_prestamo) BETWEEN '$fechaInicio' AND '$fechaFin' GROUP BY u.id ORDER BY total_prestamos DESC LIMIT 10";
        } elseif ($tipoInformeDatos === "Usuarios con mas reservas") {
            $consulta = "SELECT u.nombre, u.apellido, COUNT(r.id) AS total_reservas FROM usuario u INNER JOIN reserva r ON u.id = r.id_usuario WHERE DATE(r.fecha_reserva) BETWEEN '$fechaInicio' AND '$fechaFin' GROUP BY u.id ORDER BY total_reservas DESC LIMIT 10";
        }
        $resultado = $mysql->efectuarConsulta($consulta);
        $usuariosTipo = [];

        while ($fila = $resultado->fetch_assoc()) {
            $usuariosTipo[] = $fila;
        }

        return $usuariosTipo;
    }


    //* *INVENTARIO
    public function datosTipoInventario($fechaInicio, $fechaFin, $tipoInformeDatos)
    {
        $mysql = new MySQL();
        $mysql->conectar();
        if ($tipoInformeDatos === "Libros Disponibles") {
            $consulta = "SELECT * FROM libro WHERE disponibilidad = 'Disponible' AND DATE(fecha_creacion) BETWEEN '$fechaInicio' AND '$fechaFin'";
        } elseif ($tipoInformeDatos === "Libros Prestados") {
            $consulta = "SELECT l.titulo AS titulo_libro,CONCAT(u.nombre, ' ', u.apellido) AS usuario, p.fecha_prestamo FROM prestamo p INNER JOIN reserva r ON p.id_reserva = r.id INNER JOIN usuario u ON r.id_usuario = u.id INNER JOIN reserva_has_libro rl ON r.id = rl.reserva_id INNER JOIN libro l ON rl.libro_id = l.id WHERE p.estado = 'Prestado' AND DATE(p.fecha_prestamo) BETWEEN '$fechaInicio' AND '$fechaFin' ORDER BY p.fecha_prestamo DESC";
        } elseif ($tipoInformeDatos === "Libros Reservados") {
            $consulta = "SELECT l.titulo AS titulo_libro,CONCAT(u.nombre, ' ', u.apellido) AS usuario,r.fecha_reserva FROM reserva r INNER JOIN usuario u ON r.id_usuario = u.id INNER JOIN reserva_has_libro rl ON r.id = rl.reserva_id INNER JOIN libro l ON rl.libro_id = l.id WHERE r.estado IN ('Aprobada','Pendiente') AND DATE(r.fecha_reserva) BETWEEN '$fechaInicio' AND '$fechaFin' ORDER BY l.titulo ASC";
        } elseif ($tipoInformeDatos === "Libros no Disponibles") {
            $consulta = "SELECT * FROM libro WHERE disponibilidad = 'No Disponible' AND DATE(fecha_creacion) BETWEEN '$fechaInicio' AND '$fechaFin'";
        }
        $resultado = $mysql->efectuarConsulta($consulta);
        $inventarioTipo = [];

        while ($fila = $resultado->fetch_assoc()) {
            $inventarioTipo[] = $fila;
        }

        return $inventarioTipo;
    }

    //* *RESERVAS
    public function datosTipoReservas($fechaInicio, $fechaFin, $tipoInformeDatos)
    {
        $mysql = new MySQL();
        $mysql->conectar();
        if($tipoInformeDatos === "Reservas Aprobadas"){
            $consulta ="SELECT reserva.id,CONCAT(usuario.nombre,' ',usuario.apellido) AS usuario,libro.titulo,reserva.fecha_reserva FROM reserva INNER JOIN usuario ON reserva.id_usuario=usuario.id INNER JOIN reserva_has_libro ON reserva.id=reserva_has_libro.reserva_id INNER JOIN libro ON reserva_has_libro.libro_id=libro.id WHERE reserva.estado='Aprobada' AND DATE(reserva.fecha_reserva) BETWEEN '$fechaInicio' AND '$fechaFin'";
        }elseif($tipoInformeDatos === "Reservas Rechazadas"){
            $consulta ="SELECT reserva.id,CONCAT(usuario.nombre,' ',usuario.apellido) AS usuario,libro.titulo,reserva.fecha_reserva FROM reserva INNER JOIN usuario ON reserva.id_usuario=usuario.id INNER JOIN reserva_has_libro ON reserva.id=reserva_has_libro.reserva_id INNER JOIN libro ON reserva_has_libro.libro_id=libro.id WHERE reserva.estado='Rechazada' AND DATE(reserva.fecha_reserva) BETWEEN '$fechaInicio' AND '$fechaFin'";
        }elseif($tipoInformeDatos === "Reservas Pendientes"){
            $consulta ="SELECT reserva.id,CONCAT(usuario.nombre,' ',usuario.apellido) AS usuario,libro.titulo,reserva.fecha_reserva FROM reserva INNER JOIN usuario ON reserva.id_usuario=usuario.id INNER JOIN reserva_has_libro ON reserva.id=reserva_has_libro.reserva_id INNER JOIN libro ON reserva_has_libro.libro_id=libro.id WHERE reserva.estado='Pendiente' AND DATE(reserva.fecha_reserva) BETWEEN '$fechaInicio' AND '$fechaFin'";
        }
        $resultado = $mysql->efectuarConsulta($consulta);
        $reservasTipo = [];

        while ($fila = $resultado->fetch_assoc()) {
            $reservasTipo[] = $fila;
        }

        return $reservasTipo;
    }
    //* *PRESTAMOS
    public function datosTipoPrestamos($fechaInicio, $fechaFin, $tipoInformeDatos)
    {
        $mysql = new MySQL();
        $mysql->conectar();
        if($tipoInformeDatos === "Prestamos Vigente"){
            $consulta="SELECT prestamo.id,CONCAT(usuario.nombre,' ',usuario.apellido) AS usuario,libro.titulo,prestamo.fecha_prestamo,prestamo.fecha_devolucion, prestamo.estado FROM prestamo INNER JOIN reserva ON prestamo.id_reserva=reserva.id INNER JOIN usuario ON reserva.id_usuario=usuario.id INNER JOIN reserva_has_libro ON reserva.id=reserva_has_libro.reserva_id INNER JOIN libro ON reserva_has_libro.libro_id=libro.id WHERE prestamo.estado='Prestado' AND DATE(prestamo.fecha_prestamo) BETWEEN '$fechaInicio' AND '$fechaFin'";
        }elseif($tipoInformeDatos === "Prestamos Cancelado"){
            $consulta="SELECT prestamo.id,CONCAT(usuario.nombre,' ',usuario.apellido) AS usuario,libro.titulo,prestamo.fecha_prestamo,prestamo.fecha_devolucion, prestamo.estado FROM prestamo INNER JOIN reserva ON prestamo.id_reserva=reserva.id INNER JOIN usuario ON reserva.id_usuario=usuario.id INNER JOIN reserva_has_libro ON reserva.id=reserva_has_libro.reserva_id INNER JOIN libro ON reserva_has_libro.libro_id=libro.id WHERE prestamo.estado='Devuelto' AND DATE(prestamo.fecha_prestamo) BETWEEN '$fechaInicio' AND '$fechaFin'";
        }elseif($tipoInformeDatos === "Libros mas Prestados"){
            $consulta="SELECT libro.titulo,COUNT(prestamo.id) AS total_prestamos FROM prestamo INNER JOIN reserva ON prestamo.id_reserva=reserva.id INNER JOIN reserva_has_libro ON reserva.id=reserva_has_libro.reserva_id INNER JOIN libro ON reserva_has_libro.libro_id=libro.id WHERE DATE(prestamo.fecha_prestamo) BETWEEN '$fechaInicio' AND '$fechaFin' GROUP BY libro.id ORDER BY total_prestamos DESC LIMIT 10";
        }
        $resultado = $mysql->efectuarConsulta($consulta);
        $prestamosTipo = [];

        while ($fila = $resultado->fetch_assoc()) {
            $prestamosTipo[] = $fila;
        }

        return $prestamosTipo;
    }
}
