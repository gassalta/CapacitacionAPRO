<?php
function espCurricNuevo($vConexion,$NombreEspacCurric,$Area){

	$SQL_Insert="INSERT INTO espacioscurriculares (NombreEspacCurric, Area) VALUES ('$NombreEspacCurric', '$Area')";

	if(!mysqli_query($vConexion,$SQL_Insert)) {
		die('<h4>Error al intentar guardar el Espacio Curricular nuevo.</h4>');
	}

	return true;
}

function modificarEspCurricular($vConexion,$Id,$NombreEspacCurric,$Area){
	$SQL = "UPDATE dbcalificacionesproa.espacioscurriculares SET NombreEspacCurric='$NombreEspacCurric' , Area='$Area' WHERE espacioscurriculares.Id='$Id'";
	if(!mysqli_query($vConexion, $SQL)){
		die('<h4>Error al intentar modificar el Espacio Curricular.</h4>');
	}
	return true;
}

function guardarEspacCurricXDocente($vConexion,$Id,$Docente) {
	$SQL = "UPDATE dbcalificacionesproa.espacioscurriculares SET Docente='$Docente' WHERE espacioscurriculares.Id='$Id'";
	if(!mysqli_query($vConexion, $SQL)){
		die('<h4>Error al intentar registrar el Espacio Curricular a cargo del docente.</h4>');
	}
	return true;
}

function guardarEspacCurricXCurso($vConexion,$Id,$Curso) {
	$SQL = "UPDATE dbcalificacionesproa.espacioscurriculares SET Curso='$Curso' WHERE espacioscurriculares.Id='$Id'";
	if(!mysqli_query($vConexion, $SQL)){
		die('<h4>Error al intentar registrar el Espacio Curricular en el curso.</h4>');
	}
	return true;
}
?>