<?php
//Conecto con la base de datos
require_once '../funciones/conexion.php';
$MiConexion = ConexionBD();
ini_set('default_charset','utf-8');
header('Content-Type: text/html; charset=ISO-8859-1');
require('../funciones/diagrama/diag.php');
define('FPDF_FONTPATH', '../funciones/font/');
$pdf = new PDF_Diag();
$pdf->AddPage();


$SQL = "SELECT c.estudiante, AVG(c.calificacion) nota, ROUND(c.calificacion,2) redondeo,CONCAT(e.apellido,', ',e.nombre) alumno, e.curso
FROM calificacionfinalxespcurr c 
INNER JOIN estudiantes e ON e.id = c.estudiante 
INNER JOIN cursos c2 ON c2.Id = e.curso
WHERE e.curso = 4 -- CUARTO AÑO
GROUP BY c.estudiante ORDER BY nota DESC LIMIT 3;";

$rs = mysqli_query($MiConexion, $SQL);

$keys = [];
$values = [];
if ($rs->num_rows > 0) {
    while ($row = $rs->fetch_assoc()) {

        $keys[] = $row['alumno'];
        $values[] = number_format($row['nota'],2,'.','');
    }
}


$data = array_combine($keys, $values);


//Bar diagram
$pdf->SetFont('Arial', 'BIU', 14);
$pdf->Cell(0, 10, utf8_decode('Mejores 3 promedios de alumnos del último curso (4to año)'), 0, 1);
$pdf->Ln();
$valX = $pdf->GetX();
$valY = $pdf->GetY();
$pdf->BarDiagram(190, 70, $data, utf8_decode('%l').' : %v (%p)', array(255, 175, 100));
$pdf->SetXY($valX, $valY + 80);

$pdf->Output();
