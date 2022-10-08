<?php
function contarTotalesAsistencia($vConexion,$estudiante,$anio){
	require_once 'buscarAsistencia.php';
	$Asistencia = array();
	$Totales = array();
	$Presentes1 = 0;
	$Inasistencias1 = 0;
	$Justificadas1 = 0;
	$Injustificadas1 = 0;
	$Tardes1 = 0;
	$RA1= 0;
	$Presentes2 = 0;
	$Inasistencias2 = 0;
	$Justificadas2 = 0;
	$Injustificadas2 = 0;
	$Tardes2 = 0;
	$RA2= 0;
	$Asistencia = buscarAsistencia($vConexion,$estudiante,$anio,1);
	if (!empty($Asistencia)) {
		$Presentes1 = contarAsistencias($vConexion,$Asistencia['ID']);
		$Inasistencias1 = contarInasistencias($vConexion,$Asistencia['ID']);
		$Justificadas1 = contarInasistenciasJustificadas($vConexion,$Asistencia['ID']);
		$Injustificadas1 = $Inasistencias1 - $Justificadas1;
		$Tardes1 = contarTardes($vConexion,$Asistencia['ID']);
		$RA1 = contarRA($vConexion,$Asistencia['ID']);

	}
	$Asistencia = buscarAsistencia($vConexion,$estudiante,$anio,2);
	if (!empty($Asistencia)) {
		$Presentes2 = contarAsistencias($vConexion,$Asistencia['ID']);
		$Inasistencias2 = contarInasistencias($vConexion,$Asistencia['ID']);
		$Justificadas2 = contarInasistenciasJustificadas($vConexion,$Asistencia['ID']);
		$Injustificadas2 = $Inasistencias2 - $Justificadas2;
		$Tardes2 = contarTardes($vConexion,$Asistencia['ID']);
		$RA2 = contarRA($vConexion,$Asistencia['ID']);
	}
	//Cargo lista con los datos finales
	$Totales['TOTAL'] = $Presentes1 + $Presentes2 + $Inasistencias1 + $Inasistencias2+$Tardes1+$Tardes2+$RA1+$RA2;
	$Totales['PRESENTES'] = $Presentes1 + $Presentes2;
	$Totales['JUSTIFICADAS'] = $Justificadas1 + $Justificadas2;
	$Totales['INJUSTIFICADAS'] = $Injustificadas1 + $Injustificadas2+(($Tardes1+$Tardes2)/4)+(($RA2+$RA1)/2);
	$Totales['INASISTENCIAS'] = $Inasistencias1 + $Inasistencias2+(($Tardes1+$Tardes2)/4)+(($RA2+$RA1)/2);

	return $Totales;
}
?>