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
require_once 'listarEstudiantes.php';
$ListadoEstudiantes=array();
$idCurso = $_REQUEST['Cx'];
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
$ListadoEstudiantes = ListarEstudiantesXCurso($MiConexion,$idCurso);
$CantidadEstudiantes = count($ListadoEstudiantes);
if (!empty($ListadoEstudiantes))
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
	$pdf->Cell(190,10,'LISTADO DE ESTUDIANTES',0,1,'C');
	$pdf->SetFont('Arial','',16);
	$pdf->Cell(190,10,utf8_decode('Curso: '.$AnioCurso.' '.$DivisionCurso.' Division'),0,1,'C');
	$pdf->SetFont('Arial','',14);
	$pdf->Cell(190,10,utf8_decode('Cantidad de Estudiantes: '.$CantidadEstudiantes),0,1,'C');
	//Salto de línea
	$pdf->Ln(10);
	//Agrego datos estudiante
	$pdf->SetFont('Arial','B',12);
	$pdf->SetX(2);
	// Colors, line width and bold font
    $pdf->SetFillColor(0,126,139);
    $pdf->SetTextColor(255);
    $pdf->SetDrawColor(128,0,0);
    $pdf->SetLineWidth(.3);
    // Header
    $pdf->Cell(6,7,'#',1,0,'C',true);
    $pdf->Cell(18,7,'Legajo',1,0,'C',true);
    $pdf->Cell(35,7,'Apellido',1,0,'C',true);
    $pdf->Cell(35,7,'Nombre',1,0,'C',true);
    $pdf->Cell(20,7,'DNI',1,0,'C',true);
    $pdf->Cell(26,7,'Telefono',1,0,'C',true);
    $pdf->Cell(65,7,'e-mail',1,0,'C',true);
    $pdf->Ln();
    $pdf->SetFillColor(224,235,255);
    $pdf->SetTextColor(0);
    $pdf->SetFont('Arial','',11);
    //Data
    $fill = false;
    for ($i=0; $i < $CantidadEstudiantes; $i++) { 
    	$pdf->SetX(2);
    	$pdf->Cell(6,6,utf8_decode($ListadoEstudiantes[$i]['ID']),'LR',0,'R',$fill);
    	$pdf->Cell(18,6,utf8_decode($ListadoEstudiantes[$i]['NROLEGAJO']),'LR',0,'L',$fill);
    	$pdf->Cell(35,6,utf8_decode($ListadoEstudiantes[$i]['APELLIDO']),'LR',0,'L',$fill);
    	$pdf->Cell(35,6,utf8_decode($ListadoEstudiantes[$i]['NOMBRE']),'LR',0,'L',$fill);
    	$pdf->Cell(20,6,utf8_decode($ListadoEstudiantes[$i]['DNI']),'LR',0,'C',$fill);
    	$pdf->Cell(26,6,utf8_decode($ListadoEstudiantes[$i]['TELEFONO']),'LR',0,'L',$fill);
    	$pdf->Cell(65,6,utf8_decode($ListadoEstudiantes[$i]['MAIL']),'LR',0,'L',$fill);
    	$pdf->Ln();
    	$fill = !$fill;
    }
    $pdf->SetX(2);
    $pdf->Cell(205,0,'','T');

	$pdf->OutPut(utf8_decode('EstudiantesCurso'.$AnioCurso.$DivisionCurso.$anioActual.'.pdf'),'D');
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