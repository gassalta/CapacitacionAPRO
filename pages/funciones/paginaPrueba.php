<?php
ob_start();
require('fpdf.php');

$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetFont('Arial','B',16);
$pdf->Cell(40,10,'Hello World!');
$pdf->Output('PaginaPrueba.pdf','D');
ob_end_flush();
?>