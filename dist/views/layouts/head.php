<?php
if ($_SESSION["acceso"] == false || $_SESSION["acceso"] == null) {
    header("location: ./login.php");
} else {
    $_SESSION["acceso"] = true;
}

$tipoUsuario = $_SESSION["tipoUsuario"];

// Obtener el nombre del archivo actual
$archivoActual = basename($_SERVER["PHP_SELF"]);

if (
    $tipoUsuario == "Cliente" && $archivoActual == "categorias.php"
    || $tipoUsuario == "Cliente" && $archivoActual == "pdf.php"
    || $tipoUsuario == "Cliente" && $archivoActual == "excel.php"
    || $tipoUsuario == "Cliente" && $archivoActual == "usuarios.php"
) {
    header("location: ./dashboard.php");
    exit();
}
?>

<!doctype html>
<html lang="en">
<!--begin::Head-->

<head>
    <!--begin::Meta-->
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Biblioteca | <?php echo $pagina ?></title>
    <!--end::Meta-->

    <!--begin::Preload CSS-->
    <link rel="preload" href="../css/adminlte.css" as="style" />
    <!--end::Preload CSS-->

    <!-- Favi.ico -->

    <link rel="shortcut icon" href="../assets/img/biblioteca.png" type="image/x-icon">

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

    <!-- CSS personal -->
    <link rel="stylesheet" href="../../public/css/style.css">
    <!-- END CSS personal -->

    <!-- Select 2 -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

    <!-- Link Datatables -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.3/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/2.3.4/css/dataTables.bootstrap5.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/3.0.7/css/responsive.bootstrap5.css">

    <!-- FixedHeader -->
    <link rel="stylesheet" href="https://cdn.datatables.net/fixedheader/4.0.4/css/fixedHeader.bootstrap5.css">

    <!-- ColumnControl -->
    <link rel="stylesheet" href="https://cdn.datatables.net/columncontrol/1.1.0/css/columnControl.dataTables.css">



</head>
<!--end::Head-->