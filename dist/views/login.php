<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Biblioteca | Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="stylesheet" href="../../public/css/style.css">
    <link rel="shortcut icon" href="../assets/img/biblioteca.png" type="image/x-icon">
</head>

<body>
    <div class="container-fluid">
        <main class="row  bg-login">
            <div class="col-12 bg-sombra">
                <form class="d-flex justify-content-center align-items-center min-vh-100 flex-column" action="" id="frmLogin" method="post">
                    <img src="../assets/img/biblioteca.png" class="img-fluid w-25" alt="">
                    <h1 class="fw-bold mb-4 display-5 text-light">Iniciar sesión</h1>

                    <div class="col-md-4 col-12">
                        <div class="form-floating mb-3">
                            <input
                                type="email"
                                class="form-control"
                                name="email"
                                id="email"
                                placeholder="" />
                            <label for="formId1">Email</label>
                        </div>

                        <div class="form-floating mb-3">
                            <input
                                type="password"
                                class="form-control"
                                name="password"
                                id="password"
                                placeholder="" />
                            <label for="password">Contraseña</label>
                        </div>

                        <button id="btn-acceder" class="btn btn-success w-100 fw-bold fs-5">Acceder</button>
                        <a href="" class="btn btn-primary my-3 w-100 fw-bold fs-5">Registrarse</a>
                    </div>
                </form>

            </div>



        </main>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
    <script src="../../public/js/login.js"></script>

    <!-- SCRIPTS externos -->

    <!-- Font Awesome -->
    <script src="https://kit.fontawesome.com/4c0cbe7815.js" crossorigin="anonymous"></script>
    <!-- ========================== -->

    <!-- Jquery -->
    <script src="https://code.jquery.com/jquery-3.7.1.js"></script>

    <!-- Sweet alert -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</body>

</html>