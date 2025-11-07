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

<body class="bg-login">

    <div class="bg-sombra min-vh-100 d-flex justify-content-center align-items-center">

        <div class="card shadow-lg rounded-4 p-4" style="max-width: 420px; width: 100%;">
            <div class="text-center mb-4">
                <img src="../assets/img/biblioteca.png" class="img-fluid w-25 rounded-pill shadow border-0" alt="">
                <h2 class="fw-bold mt-2 text-dark">Iniciar sesión</h2>
                <p class="text-muted">Accede con tus credenciales</p>
            </div>

            <form id="frmLogin" method="POST" autocomplete="off">

                <div class="form-floating mb-3">
                    <input type="email" class="form-control" id="email" name="email" placeholder="correo@ejemplo.com" required>
                    <label for="email">Email</label>
                </div>

                <div class="form-floating mb-4 position-relative">
                    <input type="password" class="form-control" id="password" name="password" placeholder="Contraseña" required>
                    <label for="password">Contraseña</label>

                    <button type="button" class="btn btn-sm btn-outline-secondary position-absolute top-50 end-0 translate-middle-y me-2"
                        onclick="togglePassword()">
                        <i class="fa-regular fa-eye"></i>
                    </button>
                </div>

                <button id="btn-acceder" class="btn btn-acceder w-100 fw-bold py-2 mb-3" type="submit">
                    <span id="btn-text"><i class="fa-solid fa-right-to-bracket"></i> Acceder</span>
                    <span id="btn-spinner" class="spinner-border spinner-border-sm d-none"></span>
                </button>

                <a href="./registrarse.php" class="btn btn-registrar w-100 fw-bold py-2">
                    <i class="fa-solid fa-user-plus"></i> Registrarse
                </a>
            </form>
        </div>

    </div>

    <!-- Bootstrap + FontAwesome + Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://kit.fontawesome.com/4c0cbe7815.js" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="../../public/js/login.js"></script>

    <script>
        // Mostrar / ocultar contraseña
        function togglePassword() {
            const input = document.getElementById("password");
            input.type = input.type === "password" ? "text" : "password";
        }

    </script>

</body>


</html>