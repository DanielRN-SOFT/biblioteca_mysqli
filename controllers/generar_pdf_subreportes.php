<?php

require_once '../libs/fpdf/fpdf.php';
require_once './generarDatosReporte.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (
        isset($_POST["tipoInforme"]) && !empty($_POST["tipoInforme"])
        && isset($_POST["fechaInicio"]) && !empty($_POST["fechaInicio"])
        && isset($_POST["fechaFin"]) && !empty($_POST["fechaFin"])
        && isset($_POST["tipoInformeCategoria"]) && !empty($_POST["tipoInformeCategoria"])
    ) {
        // Asignacion de zona horaria para la hora del documento
        date_default_timezone_set('America/Bogota');
        // Captura de datos
        $fechaInicio = filter_var($_POST["fechaInicio"], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $fechaFin = filter_var($_POST["fechaFin"], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $tipoInformeDatos = filter_var($_POST["tipoInformeCategoria"], FILTER_SANITIZE_FULL_SPECIAL_CHARS);

        // Instancia de la clase de los datos del PDF
        $datosController = new generarDatosReporte();

        // CREACION PDF
        $pdf = new FPDF();
        $pdf->AliasNbPages(); // habilita {nb} para total de páginas
        $pdf->AddPage();
        $tipoInforme = filter_var($_POST["tipoInforme"], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $pdf->Image('../dist/assets/img/biblioteca.png', 10, 6, 20);
        $pdf->SetFont('Arial', 'B', 14);
        $pdf->Cell(0, 40, "Biblioteca");
        $pdf->SetXY(0, 30);

        // =======================================
        //! USUARIOS
        // =======================================

        if ($tipoInforme === 'Usuario') {

            // Obtener los datos según el tipo de subreporte
            $datos = $datosController->datosTipoUsuarios($fechaInicio, $fechaFin, $tipoInformeDatos);

            // Encabezado dinámico según el tipo de subreporte
            if ($tipoInformeDatos === "Usuarios con mas prestamos") {
                $pdf->Cell(0, 10, utf8_decode("Reporte: Usuarios con más préstamos"), 0, 1, 'C');
                $pdf->Ln(5);

                // Encabezado de tabla
                $pdf->SetFont('Arial', 'B', 10);
                $pdf->Cell(60, 10, "Nombre", 1);
                $pdf->Cell(60, 10, "Apellido", 1);
                $pdf->Cell(50, 10, "Total de Prestamos", 1);
                $pdf->Ln();

                // Datos del reporte
                $pdf->SetFont('Arial', '', 10);
                foreach ($datos as $dato) {
                    $pdf->Cell(60, 10, ($dato['nombre']), 1);
                    $pdf->Cell(60, 10, ($dato['apellido']), 1);
                    $pdf->Cell(50, 10, ($dato['total_prestamos']), 1);
                    $pdf->Ln();
                }

                $nombreArchivo = 'reporte_usuarios_mas_prestamos.pdf';
            } elseif ($tipoInformeDatos === "Usuarios con mas reservas") {
                $pdf->Cell(0, 10, utf8_decode("Reporte: Usuarios con más reservas"), 0, 1, 'C');
                $pdf->Ln(5);

                // Encabezado de tabla
                $pdf->SetFont('Arial', 'B', 10);
                $pdf->Cell(60, 10, "Nombre", 1);
                $pdf->Cell(60, 10, "Apellido", 1);
                $pdf->Cell(50, 10, "Total de Reservas", 1);
                $pdf->Ln();

                // Datos del reporte
                $pdf->SetFont('Arial', '', 10);
                foreach ($datos as $dato) {
                    $pdf->Cell(60, 10, ($dato['nombre']), 1);
                    $pdf->Cell(60, 10, ($dato['apellido']), 1);
                    $pdf->Cell(50, 10, ($dato['total_reservas']), 1);
                    $pdf->Ln();
                }

                $nombreArchivo = 'reporte_usuarios_mas_reservas.pdf';
            }

            // Pie de página
            $pdf->SetY(265);
            $pdf->SetFont('Arial', 'I', 9);
            $pdf->Cell(0, 10, utf8_decode('Elaborado por BibliotecaMySqli. Fecha de elaboración: ') . date('d/m/Y H:i'), 0, 0, 'C');
            $pdf->Cell(0, 10, utf8_decode('Página ') . $pdf->PageNo() . ' de {nb}', 0, 0, 'C');

            // Salida del PDF
            $pdf->Output('D', $nombreArchivo);
        }
        //  =======================================
        // ! INVENTARIO
        //  =======================================
        if ($tipoInforme === "Inventario") {
            $datos = $datosController->datosTipoInventario($fechaInicio, $fechaFin, $tipoInformeDatos);
            // Encabezado dinámico según el tipo de subreporte
            if ($tipoInformeDatos === "Libros Disponibles") {
                $pdf->Cell(0, 10, utf8_decode("Reporte: Libros Disponibles"), 0, 1, 'C');
                $pdf->Ln(5);

                // Encabezado de tabla
                $pdf->SetFont('Arial', 'B', 10);
                $pdf->Cell(70, 10, "Titulo", 1);
                $pdf->Cell(45, 10, "Autor", 1);
                $pdf->Cell(40, 10, "Fecha de creacion", 1);
                // $pdf->Cell(40, 10, "Categoria", 1);
                $pdf->Cell(20, 10, "Cantidad", 1);
                $pdf->Ln();

                // Datos del reporte
                $pdf->SetFont('Arial', '', 10);
                foreach ($datos as $dato) {
                    $pdf->Cell(70, 10, ($dato['titulo']), 1);
                    $pdf->Cell(45, 10, ($dato['autor']), 1);
                    $pdf->Cell(40, 10, ($dato['fecha_creacion']), 1);
                    // $pdf->Cell(40, 10, ($dato['categoria']), 1);
                    $pdf->Cell(20, 10, ($dato['cantidad']), 1);
                    $pdf->Ln();
                }

                $nombreArchivo = 'reporte_libros_disponibles.pdf';
            } elseif ($tipoInformeDatos === "Libros Prestados") {
                $pdf->Cell(0, 10, utf8_decode("Reporte: Libros Prestados"), 0, 1, 'C');
                $pdf->Ln(5);

                // Encabezado de tabla
                $pdf->SetFont('Arial', 'B', 10);
                $pdf->Cell(60, 10, "Titulo", 1);
                $pdf->Cell(60, 10, "Usuario", 1);
                $pdf->Cell(40, 10, "Fecha Prestamo", 1);
                $pdf->Ln();

                // Datos del reporte
                $pdf->SetFont('Arial', '', 10);
                foreach ($datos as $dato) {
                    $pdf->Cell(60, 10, ($dato['titulo_libro']), 1);
                    $pdf->Cell(60, 10, ($dato['usuario']), 1);
                    $pdf->Cell(40, 10, ($dato['fecha_prestamo']), 1);
                    $pdf->Ln();
                }

                $nombreArchivo = 'reporte_libros_prestados.pdf';
            } elseif ($tipoInformeDatos === "Libros Reservados") {
                $pdf->Cell(0, 10, utf8_decode("Reporte: Libros Reservados"), 0, 1, 'C');
                $pdf->Ln(5);

                // Encabezado de tabla
                $pdf->SetFont('Arial', 'B', 10);
                $pdf->Cell(60, 10, "Titulo", 1);
                $pdf->Cell(60, 10, "Usuario", 1);
                $pdf->Cell(40, 10, "Fecha Reserva", 1);
                $pdf->Ln();

                // Datos del reporte
                $pdf->SetFont('Arial', '', 10);
                foreach ($datos as $dato) {
                    $pdf->Cell(60, 10, ($dato['titulo_libro']), 1);
                    $pdf->Cell(60, 10, ($dato['usuario']), 1);
                    $pdf->Cell(40, 10, ($dato['fecha_reserva']), 1);
                    $pdf->Ln();
                }

                $nombreArchivo = 'reporte_libros_reservados.pdf';
            } elseif ($tipoInformeDatos === "Libros no Disponibles") {
                $pdf->Cell(0, 10, utf8_decode("Reporte: Libros no Disponibles"), 0, 1, 'C');
                $pdf->Ln(5);

                // Encabezado de tabla
                $pdf->SetFont('Arial', 'B', 10);
                $pdf->Cell(50, 10, "Titulo", 1);
                $pdf->Cell(45, 10, "Autor", 1);
                $pdf->Cell(40, 10, "Fecha de creacion", 1);
                // $pdf->Cell(40, 10, "Categoria", 1);
                $pdf->Cell(20, 10, "Cantidad", 1);
                $pdf->Ln();

                // Datos del reporte
                $pdf->SetFont('Arial', '', 10);
                foreach ($datos as $dato) {
                    $pdf->Cell(50, 10, ($dato['titulo']), 1);
                    $pdf->Cell(45, 10, ($dato['autor']), 1);
                    $pdf->Cell(40, 10, ($dato['fecha_creacion']), 1);
                    // $pdf->Cell(40, 10, ($dato['categoria']), 1);
                    $pdf->Cell(20, 10, ($dato['cantidad']), 1);
                    $pdf->Ln();
                }

                $nombreArchivo = 'reporte_libros_no_disponibles.pdf';
            }

            // Pie de página
            $pdf->SetY(265);
            $pdf->SetFont('Arial', 'I', 9);
            $pdf->Cell(0, 10, utf8_decode('Elaborado por BibliotecaMySqli. Fecha de elaboración: ') . date('d/m/Y H:i'), 0, 0, 'C');
            $pdf->Cell(0, 10, utf8_decode('Página ') . $pdf->PageNo() . ' de {nb}', 0, 0, 'C');

            // Salida del PDF
            $pdf->Output('D', $nombreArchivo);
        }
        // ======================================
        //! RESERVAS
        // ======================================

        if ($tipoInforme === "Reserva") {
            $datos = $datosController->datosTipoReservas($fechaInicio, $fechaFin, $tipoInformeDatos);
            // Encabezado dinámico según el tipo de subreporte
            if ($tipoInformeDatos === "Reservas Aprobadas") {
                $pdf->Cell(0, 10, utf8_decode("Reporte: Reservas Aprobadas"), 0, 1, 'C');
                $pdf->Ln(5);

                // Encabezado de tabla
                $pdf->SetFont('Arial', 'B', 10);
                $pdf->Cell(20, 10, "Reserva", 1);
                $pdf->Cell(60, 10, "Usuario", 1);
                $pdf->Cell(60, 10, "Libro", 1);
                $pdf->Cell(40, 10, "Fecha Reserva", 1);
                $pdf->Ln();

                // Datos del reporte
                $pdf->SetFont('Arial', '', 10);
                foreach ($datos as $dato) {
                    $pdf->Cell(20, 10, ($dato['id']), 1);
                    $pdf->Cell(60, 10, ($dato['usuario']), 1);
                    $pdf->Cell(60, 10, ($dato['titulo']), 1);
                    $pdf->Cell(40, 10, ($dato['fecha_reserva']), 1);
                    $pdf->Ln();
                }

                $nombreArchivo = 'reporte_reservas_aprobadas.pdf';
            } elseif ($tipoInformeDatos === "Reservas Rechazadas") { {
                    $pdf->Cell(0, 10, utf8_decode("Reporte: Reservas Rechazadas"), 0, 1, 'C');
                    $pdf->Ln(5);

                    // Encabezado de tabla
                    $pdf->SetFont('Arial', 'B', 10);
                    $pdf->Cell(20, 10, "Reserva", 1);
                    $pdf->Cell(60, 10, "Usuario", 1);
                    $pdf->Cell(60, 10, "Libro", 1);
                    $pdf->Cell(40, 10, "Fecha Reserva", 1);
                    $pdf->Ln();

                    // Datos del reporte
                    $pdf->SetFont('Arial', '', 10);
                    foreach ($datos as $dato) {
                        $pdf->Cell(20, 10, ($dato['id']), 1);
                        $pdf->Cell(60, 10, ($dato['usuario']), 1);
                        $pdf->Cell(60, 10, ($dato['titulo']), 1);
                        $pdf->Cell(40, 10, ($dato['fecha_reserva']), 1);
                        $pdf->Ln();
                    }

                    $nombreArchivo = 'reporte_reservas_rechazadas.pdf';
                }
            } elseif ($tipoInformeDatos === "Reservas Pendientes") {
                $pdf->Cell(0, 10, utf8_decode("Reporte: Reservas Pendientes"), 0, 1, 'C');
                $pdf->Ln(5);

                // Encabezado de tabla
                $pdf->SetFont('Arial', 'B', 10);
                $pdf->Cell(20, 10, "Reserva", 1);
                $pdf->Cell(60, 10, "Usuario", 1);
                $pdf->Cell(60, 10, "Libro", 1);
                $pdf->Cell(40, 10, "Fecha Reserva", 1);
                $pdf->Ln();

                // Datos del reporte
                $pdf->SetFont('Arial', '', 10);
                foreach ($datos as $dato) {
                    $pdf->Cell(20, 10, ($dato['id']), 1);
                    $pdf->Cell(60, 10, ($dato['usuario']), 1);
                    $pdf->Cell(60, 10, ($dato['titulo']), 1);
                    $pdf->Cell(40, 10, ($dato['fecha_reserva']), 1);
                    $pdf->Ln();
                }

                $nombreArchivo = 'reporte_reservas_pendientes.pdf';
            }elseif ($tipoInformeDatos === "Reservas Canceladas") {
                $pdf->Cell(0, 10, utf8_decode("Reporte: Reservas Pendientes"), 0, 1, 'C');
                $pdf->Ln(5);

                // Encabezado de tabla
                $pdf->SetFont('Arial', 'B', 10);
                $pdf->Cell(20, 10, "Reserva", 1);
                $pdf->Cell(60, 10, "Usuario", 1);
                $pdf->Cell(60, 10, "Libro", 1);
                $pdf->Cell(40, 10, "Fecha Reserva", 1);
                $pdf->Ln();

                // Datos del reporte
                $pdf->SetFont('Arial', '', 10);
                foreach ($datos as $dato) {
                    $pdf->Cell(20, 10, ($dato['id']), 1);
                    $pdf->Cell(60, 10, ($dato['usuario']), 1);
                    $pdf->Cell(60, 10, ($dato['titulo']), 1);
                    $pdf->Cell(40, 10, ($dato['fecha_reserva']), 1);
                    $pdf->Ln();
                }

                $nombreArchivo = 'reporte_reservas_canceladas.pdf';
            }

            // Pie de página
            $pdf->SetY(265);
            $pdf->SetFont('Arial', 'I', 9);
            $pdf->Cell(0, 10, utf8_decode('Elaborado por BibliotecaMySqli. Fecha de elaboración: ') . date('d/m/Y H:i'), 0, 0, 'C');
            $pdf->Cell(0, 10, utf8_decode('Página ') . $pdf->PageNo() . ' de {nb}', 0, 0, 'C');

            // Salida del PDF
            $pdf->Output('D', $nombreArchivo);
        }
        // ======================================
        //! PRESTAMOS
        // ====================================== 

        if ($tipoInforme === "Prestamo") {
            $datos = $datosController->datosTipoPrestamos($fechaInicio, $fechaFin, $tipoInformeDatos);
            // Encabezado dinámico según el tipo de subreporte
            if ($tipoInformeDatos === "Prestamos Activo") {
                $pdf->Cell(0, 10, utf8_decode("Reporte: Prestamos Activos"), 0, 1, 'C');
                $pdf->Ln(5);

                // Encabezado de tabla
                $pdf->SetFont('Arial', 'B', 10);
                $pdf->Cell(20, 10, "Prestamo", 1);
                $pdf->Cell(55, 10, "Usuario", 1);
                $pdf->Cell(50, 10, "Libro", 1);
                $pdf->Cell(35, 10, "Fecha Prestamo", 1);
                $pdf->Cell(35, 10, "Fecha Devolucion", 1);
                $pdf->Ln();

                // Datos del reporte
                $pdf->SetFont('Arial', '', 10);
                foreach ($datos as $dato) {
                    $pdf->Cell(20, 10, ($dato['id']), 1);
                    $pdf->Cell(55, 10, ($dato['usuario']), 1);
                    $pdf->Cell(50, 10, ($dato['titulo']), 1);
                    $pdf->Cell(35, 10, ($dato['fecha_prestamo']), 1);
                    $pdf->Cell(35, 10, ($dato['fecha_devolucion']), 1);
                    $pdf->Ln();
                }

                $nombreArchivo = 'reporte_prestamos_activos.pdf';
            } elseif ($tipoInformeDatos === "Prestamos Devuelto") { {
                    $pdf->Cell(0, 10, utf8_decode("Reporte: Prestamos Devueltos"), 0, 1, 'C');
                    $pdf->Ln(5);

                    // Encabezado de tabla
                    $pdf->SetFont('Arial', 'B', 10);
                    $pdf->Cell(20, 10, "Prestamo", 1);
                    $pdf->Cell(55, 10, "Usuario", 1);
                    $pdf->Cell(50, 10, "Libro", 1);
                    $pdf->Cell(35, 10, "Fecha Prestamo", 1);
                    $pdf->Cell(35, 10, "Fecha Devolucion", 1);
                    $pdf->Ln();

                    // Datos del reporte
                    $pdf->SetFont('Arial', '', 10);
                    foreach ($datos as $dato) {
                        $pdf->Cell(20, 10, ($dato['id']), 1);
                        $pdf->Cell(55, 10, ($dato['usuario']), 1);
                        $pdf->Cell(50, 10, ($dato['titulo']), 1);
                        $pdf->Cell(35, 10, ($dato['fecha_prestamo']), 1);
                        $pdf->Cell(35, 10, ($dato['fecha_devolucion']), 1);
                        $pdf->Ln();
                    }

                    $nombreArchivo = 'reporte_prestamos_devueltos.pdf';
                }
            }elseif ($tipoInformeDatos === "Prestamos Vencido") { {
                    $pdf->Cell(0, 10, utf8_decode("Reporte: Prestamos Vencidos"), 0, 1, 'C');
                    $pdf->Ln(5);

                    // Encabezado de tabla
                    $pdf->SetFont('Arial', 'B', 10);
                    $pdf->Cell(20, 10, "Prestamo", 1);
                    $pdf->Cell(55, 10, "Usuario", 1);
                    $pdf->Cell(50, 10, "Libro", 1);
                    $pdf->Cell(35, 10, "Fecha Prestamo", 1);
                    $pdf->Cell(35, 10, "Fecha Devolucion", 1);
                    $pdf->Ln();

                    // Datos del reporte
                    $pdf->SetFont('Arial', '', 10);
                    foreach ($datos as $dato) {
                        $pdf->Cell(20, 10, ($dato['id']), 1);
                        $pdf->Cell(55, 10, ($dato['usuario']), 1);
                        $pdf->Cell(50, 10, ($dato['titulo']), 1);
                        $pdf->Cell(35, 10, ($dato['fecha_prestamo']), 1);
                        $pdf->Cell(35, 10, ($dato['fecha_devolucion']), 1);
                        $pdf->Ln();
                    }

                    $nombreArchivo = 'reporte_prestamos_vencidos.pdf';
                }
            } elseif ($tipoInformeDatos === "Libros mas Prestados") {
                $pdf->Cell(0, 10, utf8_decode("Reporte: Libros mas Prestados"), 0, 1, 'C');
                $pdf->Ln(5);

                // Encabezado de tabla
                $pdf->SetFont('Arial', 'B', 10);
                $pdf->Cell(60, 10, "Libro", 1);
                $pdf->Cell(40, 10, "Total Prestamos", 1);
                $pdf->Ln();

                // Datos del reporte
                $pdf->SetFont('Arial', '', 10);
                foreach ($datos as $dato) {
                    $pdf->Cell(60, 10, ($dato['titulo']), 1);
                    $pdf->Cell(40, 10, ($dato['total_prestamos']), 1);
                    $pdf->Ln();
                }

                $nombreArchivo = 'reporte_libros_mas_prestados.pdf';
            }

            // Pie de página
            $pdf->SetY(265);
            $pdf->SetFont('Arial', 'I', 9);
            $pdf->Cell(0, 10, utf8_decode('Elaborado por BibliotecaMySqli. Fecha de elaboración: ') . date('d/m/Y H:i'), 0, 0, 'C');
            $pdf->Cell(0, 10, utf8_decode('Página ') . $pdf->PageNo() . ' de {nb}', 0, 0, 'C');

            // Salida del PDF
            $pdf->Output('D', $nombreArchivo);
        }
    }
}
