<?php

require '../libs/vendor/autoload.php';
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

// Crear un nuevo libro
$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();
$sheet->setTitle('Reporte_de_' . $tipoInforme);



//==============================
// USUARIOS
//=============================

if ($tipoInforme == "usuarios") {
    // Obtener los datos para el reporte
    $datos = $datosController->datosUsuario($fechaInicio, $fechaFin);

    //  TITULO DEL REPORTE
    $sheet->mergeCells('A1:F1');
    $sheet->setCellValue('A1', 'REPORTE DE ' . strtoupper($tipoInforme));
    $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
    $sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

    // FECHA DEL REPORTE
    $sheet->setCellValue('A2', 'Fecha de generación: ' . date('d/m/Y'));
    $sheet->mergeCells('A2:F2');
    $sheet->getStyle('A2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);


    // CABECERA DE LA TABLA
    $encabezados = ['Nombre', 'Apellido', 'Email', 'Tipo', 'Estado', 'Fecha de creacion'];
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
        $sheet->setCellValue('C' . $fila, $dato["email"]);
        $sheet->setCellValue('D' . $fila, $dato["tipo"]);
        $sheet->setCellValue('E' . $fila, $dato["estado"]);
        $sheet->setCellValue('F' . $fila, $dato["fecha_creacion"]);
        $fila++;
    }

    // FORMATO DE LA TABLA
    $ultimaFila = $fila - 1;
    $rango = "A4:F{$ultimaFila}";

    // BORDES DE LA TABLA
    $sheet->getStyle($rango)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);

    // Alinear texto al centro
    $sheet->getStyle($rango)
        ->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

    // Autoajustar columnas
    foreach (range('A', 'F') as $col) {
        $sheet->getColumnDimension($col)->setAutoSize(true);
    }
}

//==============================
// RESERVAS
//=============================

if ($tipoInforme == "reservas") {
    // Obtener los datos para el reporte
    $datos = $datosController->datosReserva($fechaInicio, $fechaFin);

    //  TITULO DEL REPORTE
    $sheet->mergeCells('A1:F1');
    $sheet->setCellValue('A1', 'REPORTE DE ' . strtoupper($tipoInforme));
    $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
    $sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

    // FECHA DEL REPORTE
    $sheet->setCellValue('A2', 'Fecha de generación: ' . date('d/m/Y'));
    $sheet->mergeCells('A2:F2');
    $sheet->getStyle('A2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);


    // CABECERA DE LA TABLA
    $encabezados = ['ID', 'Nombre', 'Apellido', 'Libro', 'Estado', 'Fecha'];
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
        $sheet->setCellValue('A' . $fila, $dato["reserva_id"]);
        $sheet->setCellValue('B' . $fila, $dato["nombre"]);
        $sheet->setCellValue('C' . $fila, $dato["apellido"]);
        $sheet->setCellValue('D' . $fila, $dato["titulo"]);
        $sheet->setCellValue('E' . $fila, $dato["estado"]);
        $sheet->setCellValue('F' . $fila, $dato["fecha_reserva"]);
        $fila++;
    }

    // FORMATO DE LA TABLA
    $ultimaFila = $fila - 1;
    $rango = "A4:F{$ultimaFila}";

    // BORDES DE LA TABLA
    $sheet->getStyle($rango)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);

    // Alinear texto al centro
    $sheet->getStyle($rango)
        ->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

    // Autoajustar columnas
    foreach (range('A', 'F') as $col) {
        $sheet->getColumnDimension($col)->setAutoSize(true);
    }
}
//==============================
// INVENTARIO
//=============================
if ($tipoInforme == "inventario") {
    // Obtener los datos para el reporte
    $datos = $datosController->datosInventario($fechaInicio, $fechaFin);

    //  TITULO DEL REPORTE
    $sheet->mergeCells('A1:G1');
    $sheet->setCellValue('A1', 'REPORTE DE ' . strtoupper($tipoInforme));
    $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
    $sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

    // FECHA DEL REPORTE
    $sheet->setCellValue('A2', 'Fecha de generación: ' . date('d/m/Y'));
    $sheet->mergeCells('A2:G2');
    $sheet->getStyle('A2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);


    // CABECERA DE LA TABLA
    $encabezados = ['Titulo', 'Autor', 'ISBN', 'Categoria', 'Disponibilidad', 'Cantidad', 'Fecha'];
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
        $sheet->setCellValueExplicit('C' . $fila, $dato["ISBN"], DataType::TYPE_STRING);
        $sheet->setCellValue('D' . $fila, $dato["categoria"]);
        $sheet->setCellValue('E' . $fila, $dato["disponibilidad"]);
        $sheet->setCellValue('F' . $fila, $dato["cantidad"]);
        $sheet->setCellValue('G' . $fila, $dato["fecha_creacion"]);
        $fila++;
    }

    // FORMATO DE LA TABLA
    $ultimaFila = $fila - 1;
    $rango = "A4:G{$ultimaFila}";

    // BORDES DE LA TABLA
    $sheet->getStyle($rango)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);

    // Alinear texto al centro
    $sheet->getStyle($rango)
        ->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

    // Autoajustar columnas
    foreach (range('A', 'G') as $col) {
        $sheet->getColumnDimension($col)->setAutoSize(true);
    }
}
//==============================
// PRESTAMOS
//=============================
if ($tipoInforme == "prestamos") {
    // Obtener los datos para el reporte
    $datos = $datosController->datosPrestamos($fechaInicio, $fechaFin);

    //  TITULO DEL REPORTE
    $sheet->mergeCells('A1:E1');
    $sheet->setCellValue('A1', 'REPORTE DE ' . strtoupper($tipoInforme));
    $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
    $sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

    // FECHA DEL REPORTE
    $sheet->setCellValue('A2', 'Fecha de generación: ' . date('d/m/Y'));
    $sheet->mergeCells('A2:E2');
    $sheet->getStyle('A2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);


    // CABECERA DE LA TABLA
    $encabezados = ['Prestamo', 'Reserva', 'Fecha Prestamo', 'Fecha Devolucion', 'Estado'];
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
        $sheet->setCellValue('B' . $fila, $dato["id_reserva"]);
        $sheet->setCellValue('C' . $fila, $dato["fecha_prestamo"]);
        $sheet->setCellValue('D' . $fila, $dato["fecha_devolucion"]);
        $sheet->setCellValue('E' . $fila, $dato["estado"]);
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
}



// DESCARGAR EL ARCHIVO
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="reporte_' . $tipoInforme . date("d/m/Y H:i:s") . '.xlsx"');
header('Cache-Control: max-age=0');

$writer = new Xlsx($spreadsheet);
$writer->save('php://output');
exit;
