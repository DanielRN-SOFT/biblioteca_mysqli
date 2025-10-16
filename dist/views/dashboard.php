<?php
// Nombre de la pagina para el title HTML
$pagina = "Dashboard";

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
// Layout de componentes HTML
// ==========================
require_once './layouts/head.php';
require_once './layouts/nav_bar.php';
require_once './layouts/aside_bar.php';


// ==========================
// Sección: Conexión a la BD
// ==========================

// Llamar el modelo MYSQL
require_once '../../models/MYSQL.php';
// Instancia de la clase
$mysql = new MySQL();
// Conexión con la base de datos
$mysql->conectar();

// =========================
// Funciones
//  =======================

// Funcion general para contar la informacion del sistema
function contarInfo($nombreTabla, $alias)
{
  // Llamar el modelo MYSQL
  require_once '../../models/MYSQL.php';
  // Instancia de la clase
  $mysql = new MySQL();
  // Conexión con la base de datos
  $mysql->conectar();

  // Consulta 
  $consultaInfo = $mysql->efectuarConsulta("SELECT COUNT(*) as $alias FROM $nombreTabla");
  $conteoInfo = $consultaInfo->fetch_assoc()["$alias"];

  return $conteoInfo;
}

// Fuunciones para la lista de CLIENTE
function contarInfoCliente($estadoReserva, $ID)
{
  // Llamar el modelo MYSQL
  require_once '../../models/MYSQL.php';
  // Instancia de la clase
  $mysql = new MySQL();
  // Conexión con la base de datos
  $mysql->conectar();

  // Total de reservas por usuario
  $consultaReservas = $mysql->efectuarConsulta("SELECT COUNT(reserva.id) as conteo FROM reserva WHERE reserva.estado = '$estadoReserva' AND reserva.id_usuario = $ID");
  $conteoAprobadas = $consultaReservas->fetch_assoc()["conteo"];

  return $conteoAprobadas;
}

// =========================
// Funciones
//  =======================


// conteo de libros
$conteoLibros = contarInfo("libro", "conteoLibros");

if ($tipoUsuario == "Administrador") {
  // conteo de usuarios
  $conteoUsuarios = contarInfo("usuario", "conteoUsuarios");
  // conteo de reservas
  $conteoReserva = contarInfo("reserva", "conteoReserva");
  // conteo de prestamos
  $conteoPrestamos = contarInfo("prestamo", "conteoPrestamos");
}

