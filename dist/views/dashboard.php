<?php
// Nombre de la pagina para el title HTML
$pagina = "Dashboard";

// ==========================
// Sección: Inicio de sesion
// ==========================
session_start();

if ($_SESSION["acceso"] == false || $_SESSION["acceso"] = null) {
  header("location: ./login.php");
}else{
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

// Ejecución de la consulta
$usuarios = $mysql->efectuarConsulta("SELECT * FROM usuario");


// ==========================
// Layout de componentes HTML
// ==========================
require_once './layouts/head.php';
require_once './layouts/nav_bar.php';
require_once './layouts/aside_bar.php';

?>


<!-- ========================== -->
<!-- Sección: Main Content      -->
<!-- ========================== -->
<main class="app-main">
  <div class="app-content-header">
    <div class="container-fluid">
      <div class="row">
        <div class="col-sm-6">
          <h3 class="mb-0 fw-bold">Titulo de ejemplo</h3>
        </div>
      </div>

      <div class="row my-2">
        <div class="col-sm-12">
          <button class="btn btn-primary w-100" id="abrirCrearFrm">Crear nuevo </button>
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
              <h5 class="card-title fw-bold fs-5">Lista de ejemplo</h5>
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
                    <table class="table table-bordered table-striped" id="tblDashboard" width="100%" cellspacing="0">
                      <thead>
                        <tr>
                          <th>Nombre</th>
                          <th>Apellido</th>
                          <th>Email</th>
                          <th>Tipo</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php while ($fila = $usuarios->fetch_assoc()): ?>
                          <tr>
                            <td><?php echo $fila["nombre"]; ?></td>
                              <td><?php echo $fila["apellido"]; ?></td>
                            <td><?php echo $fila["email"]; ?></td>
                            <td><?php echo $fila["tipo"]; ?></td>
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
// ==========================
// Footer
// ==========================
require_once './layouts/footer.php';


$mysql->desconectar();
// ==========================
// Fin sección: Conexión a la BD
// ==========================
?>