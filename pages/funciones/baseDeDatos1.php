<?php
/////////////////////////////////CONSULTAS ////////////////// /////////////////////////////

function consultaEspaciosCurriculares(){
	$consultaEspCurriculares="Select * from espacioscurriculares ORDER BY NombreEspacCurric	ASC";
	$Estado=mysqli_query(ConexionBD(),$consultaEspCurriculares);
	return $Estado;
}
function consultaEspaciosCurricularesxID($EspCurr){
	$consultaContenidos="Select * from espacioscurriculares where Id='$EspCurr'";
	$Estado=mysqli_query(ConexionBD(),$consultaContenidos);
	return $Estado;
}
function consultaEspaciosCurricularesxDocente($Docente){
	$consultaContenidos="Select * from espacioscurriculares where Docente='$Docente'";
	$Estado=mysqli_query(ConexionBD(),$consultaContenidos);
	return $Estado;
}
function consultaContenido(){
	$consultaContenidos="Select * from contenidos";
	$Estado=mysqli_query(ConexionBD(),$consultaContenidos);
	return $Estado;
}
function consultaContenidoId($Id){
	$consultaContenidos="Select * from contenidos where id=$Id";
	$Estado=mysqli_query(ConexionBD(),$consultaContenidos);
	return $Estado;
}


function consultaAprendizajexID($Aprendizaje){
	$consultaAprendizaje="Select * from aprendizajes where id='$Aprendizaje'";
	$Estado=mysqli_query(ConexionBD(),$consultaAprendizaje);
	return $Estado;
}


function consultaContenidoxEspaciosCurriculares($EspCurric){
	$consultaEspCurriculares="Select * from contenidos where espaciocurricular='$EspCurric'";
	$Estado=mysqli_query(ConexionBD(),$consultaEspCurriculares);
	return $Estado;
}
function consultaEvaluacionxEstudiante($id){
	$consultaEvaluacionxEstudiante="Select * from evaluacionesxestudiante where id='$id'";
	$Estado=mysqli_query(ConexionBD(),$consultaEvaluacionxEstudiante);
	return $Estado;
}
function consultaEstudiante($id){
	$consultaEvaluacionxEstudiante="Select nombre, apellido from estudiantes where id='$id'";
	$Estado=mysqli_query(ConexionBD(),$consultaEvaluacionxEstudiante);
	$Fila=mysqli_fetch_array($Estado);
	return $Fila;
}

function consultaNotaFinal($IdEstudiante,$IdEspCurr){
	$consultaEvaluacionxEstudiante="Select * from calificacionfinalxespcurr where estudiante='$IdEstudiante' and espacioCurricular='$IdEspCurr'";
	$Estado=mysqli_query(ConexionBD(),$consultaEvaluacionxEstudiante);
	//$Fila=mysqli_fetch_array($Estado);
	return $Estado;
}

function consultaCalificacionFinal($IdEstudiante,$IdEspCurr)
{ $Consulta="Select a.calificacion,b.apellido,b.nombre,c.NombreEspacCurric
from calificacionfinalxespcurr as a
inner join estudiantes as b on b.id=a.estudiante
inner join espacioscurriculares as c on c.Id=a.espacioCurricular
where a.estudiante='$IdEstudiante' and a.espacioCurricular='$IdEspCurr'";
$ConsultaSql=mysqli_query(ConexionBD(),$Consulta);

return $ConsultaSql;


}



////////////////////////VISTAS///////////////////////////////

function consultaEspacioContenidosAprendizajes($Contenido){
	$consultaEspCurriculares="Select * from espacios_contenidos_aprendizajes where contenido='$Contenido'";
	$Estado=mysqli_query(ConexionBD(),$consultaEspCurriculares);
	return $Estado;
}
function consultaEspacioContenidosAprendizajesA($Aprendizaje){
	$consultaEspCurriculares="Select * from espacios_contenidos_aprendizajes where id='$Aprendizaje'";
	$Estado=mysqli_query(ConexionBD(),$consultaEspCurriculares);
	return $Estado;
}

