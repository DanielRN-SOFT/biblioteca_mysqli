<?php


// Nombre de la pagina para el title HTML
$pagina = "Reservas";

// ==========================
// Sección: Inicio de sesion
// ==========================
session_start();

if ($_SESSION["acceso"] == false || $_SESSION["acceso"] = null) {
    header("location: ./login.php");
} else {
    $_SESSION["acceso"] = true;
}

// ===============================
// Layout de componentes HTML
// ===============================
require_once './layouts/head.php';
require_once './layouts/nav_bar.php';
require_once './layouts/aside_bar.php';


// ==========================
// Seccion: Conexion a la base de datos
// ==========================

// Modelo a utilizar
require_once '../../models/MYSQL.php';

// Instanciar la clase
$mysql = new MySQL();

// Conexion a la BD
$mysql->conectar();

// Si es cliente solo puede ver sus reservas
if ($tipoUsuario == "Cliente") {
    $reservasUsuario = $mysql->efectuarConsulta("SELECT reserva.id, usuario.nombre, usuario.apellido, reserva.fecha_reserva, reserva.estado FROM reserva JOIN usuario ON usuario.id = reserva.id_usuario WHERE usuario.id = $IDusuario ORDER BY CASE 
    WHEN reserva.estado = 'Pendiente' THEN 1
    WHEN reserva.estado = 'Cancelada' THEN 2
    WHEN reserva.estado = 'Aprobada' THEN 3
    WHEN reserva.estado = 'Rechazada' THEN 4
    ELSE 5
    END, 
    reserva.fecha_reserva DESC");
}

// Si es administrador puede ver todas las reservas 
if ($tipoUsuario == "Administrador") {
    $reservasUsuario = $mysql->efectuarConsulta("SELECT reserva.id,  usuario.nombre, usuario.apellido, reserva.fecha_reserva, reserva.estado FROM reserva JOIN usuario ON usuario.id = reserva.id_usuario
    ORDER BY CASE 
            WHEN reserva.estado = 'Pendiente' THEN 1
            WHEN reserva.estado = 'Cancelada' THEN 2
            WHEN reserva.estado = 'Aprobada' THEN 3
            WHEN reserva.estado = 'Rechazada' THEN 4
            ELSE 5
        END,
        reserva.fecha_reserva DESC
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
                        Mis reservas
                    </h3>
                </div>
            </div>

            <div class="row mt-3 mb-2">
                <div class="col-sm-12">
                    <button class="btn btn-success w-100" id="BtnCrearReserva" onclick="crearReserva(
                    <?php echo $IDusuario ?> , 
                    '<?php echo $tipoUsuario ?>')">Crear nueva reserva</button>
                </div>
            </div>
        </div>
    </div>

    <div class="app-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="card-title fw-bold fs-5">Lista de reservas</h5>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-lte-toggle="card-collapse">
                                    <i data-lte-icon="expand" class="bi bi-plus-lg"></i>
                                    <i data-lte-icon="collapse" class="bi bi-dash-lg"></i>
                                </button>
                            </div>
                        </div>
                        <!-- /.card-header -->

                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12" id="contenedorTabla">
                                    <div>
                                        <table class="table table-bordered table-striped nowrap" id="tblGeneral" width="100%" cellspacing="0">
                                            <thead>
                                                <tr>
                                                    <?php if ($tipoUsuario == "Administrador") { ?>
                                                        <th>Usuario</th>
                                                    <?php } ?>
                                                    <th>Reserva</th>
                                                    <th>Fecha</th>
                                                    <th>Estado</th>
                                                    <th>Acciones</th>
                                                    <?php if ($tipoUsuario == "Administrador") { ?>
                                                        <th>Opciones</th>
                                                    <?php } ?>
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
                                                            <span class="badge <?php echo $estado ?>">
                                                                <?php echo $fila["estado"] ?>
                                                            </span>
                                                        </td>


                                                        <td>
                                                            <button
                                                                onclick="verDetalle(
                                                                <?php echo $fila['id'] ?> ,
                                                                '<?php echo $fila['nombre'] ?>' ,
                                                                '<?php echo $fila['apellido'] ?>')" class="btn btn-info">
                                                                <i class="fa-solid fa-eye"></i>
                                                            </button>

                                                            <?php if ($fila["estado"] == "Pendiente") { ?>
                                                                <button class="btn btn-danger mx-auto" onclick="cancelarReserva(
                                                                <?php echo $fila['id'] ?> , 
                                                               
                                                                '<?php echo $fila['estado'] ?>')">
                                                                    <i class="fa-solid fa-trash"></i>
                                                                </button>
                                                            <?php } else if ($fila["estado"] == "Cancelada") { ?>
                                                                <button class="btn btn-success mx-auto" onclick="reintegrarReserva(
                                                                <?php echo $fila['id'] ?>, 
                                                             
                                                                '<?php echo $fila['estado'] ?>')">
                                                                    <i class="fa-solid fa-check"></i>
                                                                </button>
                                                            <?php } ?>
                                                        </td>

                                                        <!-- OPCIONES DE RESERVA -->
                                                        <?php if ($tipoUsuario == "Administrador") { ?>
                                                            <td>
                                                                <?php if (
                                                                    $fila["estado"] == "Pendiente" ||
                                                                    $fila["estado"] == "Rechazada"
                                                                ) { ?>
                                                                    <button class="btn btn-success" onclick="aprobarReserva(
                                                                    <?php echo $fila['id'] ?>, 
                                                                    '<?php echo $fila['estado'] ?>', 
                                                                    '<?php echo 'Aprobar' ?>')">
                                                                        <i class="fa-solid fa-thumbs-up"></i>
                                                                    </button>
                                                                <?php } ?>

                                                                <?php if (
                                                                    $fila["estado"] == "Pendiente" ||
                                                                    $fila["estado"] == "Aprobada"
                                                                ) { ?>
                                                                    <button class="btn btn-danger" onclick="rechazarReserva(
                                                                    <?php echo $fila['id'] ?>, 
                                                                    '<?php echo $fila['estado'] ?>', 
                                                                    '<?php echo 'Rechazar' ?>')">
                                                                        <i class="fa-solid fa-circle-xmark"></i>
                                                                    </button>
                                                                <?php } ?>

                                                            </td>
                                                        <?php } ?>
                                                    </tr>
                                                <?php endwhile ?>
                                            </tbody>
                                        </table>

                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- ./card-body -->

                        <div class="card-footer"></div>
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