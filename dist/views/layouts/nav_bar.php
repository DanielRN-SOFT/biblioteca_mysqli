<?php

$IDusuario = $_SESSION["IDusuario"];
$nombreUsuario = $_SESSION["nombreUsuario"];
$apellidoUsuario = $_SESSION["apellidoUsuario"];
$tipoUsuario = $_SESSION["tipoUsuario"];

?>



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
                        <a href="#" class="nav-link">Home</a>
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
                            <span class="d-none d-md-inline"><?php echo $nombreUsuario . " " . $apellidoUsuario  ?></span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-end">
                            <!--begin::User Image-->
                            <li class="user-header bg-body-secondary">
                                <img
                                    src="../assets/img/profile.png"
                                    class="rounded-circle shadow"
                                    alt="User Image" />
                                <p>
                                    <?php echo $nombreUsuario . " " . $apellidoUsuario  ?>
                                    <small><?php echo $tipoUsuario ?></small>
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
                                <a href="../../controllers/logout.php" class="btn btn-danger text-light btn-flat float-end">Cerrar sesión</a>
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