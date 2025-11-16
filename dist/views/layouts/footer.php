<?php

if ($_SESSION["acceso"] == false || $_SESSION["acceso"] == null) {
    header("location: ./login.php");
} else {
    $_SESSION["acceso"] = true;
}

// Obtener el nombre del archivo actual
$archivoActual = basename($_SERVER["PHP_SELF"]);
?>



<!-- ========================== -->
<!-- Sección: Footer            -->
<!-- ========================== -->
<footer class="app-footer">
    <div class="float-end d-none d-sm-inline">Gestion de biblioteca</div>
    <strong>
        &copy; 2025
        <a href="#" class="text-decoration-none">bibliotecaMysqli.com</a>.
    </strong>
    Todos los derechos reservados.
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
<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.3/js/bootstrap.bundle.min.js"></script>

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

<!-- Jquery -->
<script src="https://code.jquery.com/jquery-3.7.1.js"></script>

<!-- Sweet alert -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<?php
// Incluir el script especifico en caso de que el archivo sea usuarios 
if ($archivoActual == "usuarios.php") {
?>
    <!-- JS externo  -->
    <script src="../../public/js/gestion_usuarios.js"></script>
<?php } ?>
<?php
// Incluir el script especifico en caso de que el archivo sea libros 
if ($archivoActual == "inventario.php") {
?>
    <!-- JS externo  -->
    <script src="../../public/js/gestion_libros.js"></script>
<?php } ?>
<?php
// Incluir el script especifico en caso de que el archivo sea libros 
if ($archivoActual == "categorias.php") {
?>
    <!-- JS externo  -->
    <script src="../../public/js/gestion_categorias.js"></script>
<?php } ?>

<?php
// Incluir el script especifico en caso de que el archivo sea reservas 
if ($archivoActual == "reservas.php") {
?>
    <!-- JS externo  -->
    <script src="../../public/js/gestion_reservas.js"></script>
<?php } ?>
<?php
// Incluir el script especifico en caso de que el archivo sea libros 
if ($archivoActual == "prestamos.php") {
?>
    <!-- JS externo  -->
    <script src="../../public/js/gestion_prestamos.js"></script>
<?php } ?>
<?php
// Incluir el script especifico en caso de que el archivo sea libros 
if ($archivoActual == "reportes.php") {
?>
    <!-- JS externo  -->
    <script src="../../public/js/PDF.js"></script>
<?php } ?>
<?php
// Incluir el script especifico en caso de que el archivo sea libros 
if ($archivoActual == "ingresarPerfil.php") {
?>
    <!-- JS externo  -->
    <script src="../../public/js/perfil.js"></script>
<?php } ?>

<?php
// Incluir el script especifico en caso de que el archivo sea PDF 
if ($archivoActual == "pdf.php") {
?>
    <!-- JS externo  -->
    <script src="../../public/js/PDF.js"></script>
<?php } ?>

<?php
// Incluir el script especifico en caso de que el archivo sea excel 
if ($archivoActual == "excel.php") {
?>
    <!-- JS externo  -->
    <script src="../../public/js/excel.js"></script>
<?php } ?>

<?php
// Incluir el script especifico en caso de que el archivo sea el dashboard 
if ($archivoActual == "dashboard.php") {
?>
    <!-- JS externo  -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <?php if ($tipoUsuario == "Administrador") { ?>
        <script src="../../public/js/grafico_libros_admin.js"></script>
        <script src="../../public/js/grafico_usuarios.js"></script>
        <script src="../../public/js/libros_vencidos.js"></script>
    <?php } ?>

    <?php if ($tipoUsuario == "Cliente") { ?>
        <script src="../../public/js/grafico_libros_cli.js"></script>
        <script src="../../public/js/libros_vencidos.js"></script>
    <?php } ?>


<?php } ?>







<!-- Datatables Script -->
<script src="../../public/js/datatables.js"></script>
<!-- BOOSTRAP 5 DATATABLES -->

<script src="https://cdn.datatables.net/2.3.4/js/dataTables.js"></script>
<script src="https://cdn.datatables.net/2.3.4/js/dataTables.bootstrap5.js"></script>
<script src="https://cdn.datatables.net/responsive/3.0.7/js/dataTables.responsive.js"></script>
<script src="https://cdn.datatables.net/responsive/3.0.7/js/responsive.bootstrap5.js"></script>

<!-- FixedHeader -->
<script src="https://cdn.datatables.net/fixedheader/4.0.4/js/dataTables.fixedHeader.js"></script>
<script src="https://cdn.datatables.net/fixedheader/4.0.4/js/fixedHeader.bootstrap5.js"></script>

<!-- Column Control -->
<script src="https://cdn.datatables.net/columncontrol/1.1.0/js/dataTables.columnControl.js"></script>
<script src="https://cdn.datatables.net/columncontrol/1.1.0/js/columnControl.dataTables.js"></script>

<!-- Select 2 -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
    document.getElementById("btnLogOut").addEventListener("click", () => {
        sessionStorage.clear(); // limpia todo lo guardado en la sesión
    });
</script>
<!-- Fin sección: Scripts       -->
<!-- ========================== -->

</body>
<!--end::Body-->

</html>