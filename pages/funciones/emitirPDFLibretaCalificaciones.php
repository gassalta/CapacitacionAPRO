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
require_once 'informeAsistencia.php';
$totalesAsistencia = array();
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
$totalesAsistencia = contarTotalesAsistencia($MiConexion,$EstudElegido['ID'],$anioActual);
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
require_once 'listarNotas.php';
$notas1raEt = array();
$notas1raEt = listarNotasXEtapaXEstud($MiConexion,1,$idEstud);
$cantNotas1 = count($notas1raEt);
//SI ES LA EMISIÓN FINAL DE LA LIBRETA (2daEtapa) PEDIR EL LISTADO DE LA 2DA ETAPA Y EL DE LAS CALIFICACIONES FINALES
if ($etapa == 2) {
	$notas2daEt=array();
	$notas2daEt = listarNotasXEtapaXEstud($MiConexion,2,$idEstud);
	$cantNotas2 = count($notas2daEt);
	$notasFinales = listarNotasFinalesXEstudiante($MiConexion,$idEstud,$idCurso);
	$cantNotasFinales = count($notasFinales);
}
if (!empty($notas1raEt))
	{
	ob_start();
	//creo pdf vacío
	$pdf = new FPDF();
	//agrego una pagina al pdf
	$pdf ->AddPage();
	//establezco tipo de letra
	$pdf->SetFont('Arial','U',16);
	//Agrego celda con título
	$pdf->Cell(190,10,'LIBRETA DE CALIFICACIONES',0,1,'C');
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
	//Encabezados de la tabla con las notas
    $pdf->Cell(79,7,'Espacio Curricular',1,0,'C',false);
    $pdf->Cell(45,7,'Calif. Primer Etapa',1,0,'C',false);
    $pdf->Cell(45,7,'Calif. Segunda Etapa',1,0,'C',false);
    $pdf->Cell(22,7,'Calif. Final',1,0,'C',false);
    $pdf->Ln();
    
    $pdf->SetFont('Arial','',11);
    //Data
    for ($i=0; $i < $CantidadEspaciosCurriculares; $i++) { 
    	$pdf->Cell(79,6,utf8_decode($ListadoEspaciosCurriculares[$i]['NOMBREESPACCURRIC']),1,0,'L',false);
    	$cantNotasEC = 0;
    	for ($j=0; $j < $cantNotas1; $j++) { 
    		if ($notas1raEt[$j]['ESPACIOCURRICULAR']==$ListadoEspaciosCurriculares[$i]['ID']) {
    			$pdf->Cell(9,6,utf8_decode($notas1raEt[$j]['CALIFICACION']),1,0,'C',false);
    			$cantNotasEC++;
    		}
    	}
    		$dif=5-$cantNotasEC;
    	if ($dif>0) {
    		for ($j=0; $j < $dif; $j++) { 
    			$pdf->Cell(9,6,"",1,0,'C',false);
    		}
    	}
    	
    	//SI ES LA EMISIÓN FINAL DE LA LIBRETA (2daEtapa) MOSTRAR LAS NOTAS DE LA 2DA ETAPA Y LAS NOTAS FINALES, SINO, DEJAR LAS CELDAS VACÍAS
    	if ($etapa == 2) {
    		$cantNotasEC = 0;
    		for ($j=0; $j < $cantNotas2; $j++) { 
    		if ($notas2daEt[$j]['ESPACIOCURRICULAR']==$ListadoEspaciosCurriculares[$i]['ID']) {
    			$pdf->Cell(9,6,utf8_decode($notas2daEt[$j]['CALIFICACION']),1,0,'C',false);
    			$cantNotasEC++;
    		}
    	}
    	$dif=5-$cantNotasEC;
    	if ($dif>0) {
    		for ($j=0; $j < $dif; $j++) { 
    			$pdf->Cell(9,6,"",1,0,'C',false);
    		}
    	}
    	$posic = -1;
    	for ($j=0; $j < $cantNotasFinales; $j++) { 
    		if ($notasFinales[$j]['ESPACIOCURRICULAR']==$ListadoEspaciosCurriculares[$i]['ID']) {
    			$posic=$j;
    		}
    	}
    	if ($posic != -1) {
    		$pdf->Cell(22,6,utf8_decode($notasFinales[$posic]['CALIFICACION']),1,0,'C',false);
    	} else {
    		$pdf->Cell(22,6,"",1,0,'C',false);
    	}
    	} else {
    		for ($j=0; $j < 5; $j++) { 
    			$pdf->Cell(9,6,"",1,0,'C',false);
    		}
    		$pdf->Cell(22,6,"",1,0,'C',false);
    	}
    	$pdf->Ln();
    }
    $pdf->Ln(10);
    // TABLA INASISTENCIAS
    $pdf->Cell(192,7,'INASISTENCIAS',1,0,'C',false);
    $pdf->Ln();
    $pdf->Cell(64,7,'Justificadas: '.$totalesAsistencia['JUSTIFICADAS'],1,0,'C',false);
    $pdf->Cell(64,7,'No Justificadas: '.$totalesAsistencia['INJUSTIFICADAS'],1,0,'C',false);
    $pdf->Cell(64,7,'Total: '.$totalesAsistencia['INASISTENCIAS'],1,0,'C',false);

	$pdf->OutPut(utf8_decode('funciones/Libretas/LibretaCalificaciones'.$apeEstudiante.$nomEstudiante.'.pdf'),'F');
	ob_end_flush();
} 
}
?>