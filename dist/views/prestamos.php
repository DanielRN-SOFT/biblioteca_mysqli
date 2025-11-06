<?php
$pagina = "Prestamos";

// ==========================
// Sección: Inicio de sesion
// ==========================
session_start();

if ($_SESSION["acceso"] == false || $_SESSION["acceso"] == null) {
  header("location: ./login.php");
} else {
  $_SESSION["acceso"] = true;
}
// ==========================
// Sección: Conexión a la BD
// ==========================

// Llamar el modelo MYSQL
require_once '../../models/MYSQL.php';
// Instancia de la clase
$mysql = new MySQL();

// Conexión con la base de datos
$mysql->conectar();


// ===============================
// Layout de componentes HTML
// ===============================
require_once './layouts/head.php';
require_once './layouts/nav_bar.php';
require_once './layouts/aside_bar.php';




// Ejecución de la consulta si es Cliente
if ($tipoUsuario == "Cliente") {
  $prestamos = $mysql->efectuarConsulta("SELECT 
    prestamo.id,
    prestamo.id_reserva,
    prestamo.fecha_prestamo,
    prestamo.fecha_devolucion,
    prestamo.estado
FROM prestamo
JOIN reserva ON prestamo.id_reserva = reserva.id
WHERE reserva.id_usuario = $IDusuario
ORDER BY prestamo.fecha_prestamo DESC;
");
}
// Ejecución de la consulta si es administrador
if ($tipoUsuario == "Administrador") {
  $prestamos = $mysql->efectuarConsulta("SELECT * FROM prestamo 
  ORDER BY prestamo.fecha_prestamo DESC;");
}

// Fecha actual
$consultaFecha = $mysql->efectuarConsulta("SELECT DATE(NOW()) AS fecha_actual");
$fechaActual = $consultaFecha->fetch_assoc()["fecha_actual"];

require_once './layouts/head.php';
require_once './layouts/nav_bar.php';
require_once './layouts/aside_bar.php';
// ==========================
// Fin sección: Conexión a la BD
// ==========================
?>
<main class="app-main">
  <div class="app-content-header">
    <div class="container-fluid">
      <div class="row">
        <div class="col-sm-6">
          <h3 class="mb-0 fw-bold"> <i class="fa-solid fa-handshake"></i> Prestamos</h3>
        </div>
      </div>
    </div>
  </div>

  <div class="app-content">
    <div class="container-fluid">
      <div class="row">
        <div class="col-md-12">
          <div class="card mb-4">
            <div class="card-header bg-card-general">
              <h5 class="card-title fw-bold fs-5">Lista de Prestamos</h5>
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
                  <div class="table-responsive">
                    <table class="table table-bordered table-striped" id="tblGeneral" width="100%" cellspacing="0">
                      <thead>
                        <tr>
                          <th>Prestamo</th>
                          <th>Fecha Prestamo</th>
                          <th>Fecha Devolucion</th>
                          <th>Estado</th>
                          <th>Acciones</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php while ($fila = $prestamos->fetch_assoc()): ?>
                          <tr>
                            <td><?php echo $fila["id"]; ?></td>
                            <td><?php echo $fila["fecha_prestamo"]; ?></td>

                            <!-- Alerta de fecha de prestamos que ya pasaron -->
                            <?php if ($fechaActual > $fila["fecha_devolucion"] && $fila["estado"] == "Prestado" || $fila["estado"] == "Vencido") {
                              $claseFecha = "badge text-bg-danger";
                              
                            } else {
                              $claseFecha = "badge text-bg-success";
                            } ?>
                            <td>
                              <span class="<?php echo $claseFecha ?>">
                                <?php echo $fila["fecha_devolucion"]; ?>
                              </span>
                            </td>

                            <?php if ($fila["estado"] == "Prestado") {
                              $claseEstado = "badge text-bg-warning";
                            } else if ($fila["estado"] == "Devuelto") {
                              $claseEstado = "badge text-bg-primary";
                            } else if($fila["estado"] == "Vencido"){
                              $claseEstado = "badge text-bg-danger";
                            } else if($fila["estado"] == "Cancelado"){
                              $claseEstado = "badge text-bg-info";
                            }?>
                            <td>
                              <span class="<?php echo $claseEstado ?>">
                                <?php echo $fila["estado"]; ?>
                              </span>
                            </td>
                            <td>
                              <button
                                onclick="verDetalle(
                                      <?php echo $fila['id'] ?> ,
                                      <?php echo $fila['id_reserva'] ?> , 
                                      '<?php echo $fila['estado'] ?>' ,
                                      '<?php echo $tipoUsuario ?>'
                                     )" class="btn btn-info">
                                <i class="fa-solid fa-eye"></i>
                              </button>
                            </td>
                          </tr>
                        <?php endwhile; ?>
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