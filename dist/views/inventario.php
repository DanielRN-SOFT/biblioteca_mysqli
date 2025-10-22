<?php
$pagina = "Inventario";

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
// Sección: Conexión a la BD
// ==========================

// Llamar el modelo MYSQL
require_once '../../models/MYSQL.php';


// ===============================
// Layout de componentes HTML
// ===============================
require_once './layouts/head.php';
require_once './layouts/nav_bar.php';
require_once './layouts/aside_bar.php';
// Instancia de la clase
$mysql = new MySQL();

// Conexión con la base de datos
$mysql->conectar();

// Ejecución de la consulta
$libros = $mysql->efectuarConsulta("SELECT * FROM libro");

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
          <h3 class="mb-0 fw-bold"> <i class="fa-solid fa-book"></i> Libros</h3>
        </div>
      </div>

      <div class="row my-2">
        <?php if($tipoUsuario == "Administrador"){ ?>
        <div class="col-sm-12">
          <button class="btn btn-success w-100" id="crearLibro">Añadir Libro</button>
        </div>
        <?php } ?>
        <div class="col-sm-12">
          <button class="btn btn-primary w-100" id="crearBusqueda">Buscar</button>
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
              <h5 class="card-title fw-bold fs-5">Lista de libros</h5>
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
                    <table class="table table-bordered table-striped" id="tblLibros" width="100%" cellspacing="0">
                      <thead>
                        <tr>
                          <th>Titulo</th>
                          <th>Autor</th>
                          <th>ISBN</th>
                          <th>Categoria</th>
                          <th>Disponibilidad</th>
                          <th>Cantidad</th>
                          <th>Estado</th>
                          <?php if($tipoUsuario == "Administrador"){ ?>
                          <th>Acciones</th>
                          <?php } ?>
                        </tr>
                      </thead>
                      <tbody>
                        <?php while ($fila = $libros->fetch_assoc()): ?>
                          <tr>
                            <td><?php echo $fila["titulo"]; ?></td>
                            <td><?php echo $fila["autor"]; ?></td>
                            <td><?php echo $fila["ISBN"]; ?></td>
                            <td><?php echo $fila["categoria"]; ?></td>
                            <td><?php echo $fila["disponibilidad"]; ?></td>
                            <td><?php echo $fila["cantidad"]; ?></td>
                            <td><?php echo $fila["estado"]; ?></td>
                            <?php if ($tipoUsuario === "Administrador"){?>
                            <td>
                              <button class="btn btn-primary mx-1" onclick="editarLibro(<?php echo $fila['id'] ?>)"><i class="fa-solid fa-pen-to-square"></i></button>
                              <?php if ($fila["estado"] == "Activo") { ?>
                                <button class="btn btn-danger btn-eliminar-libro" onclick="eliminarLibro(<?php echo $fila['id'] ?> , '<?php echo $fila['estado'] ?>')" data-id="<?php echo $fila["id"] ?>"><i class="fa-solid fa-trash"></i></button>
                              <?php } else { ?>
                                <button class="btn btn-success btn-reitengrar-libro" onclick="reintegrarLibro(<?php echo $fila['id'] ?> , '<?php echo $fila['estado'] ?>')" data-id="<?php echo $fila["id"] ?>"><i class="fa-solid fa-check"></i></button>


                              <?php } ?>
                            </td>
                            <?php } ?>
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