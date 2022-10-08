<?php
function barrioNuevo($vConexion,$nombre){

	$SQL_Insert="INSERT INTO barrios (nombre) VALUES ('$nombre')";

	if(!mysqli_query($vConexion,$SQL_Insert)) {
		die('<h4>Error al intentar guardar el nuevo barrio.</h4>');
	}

	return true;
}
?>