<?php
function estudianteNuevo($vConexion,$nroLegajo,$nroLibroMatriz,$nroFolio,$apellido,$nombre,$dni,$telefono,$mail,$nacionalidad,$escDeProcedencia,$lugarNacim,$fechaNacim,$domicilio,$barrio,$fechaPreinscripcion,$Padre,$Madre,$Tutor){

	$SQL_Insert="INSERT INTO estudiantes (nroLegajo, nroLibroMatriz, nroFolio, apellido, nombre, dni, telefono, mail, nacionalidad, escDeProcedencia, lugarNacim, fechaNacim, domicilio, barrio, fechaPreinscripcion, Padre, Madre, Tutor) VALUES ('$nroLegajo', '$nroLibroMatriz', '$nroFolio','$apellido' , '$nombre', '$dni', '$telefono', '$mail', '$nacionalidad', '$escDeProcedencia', '$lugarNacim', '$fechaNacim' , '$domicilio' , '$barrio' , '$fechaPreinscripcion', '$Padre', '$Madre', '$Tutor')";

	if(!mysqli_query($vConexion,$SQL_Insert)) {
		die('<h4>Error al intentar guardar el nuevo estudiante.</h4>');
	}

	return true;
}

function modificarEstudiante($vConexion,$id,$nroLegajo,$nroLibroMatriz,$nroFolio,$apellido,$nombre,$dni,$telefono,$mail,$nacionalidad,$escDeProcedencia,$lugarNacim,$fechaNacim,$domicilio,$barrio,$fechaPreinscripcion,$Padre,$Madre,$Tutor){
	$SQL = "UPDATE dbcalificacionesproa.estudiantes SET nroLegajo='$nroLegajo', nroLibroMatriz='$nroLibroMatriz', nroFolio='$nroFolio', apellido='$apellido' , nombre='$nombre' , dni='$dni' , telefono='$telefono', mail='$mail', nacionalidad='$nacionalidad', escDeProcedencia='$escDeProcedencia', lugarNacim='$lugarNacim', fechaNacim='$fechaNacim' , domicilio='$domicilio' , barrio='$barrio' , fechaPreinscripcion='$fechaPreinscripcion', Padre='$Padre', Madre='$Madre', Tutor='$Tutor' WHERE estudiantes.id='$id'";
	if(!mysqli_query($vConexion, $SQL)){
		die('<h4>Error al intentar modificar el estudiante.</h4>');
	}
	return true;
}

function guardarCursoActualEstudiante($vConexion,$id,$curso) {
	$SQL = "UPDATE dbcalificacionesproa.estudiantes SET curso='$curso' WHERE estudiantes.id='$id'";
	if(!mysqli_query($vConexion, $SQL)){
		die('<h4>Error al intentar modificar el estudiante.</h4>');
	}
	return true;
}
?>