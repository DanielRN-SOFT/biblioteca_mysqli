<?php
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
$libros = $mysql->efectuarConsulta("SELECT * FROM libro");

// Desconexión con la base de datos
$mysql->desconectar();

// ==========================
// Fin sección: Conexión a la BD
// ==========================
?>

<!doctype html>
<html lang="en">
<!--begin::Head-->

<head>
  <!--begin::Meta-->
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Biblioteca_Mysqli</title>
  <!--end::Meta-->

  <!--begin::Preload CSS-->
  <link rel="preload" href="../css/adminlte.css" as="style" />
  <!--end::Preload CSS-->

  <!-- Favi.ico -->

  <link rel="shortcut icon" href="../assets/img/biblioteca.png" type="image/x-icon">

  <!-- Fin Favi.ico -->
  <!--begin::Fonts-->
  <link
    rel="stylesheet"
    href="https://cdn.jsdelivr.net/npm/@fontsource/source-sans-3@5.0.12/index.css"
    integrity="sha256-tXJfXfp6Ewt1ilPzLDtQnJV4hclT9XuaZUKyUvmyr+Q="
    crossorigin="anonymous"
    media="print"
    onload="this.media='all'" />
  <!--end::Fonts-->

  <!--begin::Third Party Plugin(OverlayScrollbars)-->
  <link
    rel="stylesheet"
    href="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.11.0/styles/overlayscrollbars.min.css"
    crossorigin="anonymous" />
  <!--end::Third Party Plugin(OverlayScrollbars)-->

  <!--begin::Third Party Plugin(Bootstrap Icons)-->
  <link
    rel="stylesheet"
    href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css"
    crossorigin="anonymous" />
  <!--end::Third Party Plugin(Bootstrap Icons)-->

  <!--begin::Required Plugin(AdminLTE)-->
  <link rel="stylesheet" href="../css/adminlte.css" />
  <!--end::Required Plugin(AdminLTE)-->

  <!--begin::Datatables CSS-->
  <link rel="stylesheet" href="https://cdn.datatables.net/2.3.4/css/dataTables.dataTables.css" />
  <link rel="stylesheet" href="https://cdn.datatables.net/columncontrol/1.1.0/css/columnControl.dataTables.css" />
  <link rel="stylesheet" href="https://cdn.datatables.net/colreorder/2.1.1/css/colReorder.dataTables.css" />
  <link rel="stylesheet" href="https://cdn.datatables.net/rowreorder/1.5.0/css/rowReorder.dataTables.css" />
  <link rel="stylesheet" href="https://cdn.datatables.net/responsive/3.0.6/css/responsive.dataTables.css" />
  <!--end::Datatables CSS-->
</head>
<!--end::Head-->

<!--begin::Body-->

