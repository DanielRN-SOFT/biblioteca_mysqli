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
    $reservasUsuario = $mysql->efectuarConsulta("SELECT usuario.nombre, usuario.apellido, reserva.fecha_reserva, reserva_has_libro.reserva_id, reserva_has_libro.libro_id, libro.titulo, reserva.estado FROM usuario JOIN reserva ON usuario.id = reserva.id_usuario JOIN reserva_has_libro ON reserva.id = reserva_has_libro.reserva_id JOIN libro ON reserva_has_libro.libro_id = libro.id WHERE usuario.id = $IDusuario ORDER BY reserva_has_libro.reserva_id");
}

// Si es administrador puede ver todas las reservas 
if ($tipoUsuario == "Administrador") {
    $reservasUsuario = $mysql->efectuarConsulta("SELECT usuario.nombre, usuario.apellido, reserva.fecha_reserva, reserva_has_libro.reserva_id, reserva_has_libro.libro_id, libro.titulo, reserva.estado FROM usuario JOIN reserva ON usuario.id = reserva.id_usuario JOIN reserva_has_libro ON reserva.id = reserva_has_libro.reserva_id JOIN libro ON reserva_has_libro.libro_id = libro.id ORDER BY reserva_has_libro.reserva_id");
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
                    <h3 class="mb-0 fw-bold"> <i class="fa-solid fa-calendar-days"></i> Reservas</h3>
                </div>
            </div>

            <div class="row mt-3 mb-2">
                <div class="col-sm-12">
                    <button class="btn btn-success w-100" id="BtnCrearReserva" onclick="crearReserva(<?php echo $IDusuario ?>)">Crear nueva reserva</button>
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
                                        <table class="table table-bordered table-striped nowrap" id="tblUsuarios" width="100%" cellspacing="0">
                                            <thead>
                                                <tr>
                                                    <th>Usuario</th>
                                                    <th>Fecha</th>
                                                    <th>Reserva</th>
                                                    <th>Libro</th>
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
                                                        <td> <?php echo $fila["nombre"] . " " . $fila["apellido"] ?></td>
                                                        <td> <?php echo $fila["fecha_reserva"] ?></td>
                                                        <td> <?php echo $fila["reserva_id"] ?></td>
                                                        <td> <?php echo $fila["titulo"] ?></td>
                                                        <?php if ($fila["estado"] == "Aprobada") { ?>
                                                            <td class="text-success fw-bold"> <?php echo $fila["estado"] ?></td>
                                                        <?php } else if ($fila["estado"] == "Rechazada") { ?>
                                                            <td class="text-danger fw-bold"> <?php echo $fila["estado"] ?></td>
                                                        <?php } else { ?>
                                                            <td> <?php echo $fila["estado"] ?></td>
                                                        <?php } ?>
                                                        <td>
                                                            <?php if($fila["estado"] == "Pendiente" || $tipoUsuario == "Administrador"){?>
                                                            <button class="btn btn-primary mx-1" onclick="editarReserva(<?php echo $fila['reserva_id'] ?> ,<?php echo $fila['libro_id'] ?>, 
                                                            '<?php echo $fila['estado'] ?>', '<?php echo $tipoUsuario ?>')"><i class="fa-solid fa-pen-to-square"></i></button>
                                                            <?php } ?>

                                                            <?php if ($fila["estado"] == "Pendiente" || $fila["estado"] == "Aprobada") { ?>
                                                                <button class="btn btn-danger btn-eliminar-usuario mx-auto" onclick="cancelarReserva('<?php echo $tipoUsuario ?>',<?php echo $fila['reserva_id'] ?>, <?php echo $fila['libro_id'] ?>, '<?php echo $fila['titulo'] ?>', '<?php echo $fila['estado'] ?>')"><i class="fa-solid fa-trash"></i></button>
                                                            <?php } else if ($fila["estado"] == "Cancelada") { ?>
                                                                <button class="btn btn-success btn-reitegrar-usuario mx-auto" onclick="reintegrarReserva('<?php echo $tipoUsuario ?>',<?php echo $fila['reserva_id'] ?>, <?php echo $fila['libro_id'] ?>, '<?php echo $fila['titulo'] ?>', '<?php echo $fila['estado'] ?>')"><i class="fa-solid fa-check"></i></button>
                                                            <?php } else if ($fila["estado"] == "Rechazada" && $tipoUsuario == "Administrador") { ?>
                                                                <button class="btn btn-success btn-reitegrar-usuario mx-auto" onclick="reintegrarReserva('<?php echo $tipoUsuario ?>',<?php echo $fila['reserva_id'] ?>, <?php echo $fila['libro_id'] ?>, '<?php echo $fila['titulo'] ?>', '<?php echo $fila['estado'] ?>')"><i class="fa-solid fa-check"></i></button>
                                                            <?php } ?>

                                                        </td>
                                                        <?php if ($tipoUsuario == "Administrador") { ?>
                                                            <td>
                                                                <?php if ($fila["estado"] == "Pendiente") { ?>
                                                                    <button class="btn btn-success mx-1" onclick="aprobarReserva(<?php echo $fila['reserva_id'] ?>, <?php echo $fila['libro_id'] ?>, '<?php echo $fila['titulo'] ?>', '<?php echo $fila['estado'] ?>', '<?php echo 'Aprobar' ?>')"><i class="fa-solid fa-thumbs-up"></i></button>
                                                                    <button class="btn btn-danger" onclick="rechazarReserva(<?php echo $fila['reserva_id'] ?>, <?php echo $fila['libro_id'] ?>, '<?php echo $fila['titulo'] ?>', '<?php echo $fila['estado'] ?>', '<?php echo 'Rechazar' ?>')"><i class="fa-solid fa-circle-xmark"></i></button>
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

?>