<?php
function docenteNuevo($vConexion,$Apellido,$Nombre,$DNI,$FechaNacim,$NroLegajoJunta,$Titulo,$FechaEscalafon,$Categoria,$Contrasenia,$Mail){

	$SQL_Insert="INSERT INTO docentes (Apellido, Nombre, DNI, FechaNacim, NroLegajoJunta, Titulo, FechaEscalafon, Categoria, Contrasenia, Mail) VALUES ('$Apellido' , '$Nombre', '$DNI' , '$FechaNacim' , '$NroLegajoJunta' , '$Titulo' , '$FechaEscalafon' , '$Categoria' , '$Contrasenia' , '$Mail')";

	if(!mysqli_query($vConexion,$SQL_Insert)) {
		die('<h4>Error al intentar guardar el nuevo docente.</h4>');
	}

	return true;
}

function modificarDocente($vConexion,$Id,$Apellido,$Nombre,$DNI,$FechaNacim,$NroLegajoJunta,$Titulo,$FechaEscalafon,$Categoria,$Mail){
	$SQL = "UPDATE docentes SET Apellido='$Apellido' , Nombre='$Nombre' , DNI='$DNI' , FechaNacim='$FechaNacim' , NroLegajoJunta='$NroLegajoJunta' , Titulo='$Titulo' , FechaEscalafon='$FechaEscalafon' , Categoria='$Categoria', Mail='$Mail' WHERE docentes.id='$Id'";
	if(!mysqli_query($vConexion, $SQL)){
		die('<h4>Error al intentar modificar el docente.</h4>');
	}
	return true;
}

function guardarUltConexionDocente($vConexion,$Id) {
	$SQL1 = "UPDATE docentes SET UltIngreso=NOW() WHERE docentes.Id='$Id'";
    if (!mysqli_query($vConexion, $SQL1)) {
    	die('<h4>Error al intentar modificar el docente.</h4>');
    }
    return true;
}
?>