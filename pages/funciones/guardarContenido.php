<?php
function contenidoNuevo($vConexion,$EspCurr,$Denominacion){

	$SQL_Insert="INSERT INTO contenidos (espacioCurricular, denominacion) VALUES ('$EspCurr' , '$Denominacion')";

	if(!mysqli_query($vConexion,$SQL_Insert)) {
		die('<h4>Error al intentar guardar el nuevo contenido.</h4>');
	}

	return true;
}

function modificarContenido($vConexion,$Id,$EspCurr,$Denominacion){
	$SQL = "UPDATE dbcalificacionesproa.contenidos SET espacioCurricular='$EspCurr' , denominacion='$Denominacion' WHERE contenidos.id='$Id'";
	if(!mysqli_query($vConexion, $SQL)){
		die('<h4>Error al intentar modificar el contenido.</h4>');
	}
	return true;
}
function modificarContenidoEstado($vConexion,$Id,$EspCurr,$Denominacion){
	$SQL = "UPDATE dbcalificacionesproa.contenidos SET espacioCurricular='$EspCurr' , denominacion='$Denominacion', estado='0' WHERE contenidos.id='$Id'";
	if(!mysqli_query($vConexion, $SQL)){
		die('<h4>Error al intentar modificar el contenido.</h4>');
	}
	return true;
}

?>