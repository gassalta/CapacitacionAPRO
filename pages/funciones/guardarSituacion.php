<?php
function situacionNueva($vConexion,$Denominacion){

	$SQL_Insert="INSERT INTO situaciones (Denominacion) VALUES ('$Denominacion')";

	if(!mysqli_query($vConexion,$SQL_Insert)) {
		die('<h4>Error al intentar guardar la nueva situación.</h4>');
	}

	return true;
}
?>