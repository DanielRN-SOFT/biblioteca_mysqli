<?php
$pagina = "Categorias";

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

$categorias = $mysql->efectuarConsulta("SELECT * FROM categoria")


?>
<main class="app-main">
    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h3 class="mb-0 fw-bold"> <i class="fa-solid fa-list"></i>Categorias</h3>
                </div>
            </div>

            <div class="row my-2">
                <?php if ($tipoUsuario == "Administrador") { ?>
                    <div class="col-sm-12">
                        <button class="btn btn-success fw-bold w-100" id="crearCategoria"> <i class="fa-solid fa-plus"></i>Crear nueva categoria</button>
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
                                <i class="fa-solid fa-list me-2"></i> Lista de categorias
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
                                                    <th>Categoria</th>
                                                    <th>Estado</th>
                                                    <?php if ($tipoUsuario === "Administrador") { ?>
                                                        <th>Acciones</th>
                                                    <?php } ?>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php while ($fila = $categorias->fetch_assoc()): ?>
                                                    <tr>
                                                        <td><?php echo $fila["nombre_categoria"]; ?></td>
                                                        <!-- Estilos para el estado -->
                                                        <?php if ($fila["estado"] == "Activo") {
                                                            $estado = "text-bg-success";
                                                        } else if ($fila["estado"] == "Inactivo") {
                                                            $estado = "text-bg-danger";
                                                        } ?>
                                                        <td class="">
                                                            <span class="badge rounded-pill px-3 py-2 <?php echo $estado ?>">
                                                                <?php echo $fila["estado"] ?>
                                                            </span>
                                                        </td>
                                                        <?php if ($tipoUsuario === "Administrador") { ?>
                                                            <td>
                                                                <div class="btn-group" role="group">
                                                                    <button class="btn btn-primary" onclick="editarCategoria(<?php echo $fila['id'] ?>)"><i class="fa-solid fa-pen-to-square"></i></button>
                                                                    <?php if ($fila["estado"] == "Activo") { ?>
                                                                        <button class="btn btn-danger btn-eliminar-categoria" onclick="eliminarCategoria(<?php echo $fila['id'] ?> , '<?php echo $fila['estado'] ?>' , '<?php echo $fila['nombre_categoria'] ?>')" data-id="<?php echo $fila["id"] ?>"><i class="fa-solid fa-trash"></i></button>
                                                                    <?php } else { ?>
                                                                        <button class="btn btn-success btn-reitengrar-categoria" onclick="reintegrarCategoria(<?php echo $fila['id'] ?> , '<?php echo $fila['estado'] ?>')" data-id="<?php echo $fila["id"] ?>"><i class="fa-solid fa-check"></i></button>


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