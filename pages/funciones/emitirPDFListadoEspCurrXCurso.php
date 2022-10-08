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
require_once 'listarEspaciosCurricularesXDocente.php';
$ListadoEspaciosCurriculares=array();
$idCurso=$_REQUEST['Cx'];
$CursoElegido = buscarCurso($MiConexion,$idCurso);
$AnioCurso = $CursoElegido['ANIO'];
$DivisionCurso = $CursoElegido['DIVISION'];
$Ciclo = "";
if ($CursoElegido['ANIO']=='1ro'||$CursoElegido['ANIO']=='2do'||$CursoElegido['ANIO']=='3ro') {
    $Ciclo = 'Ciclo Basico';
} else {
    $Ciclo = 'Ciclo Orientado';
}
$anioActual = date("Y");
$ListadoEspaciosCurriculares = ListarEspCurrXCurso($MiConexion,$idCurso);
$CantidadEspaciosCurriculares = count($ListadoEspaciosCurriculares);
if (!empty($ListadoEspaciosCurriculares))
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
	$pdf->Cell(190,10,'LISTADO DE ESPACIOS CURRICULARES',0,1,'C');
	$pdf->SetFont('Arial','',16);
	$pdf->Cell(190,10,utf8_decode('Curso: '.$AnioCurso.' '.$DivisionCurso.' Division'),0,1,'C');
	$pdf->SetFont('Arial','',14);
	$pdf->Cell(190,10,utf8_decode('Cantidad de Espacios Curriculares: '.$CantidadEspaciosCurriculares),0,1,'C');
	//Salto de línea
	$pdf->Ln(10);
	//Agrego datos estudiante
	$pdf->SetFont('Arial','B',12);
	// Colors, line width and bold font
    $pdf->SetFillColor(0,126,139);
    $pdf->SetTextColor(255);
    $pdf->SetDrawColor(128,0,0);
    $pdf->SetLineWidth(.3);
    $pdf->SetX(12);
    // Header
    $pdf->Cell(7,7,'#',1,0,'C',true);
    $pdf->Cell(100,7,'Nombre Espacio Curricular',1,0,'C',true);
    $pdf->Cell(80,7,'Area',1,0,'C',true);
    $pdf->Ln();
    $pdf->SetFillColor(224,235,255);
    $pdf->SetTextColor(0);
    $pdf->SetFont('Arial','',11);
    //Data
    $fill = false;
    for ($i=0; $i < $CantidadEspaciosCurriculares; $i++) { 
    	$pdf->SetX(12);
    	$pdf->Cell(7,6,utf8_decode($ListadoEspaciosCurriculares[$i]['ID']),'LR',0,'R',$fill);
    	$pdf->Cell(100,6,utf8_decode($ListadoEspaciosCurriculares[$i]['NOMBREESPACCURRIC']),'LR',0,'L',$fill);
    	$pdf->Cell(80,6,utf8_decode($ListadoEspaciosCurriculares[$i]['AREA']),'LR',0,'L',$fill);
    	$pdf->Ln();
    	$fill = !$fill;
    }
    $pdf->SetX(12);
    $pdf->Cell(187,0,'','T');

	$pdf->OutPut(utf8_decode('EspaciosCurricularesCurso'.$AnioCurso.$DivisionCurso.$anioActual.'.pdf'),'D');
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