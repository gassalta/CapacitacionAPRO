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
$EstudElegido = buscarEstudianteSimple($MiConexion,$idEstud);
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
$cantAprLogr1=0;
$cantAprPend1=0;
$cantAprLogr2=0;
$cantAprPend2=0;
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
		if ($cantAprLogr1>0) {
			$todosAL1raEt ="";
			for ($j=0; $j < $cantAprLogr1; $j++) { 
				if ($j==$cantAprLogr1-1) {
					$todosAL1raEt = $todosAL1raEt.$aprlogrados1raEt[$j]['DENOMINACION'];
				} else {
					$todosAL1raEt = $todosAL1raEt.$aprlogrados1raEt[$j]['DENOMINACION']."\n";
				}
			}
		}
		//LISTADO DE LOS APRENDIZAJES PENDIENTES DE LA 1RA ETAPA
		$aprPend1raEt = array();
		$aprPend1raEt = ListarAprendizajesXEstudXECXEstado($MiConexion,$idEstud,$ListadoEspaciosCurriculares[$i]['ID'],1,2);
		$cantAprPend1 = count($aprPend1raEt);
		if ($cantAprPend1>0) {
			$todosAP1raEt="";
			for ($j=0; $j < $cantAprPend1; $j++) { 
				if ($j==$cantAprPend1-1) {
					$todosAP1raEt=$todosAP1raEt.$aprPend1raEt[$j]['DENOMINACION'];
				} else {
					$todosAP1raEt=$todosAP1raEt.$aprPend1raEt[$j]['DENOMINACION']."\n";
				}
			}
		}
		//SI ES LA EMISIÓN FINAL DEL INFORME (2daEtapa) PEDIR EL LISTADO DE LA 2DA ETAPA
		if ($etapa == 2) {
			//LISTADO DE LOS APRENDIZAJES LOGRADOS DE LA 2DA ETAPA
    		$aprlogrados2daEt = array();
			$aprlogrados2daEt = ListarAprendizajesXEstudXECXEstado($MiConexion,$idEstud,$ListadoEspaciosCurriculares[$i]['ID'],2,1);
			$cantAprLogr2 = count($aprlogrados2daEt);
			if ($cantAprLogr2>0) {
				$todosAL2daEt = "";
				for ($j=0; $j < $cantAprLogr2; $j++) { 
					if ($j==$cantAprLogr2-1) {
						$todosAL2daEt = $todosAL2daEt.$aprlogrados2daEt[$j]['DENOMINACION'];
					} else {
						$todosAL2daEt = $todosAL2daEt.$aprlogrados2daEt[$j]['DENOMINACION']."\n";
					}
				}
			}
			//LISTADO DE LOS APRENDIZAJES PENDIENTES DE LA 2DA ETAPA
			$aprPend2daEt = array();
			$aprPend2daEt = ListarAprendizajesXEstudXECXEstado($MiConexion,$idEstud,$ListadoEspaciosCurriculares[$i]['ID'],2,2);
			$cantAprPend2 = count($aprPend2daEt);
			if ($cantAprPend2>0) {
				$todosAP2daEt = "";
				for ($j=0; $j < $cantAprPend2; $j++) { 
					if ($j==$cantAprPend2-1) {
						$todosAP2daEt= $todosAP2daEt.$aprPend2daEt[$j]['DENOMINACION'];
					} else {
						$todosAP2daEt= $todosAP2daEt.$aprPend2daEt[$j]['DENOMINACION']."\n";
					}
				}
			}
		}
    	//Encabezados de la tabla
    	$posYIni = $pdf->GetY();
    	if ($posYIni>249) {
    		$pdf->Cell(190,20,'',0,'C',false);
    		$pdf->Ln();
    		$posYIni = 30;
    	}
    	$pdf->Cell(190,7,utf8_decode($ListadoEspaciosCurriculares[$i]['NOMBREESPACCURRIC']),1,0,'C',false);
    	$pdf->Ln();
    	$posXIni = $pdf->GetX();
    	$posYIni = $pdf->GetY();
    	if ($posYIni>249) {
    		$pdf->Cell(190,20,'',0,'C',false);
    		$pdf->Ln();
    		$posYIni = 30;
    	}
    	$pdf->MultiCell(19,7,'Primer Etapa','LTR','C',false);
    	$posYPE = $pdf->GetY();
    	$pdf->SetXY($posXIni+19,$posYIni);
    	$pdf->MultiCell(25,6,'Aprendizajes Logrados','LTR','C',false);
    	$posYALPE = $pdf->GetY();
    	$pdf->SetXY($posXIni+44,$posYIni);
    	$posYTALPE= 0;
    	if ($cantAprLogr1>0) {
    		$pdf->MultiCell(146,6,utf8_decode($todosAL1raEt),'LTR','L',false);
    		$posYTALPE = $pdf->GetY();
    		if ($posYTALPE > $posYALPE && $posYTALPE >$posYPE) {
    			$difY1 = $posYTALPE - $posYPE;
    			$difY2 = $posYTALPE - $posYALPE;
    			$pdf->SetXY($posXIni,$posYPE);
    			$pdf->MultiCell(19,$difY1,'','LR','C',false);
    			$pdf->SetXY($posXIni+19,$posYALPE);
    			$pdf->MultiCell(25,$difY2,'','LR','C',false);
    		} else {
    			if ($posYTALPE < $posYALPE && $posYTALPE < $posYPE) {
    				$difY = $posYTALPE - 30;
    				$pdf->SetXY($posXIni,30);
    				$pdf->MultiCell(19,$difY,'','LR','C',false);
    				$pdf->SetXY($posXIni+19,30);
    				$pdf->MultiCell(25,$difY,'','LR','C',false);
    			} else {
    				if ($posYTALPE < $posYALPE && $posYTALPE >$posYPE) {
    					$difY1 = $posYALPE - $posYPE;
    					$difY2 = $posYALPE - $posYTALPE;
    					$pdf->SetXY($posXIni,$posYPE);
    					$pdf->MultiCell(19,$difY1,'','LR','C',false);
    					$pdf->SetXY($posXIni+44,$posYTALPE);
    					$pdf->MultiCell(146,$difY2,'','LR','C',false);
    				}
    			}
    		}
    	} else {
    		$pdf->MultiCell(146,14,"",1,'L',false);
    	}
    	$pdf->SetX($posXIni+19);
    	$pdf->Cell(171,0,'','T');
    	$pdf->Ln();
    	$posYF = $pdf->GetY();
    	if ($posYF>249) {
    		$pdf->Cell(190,20,'',0,'C',false);
    		$pdf->Ln();
    		$posYF = 30;
    	}
    	$pdf->SetXY($posXIni,$posYF);
    	$pdf->MultiCell(19,14,'','LR','C',false);
    	$pdf->SetXY($posXIni+19,$posYF);
    	$pdf->MultiCell(25,6,'Aprendizajes Pendientes','LTR','C',false);
    	$posYAPPE = $pdf->GetY();
    	$pdf->SetXY($posXIni+44,$posYF);
    	if ($cantAprPend1 > 0) {
    		$pdf->MultiCell(146,6,utf8_decode($todosAP1raEt),'LTR','L',false);
    		$posYTAPPE = $pdf->GetY();
    		if ($posYTAPPE > $posYAPPE && $posYTAPPE >$posYPE) {
    			$difY1 = $posYTAPPE - $posYPE;
    			$difY2 = $posYTAPPE - $posYAPPE;
    			$pdf->SetXY($posXIni,$posYPE);
    			$pdf->MultiCell(19,$difY1,'','LR','C',false);
    			$pdf->SetXY($posXIni+19,$posYAPPE);
    			$pdf->MultiCell(25,$difY2,'','LR','C',false);
    		} else {
    			if ($posYTAPPE < $posYAPPE && $posYTAPPE < $posYPE) {
    				$difY = $posYTAPPE - 30;
    				$pdf->SetXY($posXIni,30);
    				$pdf->MultiCell(19,$difY,'','LR','C',false);
    				$pdf->SetXY($posXIni+19,30);
    				$pdf->MultiCell(25,$difY,'','LR','C',false);
    			} else {
    				if ($posYTAPPE < $posYAPPE && $posYTAPPE >$posYPE) {
    					$difY1 = $posYAPPE - $posYPE;
    			$difY2 = $posYAPPE - $posYTAPPE;
    			$pdf->SetXY($posXIni,$posYPE);
    			$pdf->MultiCell(19,$difY1,'','LR','C',false);
    			$pdf->SetXY($posXIni+44,$posYTAPPE);
    			$pdf->MultiCell(146,$difY2,'','LR','C',false);
    				}
    			}
    		}
    	} else {
    		$pdf->MultiCell(146,14,"",1,'L',false);
    	}
    	$pdf->MultiCell(190,0,'','T');
    	$posXIni = $pdf->GetX();
    	$posYIni = $pdf->GetY();
    	if ($posYIni>249) {
    		$pdf->Cell(190,20,'',0,'C',false);
    		$pdf->Ln();
    		$posYIni = 30;
    	}
    	$pdf->SetXY($posXIni,$posYIni);
    	$pdf->MultiCell(19,7,'Segunda Etapa','LTR','C',false);
    	$posYSE = $pdf->GetY();
    	$pdf->SetXY($posXIni+19,$posYIni);
    	$pdf->MultiCell(25,6,'Aprendizajes Logrados','LTR','C',false);
    	$posYALSE = $pdf->GetY();
    	$pdf->SetXY($posXIni+44,$posYIni);
    	if ($cantAprLogr2>0) {
    		$pdf->MultiCell(146,6,utf8_decode($todosAL2daEt),'LTR','L',false);
    		$posYTALSE = $pdf->GetY();
    		if ($posYTALSE > $posYALSE && $posYTALSE >$posYSE) {
    			$difY1 = $posYTALSE - $posYSE;
    			$difY2 = $posYTALSE - $posYALSE;
    			$pdf->SetXY($posXIni,$posYSE);
    			$pdf->MultiCell(19,$difY1,'','LR','C',false);
    			$pdf->SetXY($posXIni+19,$posYALSE);
    			$pdf->MultiCell(25,$difY2,'','LR','C',false);
    		} else {
    			if ($posYTALSE < $posYALSE && $posYTALSE < $posYSE) {
    				$difY = $posYTALSE - 30;
    				$pdf->SetXY($posXIni,30);
    				$pdf->MultiCell(19,$difY,'','LR','C',false);
    				$pdf->SetXY($posXIni+19,30);
    				$pdf->MultiCell(25,$difY,'','LR','C',false);
    			} else {
    				if ($posYTALSE < $posYALSE && $posYTALSE >$posYSE) {
    					$difY1 = $posYALSE - $posYSE;
    			$difY2 = $posYALSE - $posYTALSE;
    			$pdf->SetXY($posXIni,$posYSE);
    			$pdf->MultiCell(19,$difY1,'','LR','C',false);
    			$pdf->SetXY($posXIni+44,$posYTALSE);
    			$pdf->MultiCell(146,$difY2,'','LR','C',false);
    				}
    			}
    		}
    	} else {
    		$pdf->MultiCell(146,14,"",1,'L',false);
    	}
    	$pdf->SetX($posXIni+19);
    	$pdf->Cell(171,0,'','T');
    	$pdf->Ln();
    	$posYF = $pdf->GetY();
    	if ($posYF>249) {
    		$pdf->Cell(190,20,'',0,'C',false);
    		$pdf->Ln();
    		$posYF = 30;
    	}
    	$pdf->SetXY($posXIni,$posYF);
    	$pdf->MultiCell(19,14,'','LR','C',false);
    	$pdf->SetXY($posXIni+19,$posYF);
    	$pdf->MultiCell(25,6,'Aprendizajes Pendientes','LTR','C',false);
    	$posYAPSE = $pdf->GetY();
    	$pdf->SetXY($posXIni+44,$posYF);
    	if ($cantAprPend2 > 0) {
    		$pdf->MultiCell(146,6,utf8_decode($todosAP2daEt),'LTR','L',false);
    		$posYTAPSE = $pdf->GetY();
    		if ($posYTAPSE > $posYAPSE && $posYTAPSE >$posYSE) {
    			$difY1 = $posYTAPSE - $posYSE;
    			$difY2 = $posYTAPSE - $posYAPSE;
    			$pdf->SetXY($posXIni,$posYSE);
    			$pdf->MultiCell(19,$difY1,'','LR','C',false);
    			$pdf->SetXY($posXIni+19,$posYAPSE);
    			$pdf->MultiCell(25,$difY2,'','LR','C',false);
    		} else {
    			if ($posYTAPSE < $posYAPSE && $posYTAPSE < $posYSE) {
    				$difY = $posYTAPSE - 30;
    				$pdf->SetXY($posXIni,30);
    				$pdf->MultiCell(19,$difY,'','LR','C',false);
    				$pdf->SetXY($posXIni+19,30);
    				$pdf->MultiCell(25,$difY,'','LR','C',false);
    			} else {
    				if ($posYTAPSE < $posYAPSE && $posYTAPSE >$posYSE) {
    					$difY1 = $posYAPSE - $posYSE;
    			$difY2 = $posYAPSE - $posYTAPSE;
    			$pdf->SetXY($posXIni,$posYSE);
    			$pdf->MultiCell(19,$difY1,'','LR','C',false);
    			$pdf->SetXY($posXIni+44,$posYTAPSE);
    			$pdf->MultiCell(146,$difY2,'','LR','C',false);
    				}
    			}
    		}
    	} else {
    		$pdf->MultiCell(146,14,"",1,'L',false);
    	}
    	$pdf->MultiCell(190,0,'','T');
    	$pdf->Ln(10);
    }
    

	$pdf->OutPut(utf8_decode('funciones/Informes/InformeCyA'.$apeEstudiante.$nomEstudiante.'.pdf'),'F');
	ob_end_flush();
} 
}
?>