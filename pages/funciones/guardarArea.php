<?php
function areaNuevo($vConexion,$Denominacion){

	$SQL_Insert="INSERT INTO areas (Denominacion) VALUES ('$Denominacion')";

	if(!mysqli_query($vConexion,$SQL_Insert)) {
		die('<h4>Error al intentar guardar el Área nuevo.</h4>');
	}

	return true;
}
?>