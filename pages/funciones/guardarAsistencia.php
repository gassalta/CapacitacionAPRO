<?php
function GuardarAsistencia($vConexion,$estudiante,$anio,$instancia){

	$SQL_Insert="INSERT INTO asistencias (estudiante,anio,instancia) VALUES ('$estudiante','$anio','$instancia')";

	if(!mysqli_query($vConexion,$SQL_Insert)) {
		die('<h4>Error al intentar guardar la asistencia.</h4>');
	}

	return true;
}

function guardarDetalleAsistencia($vConexion,$asistencia,$fecha,$situacion,$justificacion) {
	$SQL_Insert="INSERT INTO detallesasistencias (asistencia,fecha,situacion,justificacion) VALUES ('$asistencia','$fecha','$situacion','$justificacion')";
	if(!mysqli_query($vConexion,$SQL_Insert)) {
		die('<h4>Error al intentar guardar el detalle de la asistencia.</h4>');
	}

	return true;
}

function guardarAsistenciaYDetalle($vConexion,$estudiante,$anio,$instancia,$fecha,$situacion,$justificacion){
	require_once 'funciones/buscarAsistencia.php';
	$Asistencia = array();
	$Asistencia = buscarAsistencia($vConexion,$estudiante,$anio,$instancia);
	$Cant = count($Asistencia);
	if ($Cant == 0) {
		if(GuardarAsistencia($vConexion,$estudiante,$anio,$instancia)) {
			$Asistencia = buscarAsistencia($vConexion,$estudiante,$anio,$instancia);
		}
	}
	if (!guardarDetalleAsistencia($vConexion,$Asistencia['ID'],$fecha,$situacion,$justificacion)) {
		die('<h4>Error al intentar guardar asistencia con su detalle.</h4>');
	}
	if ($situacion == 2) {
		if (!actualizarCantInasistencias($vConexion,$Asistencia['ID'])) {
		die('<h4>Error al intentar actualizar la cantidad de inasistencias.</h4>');
	}
	}
	return true;
}

function actualizarCantInasistencias ($vConexion,$idAsistencia) {
	require_once 'funciones/buscarAsistencia.php';
	$Asistencia = array();
	$Asistencia = buscarAsistenciaXId($vConexion,$idAsistencia);
	$Cant = count($Asistencia);
	if ($Cant == 0) {
		die('<h4>Asistencia inexistente.</h4>');
	} else {
		$CantInasistencias = contarInasistencias ($vConexion,$idAsistencia);
		if ($CantInasistencias!= 0) {
			$SQL = "UPDATE dbcalificacionesproa.asistencias SET cantInasistencias='$CantInasistencias' WHERE id='$idAsistencia'";
			if(!mysqli_query($vConexion, $SQL)){
		die('<h4>Error al intentar actualizar la cantidad de inasistencias.</h4>');
	}
	return true;
		}
	}
}
?>