function consultaEstudianteCursoxEspacioC($Espacio){
	$consultaEstudiante="Select * from estudiantecursoespacioc where idEspCurr='$Espacio'";
	$Estado=mysqli_query(ConexionBD(),$consultaEstudiante);
	return $Estado;
}
function consultaAlumnoEvaluacionAprendizajeCalificacion($Estudiante){
	$Listado=array();

    $SQL = "Select * from evaluacion_aprendizaje_calificacion where idEst='$Estudiante' group by IdEvEst";

     $rs = mysqli_query(ConexionBD(), $SQL);
	
	
	      
     $i=0;
    while ($Fila = mysqli_fetch_array($rs)) {
            $Listado[$i]['IdEvEst'] = $Fila['IdEvEst'];
            $Listado[$i]['IdEv'] = $Fila['IdEv'];
            $Listado[$i]['Instancia'] = $Fila['Instancia'];
            $Listado[$i]['Fecha'] = $Fila['Fecha'];
            $Listado[$i]['idEst'] =$Fila['idEst'];;
            $Listado[$i]['Apellido'] = $Fila['Apellido'];
            $Listado[$i]['Nombre'] = $Fila['Nombre'];
            $Listado[$i]['IdEspCurr'] = $Fila['IdEspCurr'];
            $Listado[$i]['NombreEspCurr'] = $Fila['NombreEspCurr'];
            $Listado[$i]['Id_Estado'] = $Fila['Id_Estado'];
            $Listado[$i]['IdAprend'] =$Fila['IdAprend'];


 $Listado[$i]['Aprendizajes'] = $Fila['Aprendizajes'];
            $Listado[$i]['Estado'] =$Fila['Estado'];

            $i++;
    }
					
    return $Listado;
	
	
}
function consultaAprendizajes($Estudiante,$ECurric){
	$Listado=array();

    $SQL = "Select Aprendizajes,Estado,IdEspCurr from evaluacion_aprendizaje_calificacion where idEst='$Estudiante' and IdEspCurr='$ECurric' group by IdAprend";

    $rs = mysqli_query(ConexionBD(), $SQL);
	
	
	      
    $i=0;
    while ($Fila = mysqli_fetch_array($rs)) {
            $Listado[$i]['Aprendizajes'] = $Fila['Aprendizajes'];
            $Listado[$i]['Estado'] = $Fila['Estado'];
   
            $i++;
    }
					
    return $Listado;
	
	
}

///////////////////UPDATE/////////////////////
function UpdateAprendizaje($Id,$Aprendizaje){
   $cambio="Update aprendizajes set denominacion='$Aprendizaje' where id='$Id'";
   $SqlCambio=mysqli_query(ConexionBD(),$cambio);
}

function UpdateEliminarAprendizaje($Id){
   $cambio="Update aprendizajes set estado='1' where id='$Id'";
   $SqlCambio=mysqli_query(ConexionBD(),$cambio);
}
function UpdateNotaFinal($Estudiante,$EspCurr,$Nota)
{  $Sql="Update calificacionfinalxespcurr set calificacion='$Nota' where estudiante='$Estudiante' and espacioCurricular='$EspCurr'";
   $SqlInsert=mysqli_query(ConexionBD(),$Sql);
   //return $SqlInsert;
}

////////////////INSERT/////////////////

function InsertAprendizaje($Contenido,$Denominacion)
{  $Sql="Insert into aprendizajes(contenido,denominacion) values ('$Contenido','$Denominacion')";
   $SqlInsert=mysqli_query(ConexionBD(),$Sql);
   //return $SqlInsert;
}
function InsertNotaFinal($Estudiante,$EspCurr,$Nota)
{  $Sql="Insert into calificacionfinalxespcurr(estudiante,espacioCurricular,calificacion) values ('$Estudiante','$EspCurr','$Nota')";
   $SqlInsert=mysqli_query(ConexionBD(),$Sql);
   //return $SqlInsert;
}


?>