<body class="layout-fixed sidebar-expand-lg sidebar-open bg-body-tertiary">
  <!--begin::App Wrapper-->
  <div class="app-wrapper">

    <!-- ========================== -->
    <!-- Sección: Header / Navbar   -->
    <!-- ========================== -->
    <nav class="app-header navbar navbar-expand bg-body">
      <!--begin::Container-->
      <div class="container-fluid">
        <!--begin::Start Navbar Links-->
        <ul class="navbar-nav">
          <li class="nav-item">
            <a class="nav-link" data-lte-toggle="sidebar" href="#" role="button">
              <i class="bi bi-list"></i>
            </a>
          </li>
          <li class="nav-item d-none d-md-block">
            <a href="./dashboard.php" class="nav-link">Home</a>
          </li>
        </ul>
        <!--end::Start Navbar Links-->

        <!--begin::End Navbar Links-->
        <ul class="navbar-nav ms-auto">
          <!--begin::User Menu Dropdown-->
          <li class="nav-item dropdown user-menu">
            <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">
              <img
                src="../assets/img/profile.png"
                class="user-image rounded-circle"
                alt="User Image" />
              <span class="d-none d-md-inline">Alexander Pierce</span>
            </a>
            <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-end">
              <!--begin::User Image-->
              <li class="user-header bg-body-secondary">
                <img
                  src="../assets/img/profile.png"
                  class="rounded-circle shadow"
                  alt="User Image" />
                <p>
                  Alexander Pierce - Web Developer
                  <small>Member since Nov. 2023</small>
                </p>
              </li>
              <!--end::User Image-->
              <!--begin::Menu Body-->
              <li class="user-body">
                <!--begin::Row-->
                <div class="row">
                  <div class="col-4 text-center">
                    <a href="#">Followers</a>
                  </div>
                  <div class="col-4 text-center">
                    <a href="#">Sales</a>
                  </div>
                  <div class="col-4 text-center">
                    <a href="#">Friends</a>
                  </div>
                </div>
                <!--end::Row-->
              </li>
              <!--end::Menu Body-->
              <!--begin::Menu Footer-->
              <li class="user-footer">
                <a href="#" class="btn btn-success text-light btn-flat">Profile</a>
                <a href="#" class="btn btn-danger text-light btn-flat float-end">Sign out</a>
              </li>
              <!--end::Menu Footer-->
            </ul>
          </li>
          <!--end::User Menu Dropdown-->
        </ul>
        <!--end::End Navbar Links-->
      </div>
      <!--end::Container-->
    </nav>
    <!-- ========================== -->
    <!-- Fin sección: Header / Navbar -->
    <!-- ========================== -->

    <!-- ========================== -->
    <!-- Sección: Sidebar           -->
    <!-- ========================== -->
    <aside class="app-sidebar bg-dark shadow" data-bs-theme="dark">
      <!--begin::Sidebar Brand-->
      <div class="sidebar-brand">
        <a href="./dashboard.php" class="brand-link">
          <img src="../assets/img/biblioteca.png" alt="AdminLTE Logo" class="brand-image opacity-75 shadow" />
          <span class="brand-text fw-light">Biblioteca</span>
        </a>
      </div>
      <!--end::Sidebar Brand-->

      <!--begin::Sidebar Wrapper-->
      <div class="sidebar-wrapper">
        <nav class="mt-2">
          <!--begin::Sidebar Menu-->
          <ul
            class="nav sidebar-menu flex-column"
            data-lte-toggle="treeview"
            role="navigation"
            aria-label="Main navigation"
            data-accordion="false"
            id="navigation">

            <!-- Dashboard principal -->
            <li class="nav-item">
              <a href="./index.php" class="nav-link active">
                <i class="fa-solid fa-table-columns"></i>
                <p>Dashboard</p>
              </a>
            </li>
            <!-- Fin de dasboard principal -->
            <!-- Menú de información -->
            <li class="nav-item menu-open">
              <a href="#" class="nav-link active">
                <i class="bi bi-person"></i>
                <p>Información <i class="nav-arrow bi bi-chevron-right"></i></p>
              </a>
              <ul class="nav nav-treeview">
                <li class="nav-item">
                  <a href="./usuarios.php" class="nav-link">
                    <i class="fa-regular fa-eye"></i>
                    <p>Usuarios</p>
                  </a>
                </li>
                <li class="nav-item">
                  <a href="./views/departamentos.php" class="nav-link">
                    <i class="fa-regular fa-eye"></i>
                    <p>Departamentos</p>
                  </a>
                </li>
                <li class="nav-item">
                  <a href="./views/cargos.php" class="nav-link">
                    <i class="fa-regular fa-eye"></i>
                    <p>Cargos</p>
                  </a>
                </li>
              </ul>
            </li>
            <!-- Fin menú de información -->

            <!-- Menú de reportes -->
            <li class="nav-header">REPORTES</li>
            <li class="nav-item">
              <a href="#" class="nav-link">
                <i class="fa-solid fa-file-pdf"></i>
                <p>PDFs <i class="nav-arrow bi bi-chevron-right"></i></p>
              </a>
              <ul class="nav nav-treeview">
                <li class="nav-item">
                  <a href="./views/generar_pdf.php" class="nav-link">
                    <i class="fa-solid fa-globe"></i>
                    <p>PDF General</p>
                  </a>
                </li>
                <li class="nav-item">
                  <a href="./views/ListadoDepartamentosPDF.php" class="nav-link">
                    <i class="fa-solid fa-building-user"></i>
                    <p>PDF por departamento</p>
                  </a>
                </li>
              </ul>
            </li>
            <!-- Fin menú de reportes -->

            <!-- Menú de gráficos -->
            <li class="nav-header">GRÁFICOS</li>
            <li class="nav-item">
              <a href="./views/graficoBarras.php" class="nav-link">
                <i class="fa-solid fa-signal"></i>
                <p>Empleados</p>
              </a>
            </li>
            <!-- Fin menú de gráficos -->
          </ul>
          <!--end::Sidebar Menu-->
        </nav>
      </div>
      <!--end::Sidebar Wrapper-->
    </aside>
    <!-- ========================== -->
    <!-- Fin sección: Sidebar       -->
    <!-- ========================== -->

    <!-- ========================== -->
    <!-- Sección: Main Content      -->
    <!-- ========================== -->
    <main class="app-main">
      <div class="app-content-header">
        <div class="container-fluid">
          <div class="row">
            <div class="col-sm-6">
              <h3 class="mb-0 fw-bold">Libros</h3>
            </div>
          </div>

          <div class="row my-2">
            <div class="col-sm-12">
              <button class="btn btn-primary w-100" id="crearLibro">Añadir Libro</button>
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
                        <table class="table table-bordered table-striped" id="dataTable" width="100%" cellspacing="0">
                          <thead>
                            <tr>
                              <th>ID</th>
                              <th>Titulo</th>
                              <th>Autor</th>
                              <th>ISBN</th>
                              <th>Categoria</th>
                              <th>Disponibilidad</th>
                              <th>Cantidad</th>
                              <th>Acciones</th>
                            </tr>
                          </thead>
                          <tbody>
                            <?php while ($fila = $libros->fetch_assoc()): ?>
                              <tr>
                                <td><?php echo $fila["id"]; ?></td>
                                <td><?php echo $fila["titulo"]; ?></td>
                                <td><?php echo $fila["autor"]; ?></td>
                                <td><?php echo $fila["ISBN"]; ?></td>
                                <td><?php echo $fila["categoria"]; ?></td>
                                <td><?php echo $fila["disponibilidad"]; ?></td>
                                <td><?php echo $fila["cantidad"]; ?></td>
                                <td>
                                    <button class="btn btn-primary mx-1"><i class="fa-solid fa-pen-to-square"></i></button>
                                    <button class="btn btn-danger mx-1"><i class="fa-solid fa-trash"></i></button>
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

    <!-- ========================== -->
    <!-- Sección: Footer            -->
    <!-- ========================== -->
    <footer class="app-footer">
      <div class="float-end d-none d-sm-inline">Anything you want</div>
      <strong>
        Copyright &copy; 2014-2025
        <a href="#" class="text-decoration-none">biblioteca_Mysqli.com</a>.
      </strong>
      All rights reserved.
    </footer>
    <!-- ========================== -->
    <!-- Fin sección: Footer        -->
    <!-- ========================== -->

  </div>
  <!--end::App Wrapper-->

  <!-- ========================== -->
  <!-- Sección: Scripts           -->
  <!-- ========================== -->
  <script src="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.11.0/browser/overlayscrollbars.browser.es6.min.js" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.min.js" crossorigin="anonymous"></script>
  <script src="../js/adminlte.js"></script>

  <!-- Configuración OverlayScrollbars -->
  <script>
    const SELECTOR_SIDEBAR_WRAPPER = '.sidebar-wrapper';
    const Default = {
      scrollbarTheme: 'os-theme-light',
      scrollbarAutoHide: 'leave',
      scrollbarClickScroll: true,
    };
    document.addEventListener('DOMContentLoaded', function() {
      const sidebarWrapper = document.querySelector(SELECTOR_SIDEBAR_WRAPPER);
      const isMobile = window.innerWidth <= 992;
      if (sidebarWrapper && OverlayScrollbarsGlobal?.OverlayScrollbars !== undefined && !isMobile) {
        OverlayScrollbarsGlobal.OverlayScrollbars(sidebarWrapper, {
          scrollbars: {
            theme: Default.scrollbarTheme,
            autoHide: Default.scrollbarAutoHide,
            clickScroll: Default.scrollbarClickScroll,
          },
        });
      }
    });
  </script>

  <!-- Font Awesome -->
  <script src="https://kit.fontawesome.com/4c0cbe7815.js" crossorigin="anonymous"></script>
  <!-- ========================== -->
   <!-- Sweet alert -->
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- JS externo  -->
  <script src="../../public/js/gestion_libros.js"></script>

   <!-- Jquery -->
  <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
  <!-- Fin sección: Scripts       -->
  <!-- ========================== -->

</body>
<!--end::Body-->

</html>