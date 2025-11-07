<?php
$pagina = "Inventario";

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

// Ordenar los libros primero activos y disponibles 

if ($tipoUsuario == "Administrador") {
  $libros = $mysql->efectuarConsulta("SELECT * FROM libro
ORDER BY CASE
WHEN libro.disponibilidad = 'Disponible' THEN 1
WHEN libro.estado = 'Activo' THEN 2
WHEN libro.estado = 'Inactivo' THEN 3
ELSE 3
END");
} else {
  $libros = $mysql->efectuarConsulta("SELECT * FROM libro WHERE libro.estado = 'Activo'
ORDER BY CASE
WHEN libro.disponibilidad = 'Disponible' THEN 1
WHEN libro.disponibilidad = 'No disponible' THEN 2
ELSE 3
END");
}


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
        <?php if ($tipoUsuario == "Administrador") { ?>
          <div class="col-sm-12">
            <button class="btn btn-success fw-bold w-100" id="crearLibro">Crear nuevo libro</button>
          </div>
        <?php } ?>
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
                <i class="fa-solid fa-list me-2"></i> Lista de libros
              </h5>
            </div>
            <!-- /.card-header -->

            <div class="card-body">
              <div class="row">
                <div class="col-md-12" id="contenedorTabla">
                  <div class="table-responsive">
                    <table class="table align-middle table-striped nowrap" id="tblGeneral" width="100%" cellspacing="0">
                      <thead class="table-light">
                        <tr>
                          <th>Titulo</th>
                          <th>Autor</th>
                          <th>ISBN</th>
                          <th>Categoria</th>
                          <th class="text-center">Cantidad</th>
                          <th>Disponibilidad</th>
                          <?php if ($tipoUsuario == "Administrador") { ?>
                            <th>Estado</th>
                            <th>Fecha de creacion</th>
                            <th>Acciones</th>
                          <?php } ?>
                        </tr>
                      </thead>
                      <tbody>
                        <?php while ($fila = $libros->fetch_assoc()): ?>
                          <?php $claseEstado = $fila["estado"] == "Activo" ? "badge  rounded-pill text-bg-success" : "badge  rounded-pill text-bg-danger"; ?>

                          <?php $claseDisponibilidad = $fila["disponibilidad"] == "Disponible" ? "badge rounded-pill text-bg-success" : "badge rounded-pill text-bg-danger"; ?>


                          <tr>
                            <td><?php echo $fila["titulo"]; ?></td>
                            <td><?php echo $fila["autor"]; ?></td>
                            <td><?php echo $fila["ISBN"]; ?></td>
                            <td><?php echo $fila["categoria"]; ?></td>
                            <td class="text-center"><?php echo $fila["cantidad"]; ?></td>
                            <td> <span class="<?php echo $claseDisponibilidad ?> px-3 py-2"><?php echo $fila["disponibilidad"]; ?></span></td>

                            <?php if ($tipoUsuario === "Administrador") { ?>
                              <td><span class="<?php echo $claseEstado ?> px-3 py-2"><?php echo $fila["estado"]; ?></span></td>
                              <td><?php echo $fila["fecha_creacion"]; ?></td>
                              <td>
                                <div class="btn-group" role="group">
                                  <button class="btn btn-primary" onclick="editarLibro(<?php echo $fila['id'] ?>)"><i class="fa-solid fa-pen-to-square"></i></button>
                                  <?php if ($fila["estado"] == "Activo") { ?>
                                    <button class="btn btn-danger btn-eliminar-libro" onclick="eliminarLibro(<?php echo $fila['id'] ?> , '<?php echo $fila['estado'] ?>' , '<?php echo $fila['titulo'] ?>')" data-id="<?php echo $fila["id"] ?>"><i class="fa-solid fa-trash"></i></button>
                                  <?php } else { ?>
                                    <button class="btn btn-success btn-reitengrar-libro" onclick="reintegrarLibro(<?php echo $fila['id'] ?> , '<?php echo $fila['estado'] ?>')" data-id="<?php echo $fila["id"] ?>"><i class="fa-solid fa-check"></i></button>


                                  <?php } ?>
                                </div>
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