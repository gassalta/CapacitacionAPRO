<?php
//Conecto con la base de datos
require_once '../funciones/conexion.php';
$MiConexion = ConexionBD();

require('../funciones/diagrama/diag.php');
define('FPDF_FONTPATH', '../funciones/font/');
$pdf = new PDF_Diag();
$pdf->AddPage();


$SQL = "SELECT a.*, COUNT(e.Id) total
        FROM areas a 
        inner join espacioscurriculares e on a.id = e.Area 
        GROUP by e.Area
        ORDER BY total DESC";

$rs = mysqli_query($MiConexion, $SQL);

$keys = [];
$values = [];
$color = [];

function getColor($num)
{
    $hash = md5('color' . $num); // modify 'color' to get a different palette
    return array(
        hexdec(substr($hash, 0, 2)), // r
        hexdec(substr($hash, 2, 2)), // g
        hexdec(substr($hash, 4, 2))
    ); //b
}


$dato = array('Men' => 1510, 'Women' => 1610, 'Children' => 1400);


//Pie chart
$pdf->SetFont('Arial', 'BIU', 12);
$pdf->Cell(0, 5, 'MATERIAS MAS CURSADAS', 0, 1);
$pdf->Ln(8);

$pdf->SetFont('Arial', '', 10);
$valX = $pdf->GetX();
$valY = $pdf->GetY();

if ($rs->num_rows > 0) {
    $i = 0;
    while ($row = $rs->fetch_assoc()) {

        $keys[] = $row['Denominacion'];
        $values[] = number_format($row['total'], 2, '.', '');

        $numero_color = rand(50, 999);
        $color[] = getColor($numero_color);

        $i++;

    }
}

$data = array_combine($keys, $values);

$pdf->SetXY(15, 20);

$pdf->PieChart(150, 115, $data, '%l (%p)', $color);
$pdf->SetXY($valX, $valY + 80);

$pdf->Output();
