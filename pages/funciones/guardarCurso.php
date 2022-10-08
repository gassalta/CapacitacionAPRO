<?php
function cursoNuevo($vConexion,$Anio,$Division){

	$SQL_Insert="INSERT INTO cursos (Anio, Division) VALUES ('$Anio', '$Division')";

	if(!mysqli_query($vConexion,$SQL_Insert)) {
		die('<h4>Error al intentar guardar el Curso nuevo.</h4>');
	}

	return true;
}

function modificarCurso($vConexion,$Id,$Anio,$Division){
	$SQL = "UPDATE dbcalificacionesproa.cursos SET Anio='$Anio' , Division='$Division' WHERE cursos.Id='$Id'";
	if(!mysqli_query($vConexion, $SQL)){
		die('<h4>Error al intentar modificar el Curso.</h4>');
	}
	return true;
}
?>