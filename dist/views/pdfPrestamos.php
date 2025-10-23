<?php
// views/generar_pdf.php
require_once '../../libs/fpdf186/fpdf.php';
require_once '../../controllers/reportesController.php';

$controlador = new reportesController();
$prestamos = $controlador->reportesPrestamos();

class PDF extends FPDF {
function Header() {
$this->SetFont('Arial', 'B', 14);
$this->Cell(0, 10, 'Listado de Empleados', 0, 1, 'C');
$this->Ln(5);
}

function Footer() {
$this->SetY(-15);
$this->SetFont('Arial', 'I', 8);
$this->Cell(0, 10, 'Página ' . $this->PageNo(), 0, 0, 'C');
}
}

$pdf = new PDF();
$pdf->AddPage();
$pdf->SetFont('Arial', 'B', 12);

$pdf->Cell(20, 10, 'Prestamo', 1);
$pdf->Cell(20, 10, 'Reserva', 1);
$pdf->Cell(60, 10, 'Fecha Prestamo', 1);
$pdf->Cell(60, 10, 'Fecha Devolucion', 1);
$pdf->Cell(30, 10, 'Estado', 1);
$pdf->Ln();

$pdf->SetFont('Arial', '', 12);
foreach ($prestamos as $pres) {
$pdf->Cell(20, 10, $pres['id'], 1);
$pdf->Cell(20, 10, $pres['id_reserva'], 1);
$pdf->Cell(60, 10, $pres['fecha_prestamo'], 1);
$pdf->Cell(60, 10, $pres['fecha_devolucion'], 1);
$pdf->Cell(30, 10, $pres['estado'], 1);
$pdf->Ln();
}

// Se puede visualizar en el navegador o descargar:
// 'I' = Inline (mostrar), 'D' = Download
$pdf->Output('I', 'Listado_Prestamos.pdf');
// $pdf->Output('D', 'Listado_Empleados.pdf'); // ← para forzar descarga