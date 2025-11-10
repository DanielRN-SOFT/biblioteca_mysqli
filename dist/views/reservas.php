<?php


// Nombre de la pagina para el title HTML
$pagina = "Reservas";

// ==========================
// Sección: Inicio de sesion
// ==========================
session_start();

if ($_SESSION["acceso"] == false || $_SESSION["acceso"] == null) {
    header("location: ./login.php");
    exit();
} else {
    $_SESSION["acceso"] = true;
}

// ==========================
// Seccion: Conexion a la base de datos
// ==========================

// Modelo a utilizar
require_once '../../models/MYSQL.php';

// Instanciar la clase
$mysql = new MySQL();

// Conexion a la BD
$mysql->conectar();

// ===============================
// Layout de componentes HTML
// ===============================
require_once './layouts/head.php';
require_once './layouts/nav_bar.php';
require_once './layouts/aside_bar.php';




// Si es cliente solo puede ver sus reservas
if ($tipoUsuario == "Cliente") {
    $reservasUsuario = $mysql->efectuarConsulta("SELECT reserva.id, usuario.id as id_usuario , usuario.nombre, usuario.apellido, reserva.fecha_reserva, reserva.fecha_asistencia, reserva.estado FROM reserva JOIN usuario ON usuario.id = reserva.id_usuario WHERE usuario.id = $IDusuario ORDER BY reserva.fecha_reserva DESC");
}

// Si es administrador puede ver todas las reservas 
if ($tipoUsuario == "Administrador") {
    $reservasUsuario = $mysql->efectuarConsulta("SELECT reserva.id, usuario.id as id_usuario , usuario.nombre, usuario.apellido, reserva.fecha_reserva, reserva.fecha_asistencia, reserva.estado FROM reserva JOIN usuario ON usuario.id = reserva.id_usuario
    ORDER BY reserva.fecha_reserva DESC
");
}



?>


<!-- ========================== -->
<!-- Sección: Main Content      -->
<!-- ========================== -->
<main class="app-main">
    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h3 class="mb-0 fw-bold">
                        <i class="fa-solid fa-calendar-days"></i>
                        Reservas
                    </h3>
                </div>
            </div>

            <div class="row mt-3">
                <div class="col-sm-12">
                    <button class="btn btn-success fw-bold w-100 p-2" id="BtnCrearReserva" onclick="crearReserva(
                    <?php echo $IDusuario ?> , 
                    '<?php echo $tipoUsuario ?>')">
                        <i class="fa-solid fa-plus"></i>
                        Crear nueva reserva</button>
                </div>
            </div>
        </div>
    </div>

    <div class="app-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card shadow rounded-4 border-0 mb-4">
                        <div class="card-header bg-card-general d-flex justify-content-between align-items-center rounded-top-4 py-3">
                            <h5 class="mb-0 fw-semibold text-white">
                                <i class="fa-solid fa-list me-2"></i> Lista de reservas
                            </h5>
                        </div>
                        <!-- /.card-header -->

                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12" id="contenedorTabla">
                                    <div>
                                        <table class="table align-middle table-striped nowrap" id="tblGeneral" width="100%" cellspacing="0">
                                            <thead class="table-light">
                                                <tr>
                                                    <?php if ($tipoUsuario == "Administrador") { ?>
                                                        <th>Usuario</th>
                                                    <?php } ?>
                                                    <th>Reserva</th>
                                                    <th>Fecha de reserva</th>
                                                    <th>Fecha de asistencia</th>
                                                    <th>Estado</th>
                                                    <th>Acciones</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php while ($fila = $reservasUsuario->fetch_assoc()): ?>
                                                    <tr>
                                                        <?php if ($tipoUsuario == "Administrador") { ?>
                                                            <td> <?php echo $fila["nombre"] . " " . $fila["apellido"] ?></td>
                                                        <?php } ?>
                                                        <td> <?php echo $fila["id"] ?></td>
                                                        <td> <?php echo $fila["fecha_reserva"] ?></td>
                                                        <td> <?php echo $fila["fecha_asistencia"] ?></td>

                                                        <!-- Estilos para el estado -->
                                                        <?php if ($fila["estado"] == "Aprobada") {
                                                            $estado = "text-bg-success";
                                                        } else if ($fila["estado"] == "Rechazada") {
                                                            $estado = "text-bg-danger";
                                                        } else if ($fila["estado"] == "Pendiente") {
                                                            $estado = "text-bg-primary";
                                                        } else if ($fila["estado"] == "Cancelada") {
                                                            $estado = "text-bg-warning";
                                                        } ?>
                                                        <td class="">
                                                            <span class="badge rounded-pill px-3 py-2 <?php echo $estado ?>">
                                                                <?php echo $fila["estado"] ?>
                                                            </span>
                                                        </td>


                                                        <td>
                                                            <button
                                                                onclick="verDetalle(
                                                                <?php echo $fila['id'] ?> ,
                                                                '<?php echo $fila['nombre'] ?>' ,
                                                                '<?php echo $fila['apellido'] ?>',
                                                                '<?php echo $tipoUsuario ?>' ,
                                                                '<?php echo $fila['estado'] ?>' ,
                                                                <?php echo $fila['id_usuario'] ?>)" class="btn btn-info">
                                                                <i class="fa-solid fa-eye"></i>
                                                            </button>
                                                        </td>
                                                    </tr>
                                                <?php endwhile ?>
                                            </tbody>
                                        </table>

                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- ./card-body -->

                        <div class="card-footer bg-body-tertiary text-end small rounded-bottom-4">
                            Última actualización: <?php echo date("d/m/Y H:i"); ?>
                        </div>
                        <!-- /.card-footer -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
<!-- ========================== -->
<!-- Fin sección: Main Content  -->
<!-- ========================== -->



<?php

require_once './layouts/footer.php';
$mysql->desconectar();

?>