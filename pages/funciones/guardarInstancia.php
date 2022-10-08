<?php
function instanciaNueva($vConexion,$Denominacion){

	$SQL_Insert="INSERT INTO instancias (denominacion) VALUES ('$Denominacion')";

	if(!mysqli_query($vConexion,$SQL_Insert)) {
		die('<h4>Error al intentar guardar la nueva instancia.</h4>');
	}

	return true;
}
?>