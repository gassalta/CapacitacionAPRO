<?php
function tutorNuevo($vConexion,$apellido,$nombre,$dni,$telefono,$mail,$ocupacion,$telTrabajo,$nacionalidad){

	$SQL_Insert="INSERT INTO tutores (apellido, nombre, dni, telefono, mail, ocupacion, telTrabajo, nacionalidad) VALUES ('$apellido' , '$nombre', '$dni', '$telefono', '$mail', '$ocupacion', '$telTrabajo', '$nacionalidad')";

	if(!mysqli_query($vConexion,$SQL_Insert)) {
		die('<h4>Error al intentar guardar el nuevo padre, madre, tutor/a.</h4>');
	}

	return true;
}

function modificarTutor($vConexion,$id,$apellido,$nombre,$dni,$telefono,$mail,$ocupacion,$telTrabajo,$nacionalidad){
	$SQL = "UPDATE dbcalificacionesproa.tutores SET apellido='$apellido' , nombre='$nombre' , dni='$dni' , telefono='$telefono', mail='$mail', ocupacion='$ocupacion', telTrabajo='$telTrabajo', nacionalidad='$nacionalidad' WHERE tutores.id='$id'";
	if(!mysqli_query($vConexion, $SQL)){
		die('<h4>Error al intentar modificar el padre, madre, tutor/a.</h4>');
	}
	return true;
}

?>