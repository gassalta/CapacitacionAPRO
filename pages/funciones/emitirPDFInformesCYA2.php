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
    	$pdf->Cell(190,7,utf8_decode($ListadoEspaciosCurriculares[$i]['NOMBREESPACCURRIC']),1,0,'C',false);
    	$pdf->Ln();
    	$pdf->Cell(95,7,'Primer Etapa',1,0,'C',false);
    	$pdf->Cell(95,7,'Segunda Etapa',1,0,'C',false);   
    	$pdf->Ln();
    	$pdf->Cell(47.5,6,'Aprendizajes Logrados',1,0,'C',false);
    	$pdf->Cell(47.5,6,'Aprendizajes Pendientes',1,0,'C',false);
    	$pdf->Cell(47.5,6,'Aprendizajes Logrados',1,0,'C',false);
    	$pdf->Cell(47.5,6,'Aprendizajes Pendientes',1,0,'C',false);
    	$pdf->Ln();
    	$posX = 0;
    	$posY =0;
    	if ($cantAprLogr1==0 && $cantAprPend1==0 && $cantAprLogr2==0 && $cantAprPend2==0) {
    		$pdf->Cell(47.5,6,'',1,'L',false);
    		$pdf->Cell(47.5,6,'',1,'L',false);
    		$pdf->Cell(47.5,6,'',1,'L',false);
    		$pdf->Cell(47.5,6,'',1,'L',false);
    	} else {
    		$posXIni = $pdf->GetX();
    		$posYIni = $pdf->GetY();
    		$difY1 = 0;
    		$difY2 = 0;
    		$difY3 = 0;
    		$difY4 = 0;
    		if ($cantAprLogr1 > 0) {
    			//MOSTRAR LOS APRENDIZAJES LOGRADOS EN EL ESPACIO CURRICULAR DE LA 1RA ETAPA
    			$posX = $pdf->GetX();
    			$posY = $pdf->GetY();
    			$pdf->MultiCell(47.5,6,utf8_decode($todosAL1raEt),'LR','L',false);
    			$difY1 = $pdf->GetY() - $posY;
    			$pdf->SetXY($posX+47.5,$posY);
    		} else {
    			$pdf->Cell(47.5,6,'','LR','L',false);
    		}
    		if ($cantAprPend1 > 0) {
    			//MOSTRAR LOS APRENDIZAJES PENDIENTES EN EL ESPACIO CURRICULAR DE LA 1RA ETAPA
    			$posX = $pdf->GetX();
    			$posY = $pdf->GetY();
    			$pdf->MultiCell(47.5,6,utf8_decode($todosAP1raEt),'LR','L',false);
    			$difY2 = $pdf->GetY() - $posY;
    			if ($difY1>$difY2) {
    				$pdf->MultiCell(47.5,$difY1-$difY2,'','LR','L',false);
    			}
    			$pdf->SetXY($posX+47.5,$posY);
    		} else {
    			if ($difY1>0) {
    				$pdf->Cell(47.5,$difY1,'','LR','L',false);
    			} else {
    				$pdf->Cell(47.5,6,'','LR','L',false);
    			}
    		}
    		if ($etapa == 2) {
    			if ($cantAprLogr2 > 0) {
    				//MOSTRAR LOS APRENDIZAJES LOGRADOS EN EL ESPACIO CURRICULAR DE LA 2DA ETAPA
    				$posX = $pdf->GetX();
    				$posY = $pdf->GetY();
    				$pdf->MultiCell(47.5,6,utf8_decode($todosAL2daEt),'LR','L',false);
    				$difY3 = $pdf->GetY() - $posY;
    				if ($difY1>=$difY3 || $difY2>=$difY3) {
    					$alt = $difY3;
    					if ($difY1>$difY2) {
    						$alt = $difY1;
    					} else {
    						$alt = $difY2;
    					}
    					$pdf->MultiCell(47.5,$alt-$difY3,'','LR','L',false);
    				}
    				$pdf->SetXY($posX+47.5,$posY);
    			} else {
    				$alt = 6;
    				if ($difY1>$alt || $difY2>$alt) {
    					if ($difY1>$difY2) {
    						$alt = $difY1;
    					} else {
    						$alt = $difY2;
    					}
    				}
    				$pdf->Cell(47.5,$alt,'','LR','L',false);
    			}
    			if ($cantAprPend2 > 0) {
    				//MOSTRAR LOS APRENDIZAJES PENDIENTES EN EL ESPACIO CURRICULAR DE LA 2DA ETAPA
    				$posY = $pdf->GetY();
    				$posX = $pdf->GetX();
    				$pdf->MultiCell(47.5,6,utf8_decode($todosAP2daEt),'LR','L',false);
    				$difY4 = $pdf->GetY() - $posY;
    				if ($difY1>$difY4 || $difY2>$difY4 || $difY3>$difY4) {
    					$alt = $difY4;
    					if ($difY1>=$difY2 && $difY1>=$difY3) {
    						$alt=$difY1;
    					} else {
    						if ($difY2>=$difY3) {
    							$alt=$difY2;
    						} else {
    							$alt=$difY3;
    						}
    					}
    					$pdf->MultiCell(47.5,$alt-$difY4,'','LR','L',false);
    				}

    			} else {
    				$alt = 6;
    				if ($difY1>$alt || $difY2>$alt || $difY3>$alt) {
    					if ($difY1>=$difY2 && $difY1>=$difY3) {
    						$alt=$difY1;
    					} else {
    						if ($difY2>=$difY3) {
    							$alt=$difY2;
    						} else {
    							$alt=$difY3;
    						}
    					}
    				}
    				$pdf->Cell(47.5,$alt,'','LR','L',false);
    			}
    		} else {
    			$alt = 6;
    			if ($difY1>$alt || $difY2>$alt) {
    				if ($difY1>=$difY2) {
    					$alt = $difY1;
    				} else {
    					$alt = $difY2;
    				}
    			}
    			$pdf->Cell(47.5,$alt,'','LR','L',false);
    			$pdf->Cell(47.5,$alt,'','LR','L',false);
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