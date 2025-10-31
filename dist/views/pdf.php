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
                        <i class="fa-solid fa-file-pdf text-danger"></i>
                        Generacion de reportes PDF
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
                        <div class="card-header bg-danger rounded-top">
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
                                                <label class="form-label fw-semibold" for="tipoInforme">Categoria</label>
                                                <select class="form-select" name="tipoInforme" id="tipoInforme" onchange="actualizarTipoInforme()">
                                                    <option value="Usuario">Usuarios</option>
                                                    <option value="Inventario">Inventario</option>
                                                    <option value="Reserva">Reservas</option>
                                                    <option value="Prestamo">Prestamos</option>

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
                                            <div class="col-sm-4">
                                                <label class="form-label fw-semibold" for="tipoInforme">Tipo de informe</label>
                                                <select class="form-select" name="tipoInformeCategoria" id="tipoInformeCategoria">
                                                    <option value="">Selecciona un tipo</option>
                                                </select>
                                            </div>


                                        </div>
                                        <div class="text-end">
                                            <button type="submit" class="btn btn-danger mt-4 w-25" id="btnGenerarPDF">
                                                <i class="fa-solid fa-file-pdf"></i>
                                                Generar PDF</button>
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