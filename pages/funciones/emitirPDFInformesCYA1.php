<?php
//incluyo libreria para emitir pdf
require_once 'fpdf.php';
	function generate($idCurso, $etapa, $idEstud){
//Conecto con la base de datos
require_once 'conexion.php';
$MiConexion=ConexionBD();
require_once 'buscarCurso.php';
$CursoElegido = array();
require_once 'buscarEstudiante.php';
$EstudElegido = array();
$anioActual = date("Y");
require_once 'listarEstudiantes.php';
$EstudElegido = buscarEstudiante($MiConexion,$idEstud);
$apeEstudiante = $EstudElegido['APELLIDO'];
$nomEstudiante = $EstudElegido['NOMBRE'];
$dniEstudiante = $EstudElegido['DNI'];
$legaEstudiante = $EstudElegido['NROLEGAJO'];
$CursoElegido = buscarCurso($MiConexion,$idCurso);
$AnioCurso = $CursoElegido['ANIO'];
$DivisionCurso = $CursoElegido['DIVISION'];
$Ciclo = "";
if ($CursoElegido['ANIO']=='1ro'||$CursoElegido['ANIO']=='2do'||$CursoElegido['ANIO']=='3ro') {
    $Ciclo = 'CICLO BÁSICO';
} else {
    $Ciclo = 'CICLO ORIENTADO';
}
require_once 'listarEspaciosCurricularesXDocente.php';
$ListadoEspaciosCurriculares = array();
$ListadoEspaciosCurriculares = ListarEspCurrXCurso($MiConexion,$idCurso);
$CantidadEspaciosCurriculares = count($ListadoEspaciosCurriculares);
require_once 'listarAprendizajesXEspCurr.php';
if (!empty($ListadoEspaciosCurriculares))
	{
	ob_start();
	//creo pdf vacío
	$pdf = new FPDF();
	//agrego una pagina al pdf
	$pdf ->AddPage();
	//establezco tipo de letra
	$pdf->SetFont('Arial','U',16);
	//Agrego celda con título
	$pdf->Cell(190,10,'INFORME DE CONTENIDOS Y APRENDIZAJES',0,1,'C');
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
    
    $pdf->SetFont('Arial','',11);
    //Data
    for ($i=0; $i < $CantidadEspaciosCurriculares; $i++) { 
    	//LISTADO DE LOS APRENDIZAJES LOGRADOS DE LA 1RA ETAPA
    	$aprlogrados1raEt = array();
		$aprlogrados1raEt = ListarAprendizajesXEstudXECXEstado($MiConexion,$idEstud,$ListadoEspaciosCurriculares[$i]['ID'],1,1);
		$cantAprLogr1 = count($aprlogrados1raEt);
		//LISTADO DE LOS APRENDIZAJES PENDIENTES DE LA 1RA ETAPA
		$aprPend1raEt = array();
		$aprPend1raEt = ListarAprendizajesXEstudXECXEstado($MiConexion,$idEstud,$ListadoEspaciosCurriculares[$i]['ID'],1,2);
		$cantAprPend1 = count($aprPend1raEt);
		//SI ES LA EMISIÓN FINAL DEL INFORME (2daEtapa) PEDIR EL LISTADO DE LA 2DA ETAPA
		if ($etapa == 2) {
			//LISTADO DE LOS APRENDIZAJES LOGRADOS DE LA 2DA ETAPA
    		$aprlogrados2daEt = array();
			$aprlogrados2daEt = ListarAprendizajesXEstudXECXEstado($MiConexion,$idEstud,$ListadoEspaciosCurriculares[$i]['ID'],2,1);
			$cantAprLogr2 = count($aprlogrados2daEt);
			//LISTADO DE LOS APRENDIZAJES PENDIENTES DE LA 2DA ETAPA
			$aprPend2daEt = array();
			$aprPend2daEt = ListarAprendizajesXEstudXECXEstado($MiConexion,$idEstud,$ListadoEspaciosCurriculares[$i]['ID'],2,2);
			$cantAprPend2 = count($aprPend2daEt);
		}
    	//Encabezados de la tabla
    	$pdf->Cell(190,7,utf8_decode($ListadoEspaciosCurriculares[$i]['NOMBREESPACCURRIC']),1,0,'C',false);
    	$pdf->Ln();
    	$pdf->Cell(95,7,'Primer Etapa',1,'C',false);
    	$pdf->Cell(95,7,'Segunda Etapa',1,'C',false);   
    	$pdf->Ln();
    	$pdf->Cell(47.5,6,'Aprendizajes Logrados',1,'C',false);
    	$pdf->Cell(47.5,6,'Aprendizajes Pendientes',1,'C',false);
    	$pdf->Cell(47.5,6,'Aprendizajes Logrados',1,'C',false);
    	$pdf->Cell(47.5,6,'Aprendizajes Pendientes',1,'C',false);
    	$pdf->Ln();
    	$max = 0;
    	if ($etapa == 1) {
    		if ($cantAprLogr1>=$cantAprPend1) {
    			$max = $cantAprLogr1;
    		} else {
    			$max = $cantAprPend1;
    		}
    	} else {
    		if ($cantAprLogr1>=$cantAprPend1 && $cantAprLogr1>=$cantAprLogr2 && $cantAprLogr1>=$cantAprPend2) {
    			$max = $cantAprLogr1;
    		} else {
    			if ($cantAprPend1>=$cantAprLogr2 && $cantAprPend1>=$cantAprPend2) {
    				$max = $cantAprPend1;
    			} else {
    				if ($cantAprLogr2>=$cantAprPend2) {
    					$max = $cantAprLogr2;
    				} else {
    					$max = $cantAprPend2;
    				}
    			}
    		}
    	}
    	$posX = 0;
    	$posY =0;
    	if ($max == 0) {
    		$pdf->Cell(47.5,6,'',1,'L',false);
    		$pdf->Cell(47.5,6,'',1,'L',false);
    		$pdf->Cell(47.5,6,'',1,'L',false);
    		$pdf->Cell(47.5,6,'',1,'L',false);
    	} else {
    	for ($j=0; $j < $max; $j++) { 
    		$posXIni = $pdf->GetX();
    		$posYIni = $pdf->GetY();
    		$difY1 = 0;
    		$difY2 = 0;
    		$difY3 = 0;
    		$difY4 = 0;
    		if ($cantAprLogr1 > $j) {
    			//MOSTRAR LOS APRENDIZAJES LOGRADOS EN EL ESPACIO CURRICULAR DE LA 1RA ETAPA
    			$posX = $pdf->GetX();
    			$posY = $pdf->GetY();
    			$pdf->MultiCell(47.5,6,utf8_decode($aprlogrados1raEt[$j]['DENOMINACION']),'LR','L',false);
    			$difY1 = $pdf->GetY() - $posY;
    			$pdf->SetXY($posX+47.5,$posY);
    		} else {
    			$pdf->Cell(47.5,6,'','LR','L',false);
    		}
    		if ($cantAprPend1 > $j) {
    			//MOSTRAR LOS APRENDIZAJES PENDIENTES EN EL ESPACIO CURRICULAR DE LA 1RA ETAPA
    			$posX = $pdf->GetX();
    			$posY = $pdf->GetY();
    			$pdf->MultiCell(47.5,6,utf8_decode($aprPend1raEt[$j]['DENOMINACION']),'LR','L',false);
    			$difY2 = $pdf->GetY() - $posY;
    			$pdf->SetXY($posX+47.5,$posY);
    		} else {
    			$pdf->Cell(47.5,6,'','LR','L',false);
    		}
    		if ($etapa == 2) {
    			if ($cantAprLogr2 > $j) {
    				//MOSTRAR LOS APRENDIZAJES LOGRADOS EN EL ESPACIO CURRICULAR DE LA 2DA ETAPA
    				$posX = $pdf->GetX();
    				$posY = $pdf->GetY();
    				$pdf->MultiCell(47.5,6,utf8_decode($aprlogrados2daEt[$j]['DENOMINACION']),'LR','L',false);
    				$difY3 = $pdf->GetY() - $posY;
    				$pdf->SetXY($posX+47.5,$posY);
    			} else {
    				$pdf->Cell(47.5,6,'','LR','L',false);
    			}
    			if ($cantAprPend2 > $j) {
    				//MOSTRAR LOS APRENDIZAJES PENDIENTES EN EL ESPACIO CURRICULAR DE LA 2DA ETAPA
    				$posY = $pdf->GetY();
    				$pdf->MultiCell(47.5,6,utf8_decode($aprPend2daEt[$j]['DENOMINACION']),'LR','L',false);
    				$difY4 = $pdf->GetY() - $posY;
    			} else {
    				$pdf->Cell(47.5,6,'','LR','L',false);
    			}
    		} else {
    			$pdf->Cell(47.5,6,'','LR','L',false);
    			$pdf->Cell(47.5,6,'','LR','L',false);
    		}
    		if ($difY1>0 || $difY2>0 || $difY3>0 || $difY4>0) {
    			$difY = 0;
    			if ($difY1>=$difY2 && $difY1>=$difY3 && $difY1>=$difY4) {
    				$difY = $difY1;
    			} else {
    				if ($difY2>=$difY3 && $difY2>=$difY4) {
    					$difY = $difY2;
    				} else {
    					if ($difY3>=$difY4) {
    						$difY = $difY3;
    					} else {
    						$difY = $difY4;
    					}
    				}
    			}
    			$pdf->SetXY($posXIni,$posYIni+$difY);
    		} else {
    			$pdf->Ln();
    		}
    	}
    	$pdf->Cell(190,0,'','T');
    }
    	$pdf->Ln();
    	$pdf->Cell(190,6,'',0);
    	$pdf->Ln();
    }
    

	$pdf->OutPut(utf8_decode('funciones/Informes/InformeCyA'.$apeEstudiante.$nomEstudiante.'.pdf'),'F');
	ob_end_flush();
} 
}
?>