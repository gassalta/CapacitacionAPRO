<?php
//incluyo libreria para emitir pdf
require_once 'fpdf.php';
	function generate($idCurso, $idEstud){
//Conecto con la base de datos
require_once 'conexion.php';
$MiConexion=ConexionBD();
require_once 'buscarCurso.php';
$CursoElegido = array();
require_once 'buscarEstudiante.php';
$EstudElegido = array();
$anioActual = date("Y");
$EstudElegido = buscarEstudianteSimple($MiConexion,$idEstud);
$apeEstudiante = $EstudElegido['APELLIDO'];
$nomEstudiante = $EstudElegido['NOMBRE'];
$dniEstudiante = $EstudElegido['DNI'];
$legaEstudiante = $EstudElegido['NROLEGAJO'];
$lMatrizEstudiante = $EstudElegido['NROLIBROMATRIZ'];
$fEstudiante = $EstudElegido['NROFOLIO'];
$CursoElegido = buscarCurso($MiConexion,$idCurso);
$AnioCurso = $CursoElegido['ANIO'];
$DivisionCurso = $CursoElegido['DIVISION'];
require_once 'listarEspaciosCurricularesXDocente.php';
$ListadoEspaciosCurriculares = array();
$ListadoEspaciosCurriculares = ListarEspCurrXCurso($MiConexion,$idCurso);
$CantidadEspaciosCurriculares = count($ListadoEspaciosCurriculares);
require_once 'listarNotas.php';
$notasFinales = array();
$notasFinales = listarNotasFinalesXEstudiante($MiConexion,$idEstud,$idCurso);
$cantNotasFinales = count($notasFinales);
$notasColoquio = array();
$notasColoquio= listarNotasXEtapaXEstud($MiConexion,3,$idEstud);
$cantNotasColoquio=count($notasColoquio);
$notasMarzo=array();
$notasMarzo= listarNotasXEtapaXEstud($MiConexion,4,$idEstud);
$cantNotasMarzo=count($notasMarzo);

if (!empty($notasFinales))
	{
	ob_start();
	//creo pdf vacío
	$pdf = new FPDF();
	//agrego una pagina al pdf
	$pdf ->AddPage();
	//establezco tipo de letra
	$pdf->SetFont('Arial','U',16);
	//Agrego celda con título
	$pdf->Cell(190,10,'REGISTRO ANUAL DE CALIFICACIONES',0,1,'C');
	//Salto de línea
	$pdf->Ln(10);
	//Agrego datos estudiante
	$pdf->SetFont('Arial','',12);
	$pdf->Cell(150,10,utf8_decode('Estudiante: '.$apeEstudiante.' '.$nomEstudiante),0,0);
	$pdf->Cell(50,10,'DNI: '.$dniEstudiante,0,0);
	$pdf->Ln();
	$pdf->Cell(75,10,utf8_decode('Curso: '.$AnioCurso.' Division: '.$DivisionCurso.' Turno: MyT'),0,0);
	$pdf->Cell(50,10,utf8_decode('Legajo: '.$legaEstudiante),0,0);
	$pdf->Cell(50,10,utf8_decode(' L°: '.$lMatrizEstudiante),0,0);
	$pdf->Cell(50,10,utf8_decode(' F°: '.$fEstudiante),0,1);

	//Salto de línea
	$pdf->Ln(10);
	//Encabezados de la tabla con las notas
    $pdf->Cell(79,28,'Espacio Curricular',1,0,'C',false);
    $posX = $pdf->GetX();
	$posY = $pdf->GetY();
    $pdf->MultiCell(17,7,'Calif. Final Anual','LTR','C',false);
    $pdf->SetXY($posX+17,$pdf->GetY());
    $pdf->Cell(17,7,'','LRB',0,'C',false);
    $pdf->SetXY($posX+17,$posY);
    $pdf->MultiCell(17,7,'Coloquio','LTR','C',false);
    $pdf->SetXY($posX+34,$pdf->GetY());
    $pdf->Cell(17,14,'','LRB',0,'C',false);
    $pdf->SetXY($posX+34,$posY);
    $pdf->MultiCell(17,7,'Examen Marzo','LTR','C',false);
    $pdf->SetXY($posX+51,$pdf->GetY());
    $pdf->Cell(17,7,'','LRB',0,'C',false);
    $pdf->SetXY($posX+51,$posY);
    $pdf->MultiCell(17,7,utf8_decode('Calificación Definitiva'),1,'C',false);
    $pdf->SetXY($posX+68,$posY);
    $pdf->Cell(17,28,'',1,0,'C',false);
    $pdf->SetXY($posX+85,$posY);
    $pdf->MultiCell(27,7,'Observaciones','LTR','C',false);
    $pdf->SetXY($posX+85,$pdf->GetY());
    $pdf->Cell(27,14,'','LRB',0,'C',false);
    $pdf->Ln();
    
    $pdf->SetFont('Arial','',11);
    //Data
    for ($i=0; $i < $CantidadEspaciosCurriculares; $i++) { 
    	//Nombre del Espacio Curricular
    	$pdf->Cell(79,6,utf8_decode($ListadoEspaciosCurriculares[$i]['NOMBREESPACCURRIC']),1,0,'L',false);
    	//Nota final
    	$NF=0;
    	$posic = -1;
    	for ($j=0; $j < $cantNotasFinales; $j++) { 
    		if ($notasFinales[$j]['ESPACIOCURRICULAR']==$ListadoEspaciosCurriculares[$i]['ID']) {
    			$posic=$j;
    		}
    	}
    	if ($posic != -1) {
    		$NF=$notasFinales[$posic]['CALIFICACION'];
    		$pdf->Cell(17,6,utf8_decode($notasFinales[$posic]['CALIFICACION']),1,0,'C',false);
    	} else {
    		$pdf->Cell(17,6,"",1,0,'C',false);
    	}
    	//Coloquio
    	$posic = -1;
    	$C = 0;
    	for ($j=0; $j < $cantNotasColoquio; $j++) { 
    		if ($notasColoquio[$j]['ESPACIOCURRICULAR']==$ListadoEspaciosCurriculares[$i]['ID']) {
    			$posic=$j;
    		}
    	}
    	if ($posic != -1) {
    		$C=$notasColoquio[$posic]['CALIFICACION'];
    		$pdf->Cell(17,6,utf8_decode($notasColoquio[$posic]['CALIFICACION']),1,0,'C',false);
    	} else {
    		$pdf->Cell(17,6,"",1,0,'C',false);
    	}
    	//Marzo
    	$posic = -1;
    	$M=0;
    	for ($j=0; $j < $cantNotasMarzo; $j++) { 
    		if ($notasMarzo[$j]['ESPACIOCURRICULAR']==$ListadoEspaciosCurriculares[$i]['ID']) {
    			$posic=$j;
    		}
    	}
    	if ($posic != -1) {
    		$M=$notasMarzo[$posic]['CALIFICACION'];
    		$pdf->Cell(17,6,utf8_decode($notasMarzo[$posic]['CALIFICACION']),1,0,'C',false);
    	} else {
    		$pdf->Cell(17,6,"",1,0,'C',false);
    	}
    	//Calificacion definitiva
    	if ($NF != 0 || $C != 0 || $M != 0) {
    		if ($NF > $C && $NF > $M) {
    			$pdf->Cell(17,6,utf8_decode($NF),1,0,'C',false);
    		} else {
    			if ($C > $M) {
    				$pdf->Cell(17,6,utf8_decode($C),1,0,'C',false);
    			} else {
    				$pdf->Cell(17,6,utf8_decode($M),1,0,'C',false);
    			}
    		}
    	} else {
    		$pdf->Cell(17,6,'',1,0,'C',false);
    	}
    	
    	//Columna en Blanco
    	$pdf->Cell(17,6,"",1,0,'C',false);
    	//Observaciones
    	$pdf->Cell(27,6,"",1,0,'C',false);
    	$pdf->Ln();
    }
    $pdf->Ln(10);
   
	$pdf->OutPut(utf8_decode('funciones/RACs/RAC'.$apeEstudiante.$nomEstudiante.'.pdf'),'F');
	ob_end_flush();
} 
}
?>