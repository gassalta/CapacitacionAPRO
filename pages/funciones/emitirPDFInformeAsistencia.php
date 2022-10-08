<?php
session_start();
if(empty($_SESSION['Nombre'])) {
  header('Location: cerrarSesion.php');
  exit;
}
//Conecto con la base de datos
require_once 'conexion.php';
$MiConexion=ConexionBD();
require_once 'buscarCurso.php';
$CursoElegido = array();
require_once 'buscarEstudiante.php';
$EstudElegido = array();
require_once 'informeAsistencia.php';
$totalesAsistencia = array();
$anioActual = date("Y");
$EstudElegido = buscarEstudianteSimple($MiConexion,$_SESSION['EstudEleg']);
$apeEstudiante = $EstudElegido['APELLIDO'];
$nomEstudiante = $EstudElegido['NOMBRE'];
$dniEstudiante = $EstudElegido['DNI'];
$legaEstudiante = $EstudElegido['NROLEGAJO'];
$CursoElegido = buscarCurso($MiConexion,$_SESSION['CursoEleg']);
$AnioCurso = $CursoElegido['ANIO'];
$DivisionCurso = $CursoElegido['DIVISION'];
$totalesAsistencia = contarTotalesAsistencia($MiConexion,$EstudElegido['ID'],$anioActual);
$Ciclo = "";
if ($CursoElegido['ANIO']=='1ro'||$CursoElegido['ANIO']=='2do'||$CursoElegido['ANIO']=='3ro') {
    $Ciclo = 'Ciclo Basico';
} else {
    $Ciclo = 'Ciclo Orientado';
}
if (!empty($totalesAsistencia))
	{
	ob_start();
	//incluyo libreria para emitir pdf
	require_once 'fpdf.php';
	//creo pdf vacío
	$pdf = new FPDF();
	//agrego una pagina al pdf
	$pdf ->AddPage();
	//establezco tipo de letra
	$pdf->SetFont('Arial','U',16);
	//Agrego celda con título
	$pdf->Cell(190,10,'INFORME DE ASISTENCIA',0,1,'C');
	//Salto de línea
	$pdf->Ln(10);
	//Agrego datos estudiante
	$pdf->SetFont('Arial','',12);
	$pdf->Cell(100,10,utf8_decode('Estudiante: '.$apeEstudiante.' '.$nomEstudiante),0,0);
	$pdf->Cell(50,10,'DNI: '.$dniEstudiante,0,0);
	$pdf->Cell(50,10,'Legajo: '.$legaEstudiante,0,1);
	$pdf->Cell(150,10,utf8_decode('Curso: '.$AnioCurso.' '.$DivisionCurso),0,0);
	$pdf->Cell(50,10,utf8_decode($Ciclo),0,1);
	//Salto de línea
	$pdf->Ln(10);
	//Agrego datos asistencia
	$pdf->Cell(190,10,'ASISTENCIA',0,1,'C');
	$pdf->Cell(60,10,'Total: '.$totalesAsistencia['TOTAL'],0,1);
	$pdf->Cell(60,10,'Presente: '.$totalesAsistencia['PRESENTES'],0,1);
	$pdf->Cell(190,10,'Inasistencias',0,1,'C');
	$pdf->Cell(150,10,'Justificadas: '.$totalesAsistencia['JUSTIFICADAS'],0,0);
	$pdf->Cell(50,10,'No Justificadas: '.$totalesAsistencia['INJUSTIFICADAS'],0,1);
	$pdf->Cell(60,10,'Total Inasistencias: '.$totalesAsistencia['INASISTENCIAS'],0,1);
	$pdf->OutPut(utf8_decode('InformeAsistencia'.$apeEstudiante.$nomEstudiante.'.pdf'),'D');
	ob_end_flush();
} else {
	 ?>
                <div class="alert alert-dismissible alert-danger">
                  <strong>No se encuentran datos</strong>
                </div>
              </div>
             <?php
}
?>