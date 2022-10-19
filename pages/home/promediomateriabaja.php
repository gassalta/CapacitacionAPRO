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
if ($rs->num_rows > 0) {
    while ($row = $rs->fetch_assoc()) {

        $keys[] = $row['Denominacion'];
        $values[] = number_format($row['promedio'],3,'.','');
    }
}


/* $data = [
    'Formacion Especializada' =>    17,
    'Ciencias Sociales' =>    10,
    'Ciencias Naturales' =>    7,
    'Matematica' =>    5,
    'Lengua' => 4,
    'Lengua Extranjera' =>    4,
    'Educacion Fisica' => 4,
    'Educacion Artistica' => 4
]; */

/* $keys = ['sky', 'grass', 'orange'];
$values = ['blue', 'green', 'orange']; */

$data = array_combine($keys, $values);

/* echo '<pre>';
print_r($data);
echo '</pre>'; */


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
$pdf->Cell(0, 5, 'PROMEDIO MATERIAS MAS BAJAS', 0, 1);
$pdf->Ln();
$valX = $pdf->GetX();
$valY = $pdf->GetY();
$pdf->BarDiagram(190, 70, $data, '%l : %v (%p)', array(255, 175, 100));
$pdf->SetXY($valX, $valY + 80);

$pdf->Output();
