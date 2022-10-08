<?php
function nacionalidadNueva($vConexion,$nacion){

	$SQL_Insert="INSERT INTO nacionalidades (nacion) VALUES ('$nacion')";

	if(!mysqli_query($vConexion,$SQL_Insert)) {
		die('<h4>Error al intentar guardar la nueva nacionalidad.</h4>');
	}

	return true;
}
?>