if ($tipoUsuario == "Cliente") {
  // Consulta de reservas por usuario
  $consultaReservas = $mysql->efectuarConsulta("SELECT COUNT(*) as conteoReservas FROM reserva JOIN usuario ON usuario.id  = reserva.id_usuario WHERE usuario.id = $IDusuario");
  $conteoReserva = $consultaReservas->fetch_assoc()["conteoReservas"];

  // Consulta de prestamos por usuario
  $consultaPrestamos = $mysql->efectuarConsulta("SELECT COUNT(*) as conteoPrestamos FROM prestamo JOIN reserva ON reserva.id = prestamo.id_reserva JOIN usuario ON usuario.id  = reserva.id_usuario WHERE usuario.id = $IDusuario");
  $conteoPrestamos = $consultaPrestamos->fetch_assoc()["conteoPrestamos"];

  // Consultas de libros prestados de usuario
  $consultaLibros = $mysql->efectuarConsulta("SELECT libro.titulo, COUNT(reserva_has_libro.libro_id) AS cantidad FROM reserva_has_libro JOIN libro ON libro.id = reserva_has_libro.libro_id JOIN reserva ON reserva.id = reserva_has_libro.reserva_id WHERE reserva.id_usuario = $IDusuario GROUP BY reserva_has_libro.libro_id  
ORDER BY cantidad DESC LIMIT 5;");


  // Total de reservas por usuario
  $consultaTotalReservas = $mysql->efectuarConsulta("SELECT COUNT(reserva.id) as conteo FROM reserva WHERE reserva.id_usuario = $IDusuario");
  $conteoTotal = $consultaTotalReservas->fetch_assoc()["conteo"];

  // Conteo de reservas segun su estado 
  
  $conteoAprobadas = contarInfoCliente("Aprobada", $IDusuario);

  $conteoRechazadas = contarInfoCliente("Rechazada", $IDusuario);

  $conteoPendientes = contarInfoCliente("Pendiente", $IDusuario);

  $conteoCancelada = contarInfoCliente("Cancelada", $IDusuario);
}


?>


<!-- ========================== -->
<!-- Sección: Main Content      -->
<!-- ========================== -->
<main class="app-main">
  <!--begin::App Content Header-->
  <div class="app-content-header">
    <!--begin::Container-->
    <div class="container-fluid">
      <!--begin::Row-->
      <div class="row">
        <div class="col-sm-6">
          <h3 class="mb-0 fw-bold"> <i class="fa-solid fa-business-time"></i> Dashboard</h3>
        </div>
      </div>
      <!--end::Row-->
    </div>
    <!--end::Container-->
  </div>
  <!--end::App Content Header-->
  <!--begin::App Content-->
  <div class="app-content">
    <!--begin::Container-->
    <div class="container-fluid">
      <!-- Small Box (Stat card) -->

      <!-- Small boxes (Stat box) -->
      <div class="row mt-4">
        <?php if ($tipoUsuario == "Administrador") { ?>
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <a href="./usuarios.php" class="small-box text-bg-primary">
              <div class="inner">
                <h3><?php echo $conteoUsuarios ?></h3>

                <p>Total de usuarios</p>
              </div>
              <svg
                class="small-box-icon"
                fill="currentColor"
                viewBox="0 0 24 24"
                xmlns="http://www.w3.org/2000/svg"
                aria-hidden="true">
                <path
                  d="M6.25 6.375a4.125 4.125 0 118.25 0 4.125 4.125 0 01-8.25 0zM3.25 19.125a7.125 7.125 0 0114.25 0v.003l-.001.119a.75.75 0 01-.363.63 13.067 13.067 0 01-6.761 1.873c-2.472 0-4.786-.684-6.76-1.873a.75.75 0 01-.364-.63l-.001-.122zM19.75 7.5a.75.75 0 00-1.5 0v2.25H16a.75.75 0 000 1.5h2.25v2.25a.75.75 0 001.5 0v-2.25H22a.75.75 0 000-1.5h-2.25V7.5z"></path>
              </svg>

            </a>
          </div>
        <?php } ?>
        <!-- ./col -->
        <div class="col-lg-3 col-6">
          <!-- small box -->
          <a href="./inventario.php" class="small-box text-bg-success">
            <div class="inner">
              <h3><?php echo $conteoLibros ?></h3>

              <p>Total de libros</p>
            </div>
            <svg
              class="small-box-icon"
              fill="currentColor"
              viewBox="0 0 24 24"
              xmlns="http://www.w3.org/2000/svg"
              aria-hidden="true">
              <path
                d="M3 4.5A1.5 1.5 0 014.5 3H15a1.5 1.5 0 011.5 1.5V19.5A1.5 1.5 0 0115 21H4.5A1.5 1.5 0 013 19.5V4.5zm2.25 0v15h9V4.5h-9zM18 5.25a.75.75 0 01.75-.75h1.5A1.75 1.75 0 0122 6.25v12a1.75 1.75 0 01-1.75 1.75h-1.5a.75.75 0 01-.75-.75V5.25zm1.5.75v12h.75a.25.25 0 00.25-.25v-11.5a.25.25 0 00-.25-.25h-.75zM6 7.5h6a.75.75 0 010 1.5H6a.75.75 0 010-1.5zm0 3h6a.75.75 0 010 1.5H6a.75.75 0 010-1.5zm0 3h6a.75.75 0 010 1.5H6a.75.75 0 010-1.5z" />
            </svg>
          </a>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-6">
          <!-- small box -->
          <a href="./reservas.php" class="small-box text-bg-warning">
            <div class="inner">
              <h3><?php echo $conteoReserva ?></h3>

              <p>Total de reservas</p>
            </div>
            <svg
              class="small-box-icon"
              fill="currentColor"
              viewBox="0 0 24 24"
              xmlns="http://www.w3.org/2000/svg"
              aria-hidden="true">
              <path
                d="M6.75 2.25a.75.75 0 01.75.75v1.5h9V3a.75.75 0 011.5 0v1.5h.75A2.25 2.25 0 0121 6.75v12A2.25 2.25 0 0118.75 21H5.25A2.25 2.25 0 013 18.75v-12A2.25 2.25 0 015.25 4.5H6V3a.75.75 0 01.75-.75zM4.5 9v9.75c0 .414.336.75.75.75h13.5a.75.75 0 00.75-.75V9H4.5zm1.5 2.25h3v3h-3v-3zm4.5 0h3v3h-3v-3zm4.5 0h3v3h-3v-3z" />
            </svg>
          </a>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-6">
          <!-- small box -->
          <a href="./prestamos.php" class="small-box text-bg-danger">
            <div class="inner">
              <h3><?php echo $conteoPrestamos ?></h3>

              <p>Total de prestamos</p>
            </div>
            <svg
              class="small-box-icon"
              fill="currentColor"
              viewBox="0 0 24 24"
              xmlns="http://www.w3.org/2000/svg"
              aria-hidden="true">
              <path
                d="M7 3a1 1 0 00-1 1v7a1 1 0 001 1h4a1 1 0 001-1V4a1 1 0 00-1-1H7zm7.75 1.5a.75.75 0 00-.75.75V10a.75.75 0 00.75.75h2.69l-1.22 1.22a.75.75 0 101.06 1.06l2.5-2.5a.75.75 0 000-1.06l-2.5-2.5a.75.75 0 10-1.06 1.06l1.22 1.22H14V5.25a.75.75 0 00-.75-.75zM4.25 13.5a.75.75 0 00.75.75h2.69l-1.22 1.22a.75.75 0 001.06 1.06l2.5-2.5a.75.75 0 000-1.06l-2.5-2.5a.75.75 0 10-1.06 1.06l1.22 1.22H5a.75.75 0 00-.75.75v1.5zm7.25 1.5a1 1 0 00-1 1v4a1 1 0 001 1h4a1 1 0 001-1v-4a1 1 0 00-1-1h-4z" />
            </svg>
          </a>
        </div>
        <!-- ./col -->
      </div>
      <!-- /.row -->

      <div class="row mt-3">
        <!-- Start col -->
        <div class="col-lg-6 connectedSortable mx-auto">
          <div class="card mb-4">
            <div class="card-header">
              <h3 class="card-title"><?php echo ($tipoUsuario == "Administrador" ? "Libros más prestados" : "Libros más <span class = 'fw-bold'> prestados </span> de: " . $nombreUsuario . " " . $apellidoUsuario) ?></h3>
            </div>

            <div class="card-body">
              <?php if ($tipoUsuario == "Administrador") { ?>
                <canvas width="450" height="450" id="graficoLibros"></canvas>
              <?php } ?>

              <?php if ($tipoUsuario == "Cliente") { ?>
                <ul class="list-group">
                  <?php while ($fila = $consultaLibros->fetch_assoc()): ?>
                    <li class="list-group-item"> <span class="fw-bold">Libro: </span> <?php echo $fila["titulo"] ?> - <span class="fw-bold">Cantidad</span> <?php echo $fila["cantidad"] ?> </li>
                  <?php endwhile ?>
                </ul>
              <?php } ?>
            </div>
          </div>
          <!-- /.card -->


        </div>
        <!-- /.Start col -->


        <!-- Start col -->
        <div class="col-lg-6 connectedSortable">
          <div class="card mb-4">
            <div class="card-header">
              <h3 class="card-title"><?php echo ($tipoUsuario == "Administrador" ? "Usuarios con mayor cantidad de prestamos %" : "Total de <span class = 'fw-bold'>reservas </span> de: " . $nombreUsuario . " " . $apellidoUsuario) ?> </h3>
            </div>

            <div class="card-body">
              <div>
                <?php if ($tipoUsuario == "Administrador") { ?>
                  <canvas width="450" height="450" id="graficoUsuarios"></canvas>
                <?php } ?>

                <?php if ($tipoUsuario == "Cliente") { ?>
                  <ul class="list-group">
                    <li class="list-group-item"> <span class="fw-bold">Total: </span> <?php echo $conteoTotal ?></li>
                    <li class="list-group-item"> <span class="fw-bold">Pendientes: </span> <?php echo $conteoPendientes ?></li>
                    <li class="list-group-item"> <span class="fw-bold">Canceladas: </span> <?php echo $conteoCancelada ?></li>
                    <li class="list-group-item"> <span class="fw-bold text-success">Aprobadas: </span> <?php echo $conteoAprobadas ?></li>
                    <li class="list-group-item"> <span class="fw-bold text-danger">Rechazadas: </span> <?php echo $conteoRechazadas ?></li>

                  </ul>
                <?php } ?>
              </div>

            </div>
          </div>
          <!-- /.card -->
        </div>

        <!-- /.Start col -->

      </div>


    </div>
    <!--end::Container-->
  </div>
  <!--end::App Content-->
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