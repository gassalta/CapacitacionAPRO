<?php
function evaluacionNueva($vConexion,$espCurr,$instancia,$fecha){

	$SQL_Insert="INSERT INTO evaluaciones (espacioCurricular,instancia,fecha) VALUES ('$espCurr' , '$instancia', '$fecha')";

	if(!mysqli_query($vConexion,$SQL_Insert)) {
		die('<h4>Error al intentar guardar la nueva evaluación.</h4>');
	}

	return true;
}

function modificarEvaluación($vConexion,$Id,$instancia,$fecha){
	$SQL = "UPDATE dbcalificacionesproa.evaluaciones SET instancia='$instancia' , fecha='$fecha' WHERE evaluaciones.id='$Id'";
	if(!mysqli_query($vConexion, $SQL)){
		die('<h4>Error al intentar modificar la evaluación.</h4>');
	}
	return true;
}

function guardarAprendizajesXEvaluacion($vConexion,$Aprendizaje,$Evaluacion) {
	$SQL = "INSERT INTO aprendizajesxevaluacion (aprendizaje, evaluacion) VALUES ('$Aprendizaje', '$Evaluacion')";
	if(!mysqli_query($vConexion, $SQL)){
		die('<h4>Error al intentar registrar el aprendizaje en la evaluación.</h4>');
	}
	return true;
}

function eliminarElAprendizajeXEvaluacion($vConexion,$Aprendizaje,$Evaluacion){
		$SQL = "DELETE FROM dbcalificacionesproa.aprendizajesxevaluacion WHERE aprendizaje='$Aprendizaje' AND evaluacion='$Evaluacion'";
		if (mysqli_query($vConexion, $SQL)) {
			return "El Aprendizaje se eliminó correctamente de la Evaluacion";
		} else {
			return "Error al intentar eliminar Aprendizaje de la Evaluacion";
		}
	}

?>