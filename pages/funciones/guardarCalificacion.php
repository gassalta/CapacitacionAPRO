<?php
function calificacionNueva($vConexion,$Estudiante,$Evaluacion,$Calificacion){

	$SQL_Insert="INSERT INTO evaluacionesxestudiante (estudiante,evaluacion,calificacion) VALUES ('$Estudiante' , '$Evaluacion', '$Calificacion')";

	if(!mysqli_query($vConexion,$SQL_Insert)) {
		die('<h4>Error al intentar guardar la calificación.</h4>');
	}

	return true;
}

function modificarCalificacion($vConexion,$Id,$Calificacion){
	$SQL = "UPDATE dbcalificacionesproa.evaluacionesxestudiante SET calificacion='$Calificacion' WHERE evaluacionesxestudiante.id='$Id'";
	if(!mysqli_query($vConexion, $SQL)){
		die('<h4>Error al intentar modificar la calificación.</h4>');
	}
	return true;
}

function guardarDetallesAprendizajes($vConexion,$Aprendizaje,$Estado, $EvaluacionXEstudiante) {
	$SQL = "INSERT INTO detallesaprendizajes (aprendizaje, estado, evaluacionesxestudiante) VALUES ('$Aprendizaje', '$Estado', '$EvaluacionXEstudiante')";
	if(!mysqli_query($vConexion, $SQL)){
		die('<h4>Error al intentar registrar el estado del aprendizaje.</h4>');
	}
	return true;
}

function modificarDetalleAprendizaje($vConexion,$Aprendizaje,$Estado,$EvaluacionXEstudiante){
		$SQL = "UPDATE dbcalificacionesproa.detallesaprendizajes SET estado='$Estado' WHERE aprendizaje='$Aprendizaje' AND evaluacionesxestudiante='$EvaluacionXEstudiante'";
		if(!mysqli_query($vConexion, $SQL)){
			die('<h4>Error al intentar modificar el estado del aprendizaje.</h4>');
		}
	return true;
	}

?>