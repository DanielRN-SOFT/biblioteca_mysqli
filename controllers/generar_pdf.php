<?php

require_once '../libs/fpdf/fpdf.php';
require_once './generarDatosReporte.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (
        isset($_POST["tipoInforme"]) && !empty($_POST["tipoInforme"])
        && isset($_POST["fechaInicio"]) && !empty($_POST["fechaInicio"])
        && isset($_POST["fechaFin"]) && !empty($_POST["fechaFin"])
    ) {
        // Asignacion de zona horaria para la hora del documento
        date_default_timezone_set('America/Bogota');
        // Captura de datos
        $fechaInicio = filter_var($_POST["fechaInicio"], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $fechaFin = filter_var($_POST["fechaFin"], FILTER_SANITIZE_FULL_SPECIAL_CHARS);

        // Instancia de la clase de los datos del PDF
        $datosController = new generarDatosReporte();

        // CREACION PDF
        $pdf = new FPDF();
        $pdf->AliasNbPages(); // habilita {nb} para total de páginas
        $pdf->AddPage();

      


        $tipoInforme = filter_var($_POST["tipoInforme"], FILTER_SANITIZE_FULL_SPECIAL_CHARS);

        //  =======================================
        //  RESERVAS
        //  =======================================
        if ($tipoInforme == "Reserva") {
            // IMG
            $pdf->Image('../dist/assets/img/biblioteca.png', 10, 6, 20);
            $pdf->SetFont('Arial', 'B', 14);    
            $pdf->Cell(0, 40, "Biblioteca");
            $pdf->SetXY(0, 30);

            // Encabezado
            $pdf->SetFont('Arial', 'B', 14);
            $pdf->Cell(0, 10, 'Reporte de reservas', 0, 1, 'C');
            $pdf->Ln(5);

            // Body
            $datos = $datosController->datosReserva($fechaInicio, $fechaFin);
            $pdf->SetFont('Arial', "B", 8);
           
            $pdf->Cell(15, 10, "ID", 1);
            $pdf->Cell(30, 10, "Nombre", 1);
            $pdf->Cell(35, 10, "Apellido", 1);
            $pdf->Cell(60, 10, "Libro", 1);
            $pdf->Cell(23, 10, "Estado", 1);
            $pdf->Cell(35, 10, "Fecha", 1);

            $pdf->Ln();

            $pdf->SetFont("Arial", "", 8);
            foreach ($datos as $dato) {
                $pdf->Cell(15, 10, $dato["reserva_id"], 1);
                $pdf->Cell(30, 10, $dato["nombre"], 1);
                $pdf->Cell(35, 10,  $dato["apellido"], 1);
                $pdf->Cell(60, 10,  $dato["titulo"], 1);
                $pdf->Cell(23, 10,  $dato["estado"], 1);
                $pdf->Cell(35, 10,  $dato["fecha_reserva"], 1);
                $pdf->Ln();
            }
            // Pie de pagina del documento
            $pdf->SetY(265);
            $pdf->SetFont('Arial', 'I', 9);
            $pdf->Cell(0, 10, utf8_decode('Elaborado por BibliotecaMySqli. Fecha de elaboracion: ') . date('d/m/Y H:i'), 0, 0, 'C');
            $pdf->Cell(0, 10, utf8_decode('Página ') . $pdf->PageNo() . ' de {nb}', 0, 0, 'C');

           

            $pdf->Output('D', 'reporte_reservas.pdf');
        }

        //  =======================================
        //  USUARIOS
        //  =======================================
        if ($tipoInforme == "Usuario") {
            // IMG
            $pdf->Image('../dist/assets/img/biblioteca.png', 10, 6, 20);
            $pdf->SetFont('Arial', 'B', 14);
            $pdf->Cell(0, 40, "Biblioteca");
            $pdf->SetXY(0, 30);

            
            // Encabezado
            $pdf->SetFont('Arial', 'B', 14);
            $pdf->Cell(0, 10, 'Reporte de Usuarios', 0, 1, 'C');
            $pdf->Ln(5);

            // DATOS DEL PDF
            $datos = $datosController->datosUsuario($fechaInicio, $fechaFin);

            // Encabezado
            $pdf->SetFont('Arial', "B", 8);
            $pdf->Cell(30, 10, "Nombre", 1);
            $pdf->Cell(35, 10, "Apellido", 1);
            $pdf->Cell(60, 10, "Email", 1);
            $pdf->Cell(20, 10, "Tipo", 1);
            $pdf->Cell(20, 10, "Estado", 1);
            $pdf->Cell(30, 10, "Fecha de creacion", 1);

            $pdf->Ln();

            // BODY DEL PDF
            $pdf->SetFont("Arial", "", 8);
            foreach ($datos as $dato) {
                $pdf->Cell(30, 10, $dato["nombre"], 1);
                $pdf->Cell(35, 10,  $dato["apellido"], 1);
                $pdf->Cell(60, 10,  $dato["email"], 1);
                $pdf->Cell(20, 10,  $dato["tipo"], 1);
                $pdf->Cell(20, 10,  $dato["estado"], 1);
                $pdf->Cell(30, 10,  $dato["fecha_creacion"], 1);
                $pdf->Ln();
            }
            // Pie de pagina del documento
            $pdf->SetY(265);
            $pdf->SetFont('Arial', 'I', 9);
            $pdf->Cell(0, 10, utf8_decode('Elaborado por BibliotecaMySqli. Fecha de elaboracion: ') . date('d/m/Y H:i'), 0, 0, 'C');
            $pdf->Cell(0, 10, utf8_decode('Página ') . $pdf->PageNo() . ' de {nb}', 0, 0, 'C');
          
            $pdf->Output('D', 'reporte_usuarios.pdf');
        }

      
    }
}
