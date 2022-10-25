<?php
//Conecto con la base de datos
require_once '../funciones/conexion.php';
$MiConexion = ConexionBD();

require('../funciones/diagrama/diag.php');
define('FPDF_FONTPATH', '../funciones/font/');
$pdf = new PDF_Diag();
$pdf->AddPage();


$SQL = "SELECT c.espacioCurricular, AVG(c.calificacion) promedio, e.NombreEspacCurric, a.Denominacion 
FROM calificacionfinalxespcurr c
INNER JOIN espacioscurriculares e ON e.Id = c.espacioCurricular
inner join areas a ON a.Id = e.Area 
GROUP BY c.espacioCurricular 
ORDER BY promedio ASC
LIMIT 10;";

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

 //Pie chart
$pdf->SetFont('Arial', 'BIU', 12);
$pdf->Cell(0, 5, 'PROMEDIO MATERIAS MAS BAJAS', 0, 1);
$pdf->Ln(8);

$pdf->SetFont('Arial', '', 10);
$valX = $pdf->GetX();
$valY = $pdf->GetY();

if ($rs->num_rows > 0) {
    $i = 0;
    while ($row = $rs->fetch_assoc()) {

        $keys[] = $row['Denominacion'];
        $values[] = $row['promedio'];

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
