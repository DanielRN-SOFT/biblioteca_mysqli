<?php
// Nombre de la pagina para el title HTML
$pagina = "Usuarios";

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

// Llamar el modelo MYSQL
require_once '../../models/MYSQL.php';
// Instancia de la clase
$mysql = new MySQL();
// Conexión con la base de datos
$mysql->conectar();

// ==========================
// Layout de componentes HTML
// ==========================

require_once './layouts/head.php';
require_once './layouts/nav_bar.php';
require_once './layouts/aside_bar.php';


// Ejecución de la consulta
$usuarios = $mysql->efectuarConsulta("SELECT * FROM usuario WHERE id != $IDusuario
ORDER BY CASE WHEN usuario.estado = 'Activo' THEN 1
  WHEN usuario.estado = 'Inactivo' THEN 2
  ELSE 3
  END");



?>

<!-- ========================== -->
<!-- Sección: Main Content      -->
<!-- ========================== -->
<main class="app-main">
  <div class="app-content-header">
    <div class="container-fluid">
      <div class="row">
        <div class="col-sm-6">
          <h3 class="mb-0 fw-bold"> <i class="fa-solid fa-users"></i> Usuarios</h3>
        </div>
      </div>

      <div class="row mt-3 mb-2">
        <div class="col-sm-12">
          <button class="btn btn-success fw-bold w-100 p-2" id="BtnCrearUsuario">
            <i class="fa-solid fa-plus"></i> Crear nuevo usuario</button>
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
                <i class="fa-solid fa-list me-2"></i> Lista de usuarios
              </h5>

            </div>
            <!-- /.card-header -->

            <div class="card-body">
              <div class="row">
                <div class="col-md-12" id="contenedorTabla">
                  <div class="table-responsive">
                    <table class="table align-middle table-striped nowrap" id="tblGeneral" width="100%" cellspacing="0" role="table">
                      <thead class="table-light">
                        <tr>
                          <th>Nombre</th>
                          <th>Apellido</th>
                          <th>Email</th>
                          <th>Tipo</th>
                          <th>Estado</th>
                          <th>Fecha de creacion</th>
                          <th>Acciones</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php while ($fila = $usuarios->fetch_assoc()): ?>

                          <?php $claseEstado = $fila["estado"] == "Activo" ? "badge rounded-pill text-bg-success" : "badge rounded-pill text-bg-danger"; ?>
                          <tr>
                            <td><?php echo $fila["nombre"]; ?></td>
                            <td><?php echo $fila["apellido"]; ?></td>
                            <td><?php echo $fila["email"]; ?></td>
                            <td><?php echo $fila["tipo"]; ?></td>
                            <td> <span class="<?php echo $claseEstado ?> px-3 py-2"><?php echo $fila["estado"] ?></span></td>
                            <td><?php echo $fila["fecha_creacion"]; ?></td>
                            <td>
                              <div class="btn-group">


                                <button class="btn btn-primary" role="group"
                                  onclick="editarUsuario(<?php echo $fila['id'] ?>)">
                                  <i class="fa-solid fa-pen-to-square"></i>
                                </button>
                                <?php if ($fila["estado"] == "Activo") { ?>
                                  <button class="btn btn-danger btn-eliminar-usuario"
                                    onclick="eliminarUsuario(<?php echo $fila['id'] ?> , 
                                '<?php echo $fila['estado'] ?>',
                                '<?php echo htmlspecialchars($fila['nombre']) ?>' ,
                                '<?php echo htmlspecialchars($fila['apellido']) ?>')" data-id="<?php echo $fila["id"] ?>"><i class="fa-solid fa-trash"></i></button>
                                <?php } else { ?>
                                  <button class="btn btn-success btn-reitegrar-usuario" onclick="reintegrarUsuario(<?php echo $fila['id'] ?> , '<?php echo $fila['estado'] ?>')" data-id="<?php echo $fila["id"] ?>"><i class="fa-solid fa-check"></i></button>
                                <?php } ?>
                              </div>
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

// Footer del layout
require_once './layouts/footer.php';


$mysql->desconectar();
// ==========================
// Fin sección: Conexión a la BD
// ==========================
?>