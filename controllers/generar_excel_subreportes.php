<?php

require '../libs/excel/vendor/autoload.php';
require './generarDatosReporte.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Cell\DataType;

// Instanciar la clase 
$datosController = new generarDatosReporte();

// Capturar los datos
$tipoInforme = filter_var($_POST["tipoInforme"], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$fechaInicio = filter_var($_POST["fechaInicio"], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$fechaFin = filter_var($_POST["fechaFin"], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$tipoInformeDatos = filter_var($_POST["tipoInformeCategoria"], FILTER_SANITIZE_FULL_SPECIAL_CHARS);

// Crear un nuevo libro
$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();
$sheet->setTitle('Reporte_de_' . $tipoInforme);


// ==========================
//! USUARIOS
// ==========================
if ($tipoInforme === "usuarios") {
    // Obtener los datos según el tipo de subreporte
    $datos = $datosController->datosTipoUsuarios($fechaInicio, $fechaFin, $tipoInformeDatos);
    if ($tipoInformeDatos === "Usuarios con mas prestamos") {
        //  TITULO DEL REPORTE
        $sheet->mergeCells('A1:C1');
        $sheet->setCellValue('A1', 'REPORTE DE ' . strtoupper($tipoInformeDatos));
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
        $sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // FECHA DEL REPORTE
        $sheet->setCellValue('A2', 'Fecha de generación: ' . date('d/m/Y'));
        $sheet->mergeCells('A2:C2');
        $sheet->getStyle('A2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);


        // CABECERA DE LA TABLA
        $encabezados = ['Nombre', 'Apellido', 'Total de Prestamos'];
        $columna = 'A';
        foreach ($encabezados as $enc) {
            $sheet->setCellValue($columna . '4', $enc);
            $sheet->getStyle($columna . '4')->getFont()->setBold(true);
            $sheet->getStyle($columna . '4')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle($columna . '4')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('7389FF');
            $columna++;
        }

        // CONTENIDO DE LA TABLA
        $fila = 5;
        foreach ($datos as $dato) {
            $sheet->setCellValue('A' . $fila, $dato["nombre"]);
            $sheet->setCellValue('B' . $fila, $dato["apellido"]);
            $sheet->setCellValue('C' . $fila, $dato["total_prestamos"]);
            $fila++;
        }

        // FORMATO DE LA TABLA
        $ultimaFila = $fila - 1;
        $rango = "A4:C{$ultimaFila}";

        // BORDES DE LA TABLA
        $sheet->getStyle($rango)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);

        // Alinear texto al centro
        $sheet->getStyle($rango)
            ->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // Autoajustar columnas
        foreach (range('A', 'C') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }
    } elseif ($tipoInformeDatos === "Usuarios con mas reservas") {
        //  TITULO DEL REPORTE
        $sheet->mergeCells('A1:C1');
        $sheet->setCellValue('A1', 'REPORTE DE ' . strtoupper($tipoInformeDatos));
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
        $sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // FECHA DEL REPORTE
        $sheet->setCellValue('A2', 'Fecha de generación: ' . date('d/m/Y'));
        $sheet->mergeCells('A2:C2');
        $sheet->getStyle('A2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);


        // CABECERA DE LA TABLA
        $encabezados = ['Nombre', 'Apellido', 'Total de Reservas'];
        $columna = 'A';
        foreach ($encabezados as $enc) {
            $sheet->setCellValue($columna . '4', $enc);
            $sheet->getStyle($columna . '4')->getFont()->setBold(true);
            $sheet->getStyle($columna . '4')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle($columna . '4')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('7389FF');
            $columna++;
        }

        // CONTENIDO DE LA TABLA
        $fila = 5;
        foreach ($datos as $dato) {
            $sheet->setCellValue('A' . $fila, $dato["nombre"]);
            $sheet->setCellValue('B' . $fila, $dato["apellido"]);
            $sheet->setCellValue('C' . $fila, $dato["total_reservas"]);
            $fila++;
        }

        // FORMATO DE LA TABLA
        $ultimaFila = $fila - 1;
        $rango = "A4:C{$ultimaFila}";

        // BORDES DE LA TABLA
        $sheet->getStyle($rango)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);

        // Alinear texto al centro
        $sheet->getStyle($rango)
            ->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // Autoajustar columnas
        foreach (range('A', 'C') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }
    }
}

// ==========================
//! INVENTARIO
// ==========================

if ($tipoInforme === "inventario") {
    $datos = $datosController->datosTipoInventario($fechaInicio, $fechaFin, $tipoInformeDatos);
    if ($tipoInformeDatos === "Libros Disponibles") {
        //  TITULO DEL REPORTE
        $sheet->mergeCells('A1:D1');
        $sheet->setCellValue('A1', 'REPORTE DE ' . strtoupper($tipoInformeDatos));
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
        $sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // FECHA DEL REPORTE
        $sheet->setCellValue('A2', 'Fecha de generación: ' . date('d/m/Y'));
        $sheet->mergeCells('A2:D2');
        $sheet->getStyle('A2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);


        // CABECERA DE LA TABLA
        $encabezados = ['Titulo', 'Autor', 'Categoria', 'Cantidad'];
        $columna = 'A';
        foreach ($encabezados as $enc) {
            $sheet->setCellValue($columna . '4', $enc);
            $sheet->getStyle($columna . '4')->getFont()->setBold(true);
            $sheet->getStyle($columna . '4')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle($columna . '4')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('7389FF');
            $columna++;
        }

        // CONTENIDO DE LA TABLA
        $fila = 5;
        foreach ($datos as $dato) {
            $sheet->setCellValue('A' . $fila, $dato["titulo"]);
            $sheet->setCellValue('B' . $fila, $dato["autor"]);
            $sheet->setCellValue('C' . $fila, $dato["categoria"]);
            $sheet->setCellValue('D' . $fila, $dato["cantidad"]);
            $fila++;
        }

        // FORMATO DE LA TABLA
        $ultimaFila = $fila - 1;
        $rango = "A4:D{$ultimaFila}";

        // BORDES DE LA TABLA
        $sheet->getStyle($rango)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);

        // Alinear texto al centro
        $sheet->getStyle($rango)
            ->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // Autoajustar columnas
        foreach (range('A', 'D') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }
    } elseif ($tipoInformeDatos === "Libros Prestados") {
        //  TITULO DEL REPORTE
        $sheet->mergeCells('A1:C1');
        $sheet->setCellValue('A1', 'REPORTE DE ' . strtoupper($tipoInformeDatos));
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
        $sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // FECHA DEL REPORTE
        $sheet->setCellValue('A2', 'Fecha de generación: ' . date('d/m/Y'));
        $sheet->mergeCells('A2:C2');
        $sheet->getStyle('A2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);


        // CABECERA DE LA TABLA
        $encabezados = ['Titulo', 'Usuario', 'Fecha de Prestamo'];
        $columna = 'A';
        foreach ($encabezados as $enc) {
            $sheet->setCellValue($columna . '4', $enc);
            $sheet->getStyle($columna . '4')->getFont()->setBold(true);
            $sheet->getStyle($columna . '4')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle($columna . '4')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('7389FF');
            $columna++;
        }

        // CONTENIDO DE LA TABLA
        $fila = 5;
        foreach ($datos as $dato) {
            $sheet->setCellValue('A' . $fila, $dato["titulo_libro"]);
            $sheet->setCellValue('B' . $fila, $dato["usuario"]);
            $sheet->setCellValue('C' . $fila, $dato["fecha_prestamo"]);
            $fila++;
        }

        // FORMATO DE LA TABLA
        $ultimaFila = $fila - 1;
        $rango = "A4:C{$ultimaFila}";

        // BORDES DE LA TABLA
        $sheet->getStyle($rango)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);

        // Alinear texto al centro
        $sheet->getStyle($rango)
            ->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // Autoajustar columnas
        foreach (range('A', 'C') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }
    } elseif ($tipoInformeDatos === "Libros Reservados") {
        //  TITULO DEL REPORTE
        $sheet->mergeCells('A1:C1');
        $sheet->setCellValue('A1', 'REPORTE DE ' . strtoupper($tipoInformeDatos));
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
        $sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // FECHA DEL REPORTE
        $sheet->setCellValue('A2', 'Fecha de generación: ' . date('d/m/Y'));
        $sheet->mergeCells('A2:C2');
        $sheet->getStyle('A2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);


        // CABECERA DE LA TABLA
        $encabezados = ['Titulo', 'Usuario', 'Fecha de Reserva'];
        $columna = 'A';
        foreach ($encabezados as $enc) {
            $sheet->setCellValue($columna . '4', $enc);
            $sheet->getStyle($columna . '4')->getFont()->setBold(true);
            $sheet->getStyle($columna . '4')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle($columna . '4')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('7389FF');
            $columna++;
        }

        // CONTENIDO DE LA TABLA
        $fila = 5;
        foreach ($datos as $dato) {
            $sheet->setCellValue('A' . $fila, $dato["titulo_libro"]);
            $sheet->setCellValue('B' . $fila, $dato["usuario"]);
            $sheet->setCellValue('C' . $fila, $dato["fecha_reserva"]);
            $fila++;
        }

        // FORMATO DE LA TABLA
        $ultimaFila = $fila - 1;
        $rango = "A4:C{$ultimaFila}";

        // BORDES DE LA TABLA
        $sheet->getStyle($rango)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);

        // Alinear texto al centro
        $sheet->getStyle($rango)
            ->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // Autoajustar columnas
        foreach (range('A', 'C') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }
    } elseif ($tipoInformeDatos === "Libros no Disponibles") {
        //  TITULO DEL REPORTE
        $sheet->mergeCells('A1:D1');
        $sheet->setCellValue('A1', 'REPORTE DE ' . strtoupper($tipoInformeDatos));
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
        $sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // FECHA DEL REPORTE
        $sheet->setCellValue('A2', 'Fecha de generación: ' . date('d/m/Y'));
        $sheet->mergeCells('A2:D2');
        $sheet->getStyle('A2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);


        // CABECERA DE LA TABLA
        $encabezados = ['Titulo', 'Autor', 'Categoria', 'Cantidad'];
        $columna = 'A';
        foreach ($encabezados as $enc) {
            $sheet->setCellValue($columna . '4', $enc);
            $sheet->getStyle($columna . '4')->getFont()->setBold(true);
            $sheet->getStyle($columna . '4')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle($columna . '4')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('7389FF');
            $columna++;
        }

        // CONTENIDO DE LA TABLA
        $fila = 5;
        foreach ($datos as $dato) {
            $sheet->setCellValue('A' . $fila, $dato["titulo"]);
            $sheet->setCellValue('B' . $fila, $dato["autor"]);
            $sheet->setCellValue('C' . $fila, $dato["categoria"]);
            $sheet->setCellValue('D' . $fila, $dato["cantidad"]);
            $fila++;
        }

        // FORMATO DE LA TABLA
        $ultimaFila = $fila - 1;
        $rango = "A4:D{$ultimaFila}";

        // BORDES DE LA TABLA
        $sheet->getStyle($rango)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);

        // Alinear texto al centro
        $sheet->getStyle($rango)
            ->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // Autoajustar columnas
        foreach (range('A', 'D') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }
    }
}

// ==========================
//! RESERVAS
// ==========================
if ($tipoInforme === "reservas") {
    $datos = $datosController->datosTipoReservas($fechaInicio, $fechaFin, $tipoInformeDatos);
    if ($tipoInformeDatos === "Reservas Aprobadas") {
        //  TITULO DEL REPORTE
        $sheet->mergeCells('A1:D1');
        $sheet->setCellValue('A1', 'REPORTE DE ' . strtoupper($tipoInformeDatos));
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
        $sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // FECHA DEL REPORTE
        $sheet->setCellValue('A2', 'Fecha de generación: ' . date('d/m/Y'));
        $sheet->mergeCells('A2:D2');
        $sheet->getStyle('A2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);


        // CABECERA DE LA TABLA
        $encabezados = ['Rserva', 'Usuario', 'Libro', 'Fecha de Reserva'];
        $columna = 'A';
        foreach ($encabezados as $enc) {
            $sheet->setCellValue($columna . '4', $enc);
            $sheet->getStyle($columna . '4')->getFont()->setBold(true);
            $sheet->getStyle($columna . '4')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle($columna . '4')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('7389FF');
            $columna++;
        }

        // CONTENIDO DE LA TABLA
        $fila = 5;
        foreach ($datos as $dato) {
            $sheet->setCellValue('A' . $fila, $dato["id"]);
            $sheet->setCellValue('B' . $fila, $dato["usuario"]);
            $sheet->setCellValue('C' . $fila, $dato["titulo"]);
            $sheet->setCellValue('D' . $fila, $dato["fecha_reserva"]);
            $fila++;
        }

        // FORMATO DE LA TABLA
        $ultimaFila = $fila - 1;
        $rango = "A4:D{$ultimaFila}";

        // BORDES DE LA TABLA
        $sheet->getStyle($rango)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);

        // Alinear texto al centro
        $sheet->getStyle($rango)
            ->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // Autoajustar columnas
        foreach (range('A', 'D') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }
    } elseif ($tipoInformeDatos === "Reservas Rechazadas") {
        //  TITULO DEL REPORTE
        $sheet->mergeCells('A1:D1');
        $sheet->setCellValue('A1', 'REPORTE DE ' . strtoupper($tipoInformeDatos));
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
        $sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // FECHA DEL REPORTE
        $sheet->setCellValue('A2', 'Fecha de generación: ' . date('d/m/Y'));
        $sheet->mergeCells('A2:D2');
        $sheet->getStyle('A2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);


        // CABECERA DE LA TABLA
        $encabezados = ['Rserva', 'Usuario', 'Libro', 'Fecha de Reserva'];
        $columna = 'A';
        foreach ($encabezados as $enc) {
            $sheet->setCellValue($columna . '4', $enc);
            $sheet->getStyle($columna . '4')->getFont()->setBold(true);
            $sheet->getStyle($columna . '4')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle($columna . '4')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('7389FF');
            $columna++;
        }

        // CONTENIDO DE LA TABLA
        $fila = 5;
        foreach ($datos as $dato) {
            $sheet->setCellValue('A' . $fila, $dato["id"]);
            $sheet->setCellValue('B' . $fila, $dato["usuario"]);
            $sheet->setCellValue('C' . $fila, $dato["titulo"]);
            $sheet->setCellValue('D' . $fila, $dato["fecha_reserva"]);
            $fila++;
        }

        // FORMATO DE LA TABLA
        $ultimaFila = $fila - 1;
        $rango = "A4:D{$ultimaFila}";

        // BORDES DE LA TABLA
        $sheet->getStyle($rango)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);

        // Alinear texto al centro
        $sheet->getStyle($rango)
            ->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // Autoajustar columnas
        foreach (range('A', 'D') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }
    } elseif ($tipoInformeDatos === "Reservas Pendientes") {
        //  TITULO DEL REPORTE
        $sheet->mergeCells('A1:D1');
        $sheet->setCellValue('A1', 'REPORTE DE ' . strtoupper($tipoInformeDatos));
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
        $sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // FECHA DEL REPORTE
        $sheet->setCellValue('A2', 'Fecha de generación: ' . date('d/m/Y'));
        $sheet->mergeCells('A2:D2');
        $sheet->getStyle('A2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);


        // CABECERA DE LA TABLA
        $encabezados = ['Rserva', 'Usuario', 'Libro', 'Fecha de Reserva'];
        $columna = 'A';
        foreach ($encabezados as $enc) {
            $sheet->setCellValue($columna . '4', $enc);
            $sheet->getStyle($columna . '4')->getFont()->setBold(true);
            $sheet->getStyle($columna . '4')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle($columna . '4')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('7389FF');
            $columna++;
        }

        // CONTENIDO DE LA TABLA
        $fila = 5;
        foreach ($datos as $dato) {
            $sheet->setCellValue('A' . $fila, $dato["id"]);
            $sheet->setCellValue('B' . $fila, $dato["usuario"]);
            $sheet->setCellValue('C' . $fila, $dato["titulo"]);
            $sheet->setCellValue('D' . $fila, $dato["fecha_reserva"]);
            $fila++;
        }

        // FORMATO DE LA TABLA
        $ultimaFila = $fila - 1;
        $rango = "A4:D{$ultimaFila}";

        // BORDES DE LA TABLA
        $sheet->getStyle($rango)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);

        // Alinear texto al centro
        $sheet->getStyle($rango)
            ->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // Autoajustar columnas
        foreach (range('A', 'D') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }
    } elseif ($tipoInformeDatos === "Reservas Canceladas") {
        //  TITULO DEL REPORTE
        $sheet->mergeCells('A1:D1');
        $sheet->setCellValue('A1', 'REPORTE DE ' . strtoupper($tipoInformeDatos));
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
        $sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // FECHA DEL REPORTE
        $sheet->setCellValue('A2', 'Fecha de generación: ' . date('d/m/Y'));
        $sheet->mergeCells('A2:D2');
        $sheet->getStyle('A2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);


        // CABECERA DE LA TABLA
        $encabezados = ['Rserva', 'Usuario', 'Libro', 'Fecha de Reserva'];
        $columna = 'A';
        foreach ($encabezados as $enc) {
            $sheet->setCellValue($columna . '4', $enc);
            $sheet->getStyle($columna . '4')->getFont()->setBold(true);
            $sheet->getStyle($columna . '4')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle($columna . '4')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('7389FF');
            $columna++;
        }

        // CONTENIDO DE LA TABLA
        $fila = 5;
        foreach ($datos as $dato) {
            $sheet->setCellValue('A' . $fila, $dato["id"]);
            $sheet->setCellValue('B' . $fila, $dato["usuario"]);
            $sheet->setCellValue('C' . $fila, $dato["titulo"]);
            $sheet->setCellValue('D' . $fila, $dato["fecha_reserva"]);
            $fila++;
        }

        // FORMATO DE LA TABLA
        $ultimaFila = $fila - 1;
        $rango = "A4:D{$ultimaFila}";

        // BORDES DE LA TABLA
        $sheet->getStyle($rango)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);

        // Alinear texto al centro
        $sheet->getStyle($rango)
            ->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // Autoajustar columnas
        foreach (range('A', 'D') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }
    }
}
// ==========================
//! PRESTAMOS
// ==========================
if ($tipoInforme === "prestamos") {
    $datos = $datosController->datosTipoPrestamos($fechaInicio, $fechaFin, $tipoInformeDatos);
    if ($tipoInformeDatos === "Prestamos Activo") {
        //  TITULO DEL REPORTE
        $sheet->mergeCells('A1:E1');
        $sheet->setCellValue('A1', 'REPORTE DE ' . strtoupper($tipoInformeDatos));
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
        $sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // FECHA DEL REPORTE
        $sheet->setCellValue('A2', 'Fecha de generación: ' . date('d/m/Y'));
        $sheet->mergeCells('A2:E2');
        $sheet->getStyle('A2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);


        // CABECERA DE LA TABLA
        $encabezados = ['Prestamo', 'Usuario', 'Libro', 'Fecha de Prestamo', 'Fecha de Devolución'];
        $columna = 'A';
        foreach ($encabezados as $enc) {
            $sheet->setCellValue($columna . '4', $enc);
            $sheet->getStyle($columna . '4')->getFont()->setBold(true);
            $sheet->getStyle($columna . '4')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle($columna . '4')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('7389FF');
            $columna++;
        }

        // CONTENIDO DE LA TABLA
        $fila = 5;
        foreach ($datos as $dato) {
            $sheet->setCellValue('A' . $fila, $dato["id"]);
            $sheet->setCellValue('B' . $fila, $dato["usuario"]);
            $sheet->setCellValue('C' . $fila, $dato["titulo"]);
            $sheet->setCellValue('D' . $fila, $dato["fecha_prestamo"]);
            $sheet->setCellValue('E' . $fila, $dato["fecha_devolucion"]);
            $fila++;
        }

        // FORMATO DE LA TABLA
        $ultimaFila = $fila - 1;
        $rango = "A4:E{$ultimaFila}";

        // BORDES DE LA TABLA
        $sheet->getStyle($rango)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);

        // Alinear texto al centro
        $sheet->getStyle($rango)
            ->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // Autoajustar columnas
        foreach (range('A', 'E') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }
    } elseif ($tipoInformeDatos === "Prestamos Devuelto") {
        //  TITULO DEL REPORTE
        $sheet->mergeCells('A1:E1');
        $sheet->setCellValue('A1', 'REPORTE DE ' . strtoupper($tipoInformeDatos));
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
        $sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // FECHA DEL REPORTE
        $sheet->setCellValue('A2', 'Fecha de generación: ' . date('d/m/Y'));
        $sheet->mergeCells('A2:E2');
        $sheet->getStyle('A2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);


        // CABECERA DE LA TABLA
        $encabezados = ['Prestamo', 'Usuario', 'Libro', 'Fecha de Prestamo', 'Fecha de Devolución'];
        $columna = 'A';
        foreach ($encabezados as $enc) {
            $sheet->setCellValue($columna . '4', $enc);
            $sheet->getStyle($columna . '4')->getFont()->setBold(true);
            $sheet->getStyle($columna . '4')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle($columna . '4')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('7389FF');
            $columna++;
        }

        // CONTENIDO DE LA TABLA
        $fila = 5;
        foreach ($datos as $dato) {
            $sheet->setCellValue('A' . $fila, $dato["id"]);
            $sheet->setCellValue('B' . $fila, $dato["usuario"]);
            $sheet->setCellValue('C' . $fila, $dato["titulo"]);
            $sheet->setCellValue('D' . $fila, $dato["fecha_prestamo"]);
            $sheet->setCellValue('E' . $fila, $dato["fecha_devolucion"]);
            $fila++;
        }

        // FORMATO DE LA TABLA
        $ultimaFila = $fila - 1;
        $rango = "A4:E{$ultimaFila}";

        // BORDES DE LA TABLA
        $sheet->getStyle($rango)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);

        // Alinear texto al centro
        $sheet->getStyle($rango)
            ->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // Autoajustar columnas
        foreach (range('A', 'E') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }
    }elseif($tipoInformeDatos === "Libros mas Prestados"){
        //  TITULO DEL REPORTE
        $sheet->mergeCells('A1:B1');
        $sheet->setCellValue('A1', 'REPORTE DE ' . strtoupper($tipoInformeDatos));
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
        $sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // FECHA DEL REPORTE
        $sheet->setCellValue('A2', 'Fecha de generación: ' . date('d/m/Y'));
        $sheet->mergeCells('A2:B2');
        $sheet->getStyle('A2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);


        // CABECERA DE LA TABLA
        $encabezados = ['Libro', 'Total de Prestamos'];
        $columna = 'A';
        foreach ($encabezados as $enc) {
            $sheet->setCellValue($columna . '4', $enc);
            $sheet->getStyle($columna . '4')->getFont()->setBold(true);
            $sheet->getStyle($columna . '4')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle($columna . '4')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('7389FF');
            $columna++;
        }

        // CONTENIDO DE LA TABLA
        $fila = 5;
        foreach ($datos as $dato) {
            $sheet->setCellValue('A' . $fila, $dato["titulo"]);
            $sheet->setCellValue('B' . $fila, $dato["total_prestamos"]);
            $fila++;
        }

        // FORMATO DE LA TABLA
        $ultimaFila = $fila - 1;
        $rango = "A4:B{$ultimaFila}";

        // BORDES DE LA TABLA
        $sheet->getStyle($rango)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);

        // Alinear texto al centro
        $sheet->getStyle($rango)
            ->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // Autoajustar columnas
        foreach (range('A', 'B') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }
    }
}


// DESCARGAR EL ARCHIVO
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="reporte_' . $tipoInformeDatos . date("d/m/Y H:i:s") . '.xlsx"');
header('Cache-Control: max-age=0');

$writer = new Xlsx($spreadsheet);
$writer->save('php://output');
exit;
