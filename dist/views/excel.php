<?php

// Nombre de la pagina para el title HTML
$pagina = "Reportes";

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

// // Modelo a utilizar
// require_once '../../models/MYSQL.php';

// // Instanciar la clase
// $mysql = new MySQL();

// // Conexion a la BD
// $mysql->conectar();



?>


<!-- ========================== -->
<!-- Sección: Main Content      -->
<!-- ========================== -->
<main class="app-main bg-light py-4">
    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h3 class="mb-0 fw-bold">
                        <i class="fa-solid fa-file-excel text-success"></i>
                        Generacion de reportes de excel
                    </h3>
                </div>
            </div>


        </div>
    </div>

    <div class="app-content">
        <div class="container-fluid">
            <div class="row mt-2">
                <div class="col-md-12">
                    <div class="card rounded-4 mb-4">
                        <div class="card-header bg-success rounded-top">
                            <h5 class="card-title fw-bold fs-5 text-white">Generar nuevo reporte</h5>
                            <div class="card-tools">

                            </div>
                        </div>
                        <!-- /.card-header -->

                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12 mt-1 p-3" id="">
                                    <form method="post" action="" id="frmReportes">
                                        <div class="row">
                                            <div class="col-sm-4">
                                                <label class="form-label fw-semibold" for="tipoInforme">Tipo de informe</label>
                                                <select class="form-select" name="tipoInforme" id="tipoInforme">
                                                    <option value="usuarios">Usuarios</option>
                                                    <option value="inventario">Inventario</option>
                                                    <option value="reservas">Reservas</option>
                                                    <option value="prestamos">Prestamos</option>

                                                </select>
                                            </div>
                                            <div class="col-sm-4">
                                                <label class="form-label fw-semibold" for="fechaInicio">Fecha de inicio</label>
                                                <input class="form-control" type="date" name="fechaInicio" id="fechaInicio">
                                            </div>
                                            <div class="col-sm-4">
                                                <label class="form-label fw-semibold" for="fechaFin">Fecha de fin</label>
                                                <input class="form-control" type="date" name="fechaFin" id="fechaFin">
                                            </div>


                                        </div>
                                        <div class="text-end">
                                            <button type="submit" class="btn btn-success mt-4 w-25" id="btnGenerarExcel">
                                                <i class="fa-solid fa-file-excel"></i>
                                                Generar excel</button>
                                        </div>

                                    </form>
                                </div>
                            </div>
                        </div>
                        <!-- ./card-body -->

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