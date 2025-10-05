<?php
// Se inicia la sesión para poder cerrarla correctamente
session_start();

// Se eliminan todas las variables de la sesión
session_unset();

// Se destruye completamente la sesión actual
session_destroy();

// Se redirige al login tras cerrar la sesión
header("Location: ../dist/views/login.php");
exit();
?>