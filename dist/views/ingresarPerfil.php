<?php
$pagina = "Perfil";

// ==========================
// Sección: Inicio de sesion
// ==========================
session_start();

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
//CONSULTAR USUARIO LOGEADO
$sql = $mysql->efectuarConsulta("SELECT nombre, apellido, email,tipo FROM usuario WHERE id='$IDusuario'");

if (mysqli_num_rows($sql) == 0) {
    die("No se encontró el usuario con ID: $IDusuario");
}

$usuario = mysqli_fetch_assoc($sql);
// ==========================
// Fin sección: Conexión a la BD
// ==========================
?>
<main class="app-main">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-4 mb-4 d-flex justify-content-center">
                <div class="card mt-3 text-center" style="width: 18rem;">
                    <div class="card-body d-flex flex-column align-items-center">
                        <img src="../assets/img/profile.png"
                            class="rounded-circle shadow mb-3"
                            alt="User Image"
                            style="width: 150px; height: 150px; object-fit: cover;">
                        <h3 class="card-title mb-0">
                            <?php echo $nombreUsuario . " " . $apellidoUsuario; ?>
                        </h3>
                        <span><?php echo $usuario['tipo']?></span>
                    </div>
                </div>
            </div>
            <div class="col-md-8 align-self-center">
    <div class="profile-card">
        <h3 class="fw-bold-card">Detalles Perfil</h3>
        <form method="post">
            <!-- Información Personal -->
            <div class="row mb-3">
                <div class="col-md-6">
                    <label class="form-label">Nombre</label>
                    <input type="text" class="form-control" id="nombre" name="nombre" value="<?php echo $usuario['nombre']; ?>">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Apellido</label>
                    <input type="text" class="form-control" id="apellido" name="apellido" value="<?php echo $usuario['apellido'] ?>">
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-12">
                    <label class="form-label">Email</label>
                    <input type="email" class="form-control" id="email" name="email" value="<?php echo $usuario['email'] ?>">
                </div>
            </div>

            <!-- Separador visual -->
            <hr class="my-4">

            <!-- Cambio de contraseña -->
            <div class="row mb-3">
                <div class="col-md-6">
                    <label class="form-label">Password Actual</label>
                    <input type="password" class="form-control" id="oldPassword" name="oldPassword" placeholder="Ingresa tu contraseña actual">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Nuevo Password</label>
                    <input type="password" class="form-control" id="newPassword" name="newPassword" disabled placeholder="Ingresa nueva contraseña">
                    <div class="form-check mb-2">
                        <input class="form-check-input" type="checkbox" id="cambiarPassword">
                        <label class="form-check-label" for="cambiarPassword">
                            ¿Deseas cambiar tu contraseña?
                        </label>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
        </div>
    </div>
    <div class="d-flex justify-content-end">
        <button type="button" class="btn btn-primary me-2" id="btnGuardar">Guardar</button>
        <button type="button" class="btn btn-success">Volver a Mi Perfil</button>
    </div>
    </form>
    </div>
    </div>
    </div>
    </div>
</main>
<?php

require_once './layouts/footer.php';
$mysql->desconectar();

?>