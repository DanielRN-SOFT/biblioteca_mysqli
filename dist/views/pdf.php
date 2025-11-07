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

?>

<!-- ========================== -->
<!-- Sección: Main Content      -->
<!-- ========================== -->
<main class="app-main bg-light py-5">
    <div class="container">
        <!-- Encabezado -->
        <div class="text-center mb-5">
            <div class="p-4 rounded-4 bg-white shadow-sm d-inline-block">
                <h2 class="fw-bold text-dark mb-1">
                    <i class="fa-solid fa-file-pdf text-danger me-2"></i>
                    Generación de reportes PDF
                </h2>
                <p class="text-secondary mb-0">Selecciona una categoría y el rango de fechas para generar tu reporte.</p>
            </div>
        </div>

        <!-- Tarjeta principal -->
        <div class="card border-0 rounded-4 shadow-lg overflow-hidden">
            <div class="card-header bg-white border-0 py-4 px-4 d-flex align-items-center justify-content-between flex-wrap">
                <div class="d-flex align-items-center">
                    <div class="bg-danger bg-gradient text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 45px; height: 45px;">
                        <i class="fa-solid fa-chart-line fs-5"></i>
                    </div>
                    <div>
                        <h5 class="fw-bold mb-0 text-dark">Generar nuevo reporte</h5>
                        <small class="text-muted">Define los filtros y genera tu documento PDF</small>
                    </div>
                </div>
            </div>

            <div class="card-body bg-light p-5">
                <form method="post" action="" id="frmReportes">
                    <div class="row g-4">
                        <!-- Campo: Categoria -->
                        <div class="col-md-6 col-lg-3">
                            <label for="tipoInforme" class="form-label fw-semibold text-dark">Categoría</label>
                            <div class="input-group">
                                <span class="input-group-text bg-white border-0 shadow-sm">
                                    <i class="fa-solid fa-layer-group text-danger"></i>
                                </span>
                                <select class="form-select border-0 shadow-sm" name="tipoInforme" id="tipoInforme" onchange="actualizarTipoInforme()">
                                    <option value="">Selecciona un tipo</option>
                                    <option value="Usuario">Usuarios</option>
                                    <option value="Inventario">Inventario</option>
                                    <option value="Reserva">Reservas</option>
                                    <option value="Prestamo">Préstamos</option>
                                </select>
                            </div>
                        </div>

                        <!-- Campo: Fecha inicio -->
                        <div class="col-md-6 col-lg-3">
                            <label for="fechaInicio" class="form-label fw-semibold text-dark">Fecha de inicio</label>
                            <div class="input-group">
                                <span class="input-group-text bg-white border-0 shadow-sm">
                                    <i class="fa-solid fa-calendar-day text-danger"></i>
                                </span>
                                <input type="date" class="form-control border-0 shadow-sm" name="fechaInicio" id="fechaInicio">
                            </div>
                        </div>

                        <!-- Campo: Fecha fin -->
                        <div class="col-md-6 col-lg-3">
                            <label for="fechaFin" class="form-label fw-semibold text-dark">Fecha de fin</label>
                            <div class="input-group">
                                <span class="input-group-text bg-white border-0 shadow-sm">
                                    <i class="fa-solid fa-calendar-check text-danger"></i>
                                </span>
                                <input type="date" class="form-control border-0 shadow-sm" name="fechaFin" id="fechaFin">
                            </div>
                        </div>

                        <!-- Campo: Tipo de informe -->
                        <div class="col-md-6 col-lg-3">
                            <label for="tipoInformeCategoria" class="form-label fw-semibold text-dark">Tipo de informe</label>
                            <div class="input-group">
                                <span class="input-group-text bg-white border-0 shadow-sm">
                                    <i class="fa-solid fa-list-check text-danger"></i>
                                </span>
                                <select class="form-select border-0 shadow-sm" name="tipoInformeCategoria" id="tipoInformeCategoria">
                                    <option value="">Selecciona un tipo</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Botón -->
                    <div class="text-center mt-5">
                        <button type="submit" class="btn btn-danger btn-lg px-5 py-3 shadow-lg rounded-5" id="btnGenerarPDF" style="font-weight:600; letter-spacing:0.5px;">
                            <i class="fa-solid fa-file-pdf me-2"></i>Generar PDF
                        </button>
                        <div class="mt-3">
                            <small class="text-muted">El reporte se generará con los filtros seleccionados.</small>
                        </div>
                    </div>
                </form>
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
