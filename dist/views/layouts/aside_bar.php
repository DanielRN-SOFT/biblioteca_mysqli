<?php
// Obtener el nombre del archivo
$archivoActual = basename($_SERVER["PHP_SELF"]);

// Tipo de usuario
$tipoUsuario = $_SESSION["tipoUsuario"];
?>
<!-- Sección: Sidebar           -->
<!-- ========================== -->
<aside class="app-sidebar bg-nav-bar shadow" data-bs-theme="dark">
    <!--begin::Sidebar Brand-->
    <div class="sidebar-brand">
        <a href="./dashboard.php" class="brand-link">
            <img src="../assets/img/biblioteca.png" alt="Biblioteca LOGO" class="brand-image opacity-75 shadow" />
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
                    <a href="./dashboard.php" class="nav-link <?php echo ($archivoActual == "dashboard.php" ? "active" : "") ?>">
                        <i class="fa-solid fa-business-time"></i>
                        <p>Dashboard</p>
                    </a>
                </li>
                <!-- Fin de dasboard principal -->
                <!-- Menú de información -->
                <li class="nav-item menu-open">
                    <a href="#" class="nav-link active">
                        <i class="fa-solid fa-table-list"></i>
                        <p>Información <i class="nav-arrow bi bi-chevron-right"></i></p>
                    </a>
                    <ul class="nav nav-treeview">
                        <?php if ($tipoUsuario == "Administrador") { ?>
                            <li class="nav-item">
                                <a href="./usuarios.php" class="nav-link <?php echo ($archivoActual == "usuarios.php" ? "active" : "") ?>">
                                    <i class="fa-solid fa-users"></i>
                                    <p>Usuarios</p>
                                </a>
                            </li>
                        <?php } ?>
                      
                            <li class="nav-item">
                                <a href="./inventario.php" class="nav-link <?php echo ($archivoActual == "inventario.php" ? "active" : "") ?>">
                                    <i class="fa-solid fa-book"></i>
                                    <p>Libros</p>
                                </a>
                            </li>
                     

                        <li class="nav-item">
                            <a href="./reservas.php" class="nav-link <?php echo ($archivoActual == "reservas.php" ? 'active' : "") ?>">
                                <i class="fa-solid fa-calendar-days"></i>
                                <p>Reservas</p>
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