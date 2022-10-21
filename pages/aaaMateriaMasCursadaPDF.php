<?php
//Conecto con la base de datos
require_once 'funciones/conexion.php';
$MiConexion = ConexionBD();

require('funciones/diagrama/diag.php');
define('FPDF_FONTPATH', 'funciones/font/');
$pdf = new PDF_Diag();
$pdf->AddPage();


$SQL = "SELECT a.*, COUNT(e.Id) total
        FROM areas a 
        inner join espacioscurriculares e on a.id = e.Area 
        GROUP by e.Area
        ORDER BY total DESC";

$rs = mysqli_query($MiConexion, $SQL);

$i = 0;

$data = [
    'Formacion Especializada' =>    17,
    'Ciencias Sociales' =>    10,
    'Ciencias Naturales' =>    7,
    'Matematica' =>    5,
    'Lengua' => 4,
    'Lengua Extranjera' =>    4,
    'Educacion Fisica' => 4,
    'Educacion Artistica' => 4
];


/* echo "<pre>";
print_r($data);
echo "</pre>";
 */

/* //Pie chart
$pdf->SetFont('Arial', 'BIU', 12);
$pdf->Cell(0, 5, '1 - Pie chart', 0, 1);
$pdf->Ln(8);

$pdf->SetFont('Arial', '', 10);
$valX = $pdf->GetX();
$valY = $pdf->GetY();
$pdf->Cell(30, 5, 'Number of men:');
$pdf->Cell(15, 5, $data['Men'], 0, 0, 'R');
$pdf->Ln();
$pdf->Cell(30, 5, 'Number of women:');
$pdf->Cell(15, 5, $data['Women'], 0, 0, 'R');
$pdf->Ln();
$pdf->Cell(30, 5, 'Number of children:');
$pdf->Cell(15, 5, $data['Children'], 0, 0, 'R');
$pdf->Ln();
$pdf->Ln(8);

$pdf->SetXY(90, $valY);
$col1=array(100,100,255);
$col2=array(255,100,100);
$col3=array(255,255,100);
$pdf->PieChart(100, 35, $data, '%l (%p)', array($col1,$col2,$col3));
$pdf->SetXY($valX, $valY + 40); */

//Bar diagram
$pdf->SetFont('Arial', 'BIU', 12);
$pdf->Cell(0, 5, 'MATERIAS MAS CURSADAS', 0, 1);
$pdf->Ln(8);
$valX = $pdf->GetX();
$valY = $pdf->GetY();
$pdf->BarDiagram(190, 70, $data, '%l : %v Alumnos (%p)', array(255, 175, 100));
$pdf->SetXY($valX, $valY + 80);

$pdf->Output();
