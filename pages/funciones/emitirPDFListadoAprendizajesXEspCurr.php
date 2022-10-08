<?php
session_start();
if(empty($_SESSION['Nombre'])) {
  header('Location: cerrarSesion.php');
  exit;
}
//Conecto con la base de datos
require_once 'conexion.php';
$MiConexion=ConexionBD();
require_once 'buscarEspacioCurricular.php';
$EspCurrElegido = array();
require_once 'listarAprendizajesXEspCurr.php';
$ListadoAprendizajes=array();
$EspCurrElegido = buscarEspacCurric($MiConexion,$_SESSION['EspCurrEleg']);
$NombreEspacCurric = $EspCurrElegido['NOMBREESPACCURRIC'];
$ListadoAprendizajes = Listar_AprendizajesXEC($MiConexion,$_SESSION['EspCurrEleg']);
$CantidadAprendizajes = count($ListadoAprendizajes);
if (!empty($ListadoAprendizajes))
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
	$pdf->Cell(190,10,'LISTADO DE APRENDIZAJES',0,1,'C');
	$pdf->SetFont('Arial','',16);
	$pdf->Cell(190,10,utf8_decode('Espacio Curricular: '.$NombreEspacCurric),0,1,'C');
	$pdf->SetFont('Arial','',14);
	$pdf->Cell(190,10,utf8_decode('Cantidad de Aprendizajes: '.$CantidadAprendizajes),0,1,'C');
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
    $pdf->Cell(40,7,'Contenido',1,0,'C',true);
    $pdf->Cell(140,7,'Aprendizaje',1,0,'C',true);
    $pdf->Ln();
    $pdf->SetFillColor(224,235,255);
    $pdf->SetTextColor(0);
    $pdf->SetFont('Arial','',11);
    //Data
    $fill = false;
    for ($i=0; $i < $CantidadAprendizajes; $i++) { 
    	$pdf->SetX(12);
    	$pdf->Cell(7,6,utf8_decode($ListadoAprendizajes[$i]['ID']),'LTR',0,'R',$fill);
    	$posX=$pdf->GetX();
    	$posY=$pdf->GetY();
    	$pdf->MultiCell(40,6,utf8_decode($ListadoAprendizajes[$i]['CONTENIDO']),'LTR','L',$fill);
    	$posY1=$pdf->GetY();
    	if ($posY1<$posY) {
    		$posY=30;
    	}
    	$pdf->SetXY($posX+40,$posY);
    	$pdf->MultiCell(140,6,utf8_decode($ListadoAprendizajes[$i]['DENOMINACION']),'LTR','L',$fill);
    	$posY2=$pdf->GetY();
    	if ($posY1>$posY2) {
    		if ($posY1<263 && $posY2>37) {
    			$pdf->SetXY($posX+40,$posY2);
    			$pdf->Cell(140,$posY1-$posY2,'','LR',0,'L',$fill);
    			$pdf->SetXY(12,$posY1);
    		} 
    	}
    	$fill = !$fill;
    	$pdf->SetX(12);
    $pdf->Cell(187,0,'','T');
    }
    $pdf->SetX(12);
    $pdf->Cell(187,0,'','T');
    

	$pdf->OutPut(utf8_decode('AprendizajesXEspacioCurricular'.$NombreEspacCurric.'.pdf'),'D');
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