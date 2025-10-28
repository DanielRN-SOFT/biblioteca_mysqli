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
// ==========================
// Fin sección: Conexión a la BD
// ==========================
?>
<main class="app-main">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-4 mb-4 d-flex justify-content-center">
                <div class="card mt-3" style="width: 18rem;">
                    <img src="../assets/img/profile.png"
                        class="rounded-circle shadow"
                        alt="User Image" />>
                    <div class="card-body">
                        <h5 class="card-title">Card title</h5>
                        <p class="card-text">Some quick example text to build on the card title and make up the bulk of the cards content.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-8 align-self-center">
                <div class="profile-card">
                    <h3 class="fw-bold-card">Detalles Perfil</h3>
                    <form>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Nombre</label>
                                <input type="text" class="form-control">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Apellido</label>
                                <input type="text" class="form-control">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Email</label>
                                <input type="email" class="form-control">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Password</label>
                                <input type="password" class="form-control">
                            </div>
                        </div>

                </div>
                <div class="d-flex justify-content-end">
                    <button type="button" class="btn btn-primary me-2">Guardar</button>
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