<!doctype html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Biblioteca | Registro</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../../public/css/style.css">
    <link rel="shortcut icon" href="../assets/img/biblioteca.png" type="image/x-icon">
</head>


<body class="bg-registrar">

    <div class="bg-sombra min-vh-100 d-flex justify-content-center align-items-center">

        <div class="card shadow-lg rounded-4 p-4" style="max-width: 420px; width: 100%;">
            <div class="text-center mb-4">
                <i class="fa-solid fa-user-plus fa-3x text-warning"></i>
                <h2 class="fw-bold mt-2 text-dark">Registrarse</h2>
                <p class="text-muted">Llena cada campo para completar el registro</p>
            </div>

            <form id="frmRegistrarse" method="POST">

                <div class="form-floating mb-3">
                    <input type="text" class="form-control" name="nombre" id="nombre" placeholder="" required>
                    <label for="nombre">Nombre</label>
                </div>

                <div class="form-floating mb-3">
                    <input type="text" class="form-control" name="apellido" id="apellido" placeholder="" required>
                    <label for="apellido">Apellido</label>
                </div>

                <div class="form-floating mb-3">
                    <input type="email" class="form-control" name="email" id="email" placeholder="" required>
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

                <button id="btn-registrarse" class="btn btn-registrar w-100 fw-bold py-2 mb-3" type="submit">
                    <span id="btn-text">
                        <i class="fa-solid fa-user-plus"></i> Registrarse</span>
                    <span id="btn-spinner" class="spinner-border spinner-border-sm d-none"></span>
                </button>

                <a href="./login.php" class="btn btn-acceder w-100 fw-bold py-2">
                    <i class="fa-solid fa-right-left"></i> Volver al login
                </a>
            </form>
        </div>
    </div>

    <!-- Bootstrap + FontAwesome + Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://kit.fontawesome.com/4c0cbe7815.js" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="../../public/js/registro.js"></script>

    <script>
        // Mostrar / ocultar contraseña
        function togglePassword() {
            const input = document.getElementById("password");
            input.type = input.type === "password" ? "text" : "password";
        }
    </script>

</body>


</html>