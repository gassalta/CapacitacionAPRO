<?php
function listarNotasXEtapaXEstud($vConexion,$idEtapa,$idEstud){
	$fechaAct = date('Y');
	$Listado=array();

    	$SQL = "SELECT EXE.calificacion, E.espacioCurricular
        FROM evaluacionesxestudiante EXE, evaluaciones E
        WHERE EXE.estudiante = '$idEstud' AND E.instancia = '$idEtapa' AND YEAR(E.fecha)='$fechaAct' AND EXE.evaluacion=E.id
        ORDER BY E.fecha";

     $rs = mysqli_query($vConexion, $SQL);
        
     $i=0;
    while ($data = mysqli_fetch_array($rs)) {
            $Listado[$i]['CALIFICACION'] = $data['calificacion'];
            $Listado[$i]['ESPACIOCURRICULAR'] = $data['espacioCurricular'];
            
            $i++;
    }
    return $Listado;
}

//LISTADO DE CALIFICACIONES FINALES
function listarNotasFinalesXEstudiante($vConexion,$idEstud, $idCurso){
	$Listado=array();

    	$SQL = "SELECT CFEC.calificacion, CFEC.espacioCurricular
        FROM calificacionfinalxespcurr CFEC, espacioscurriculares EC
        WHERE CFEC.estudiante = '$idEstud' AND CFEC.espacioCurricular=EC.Id AND EC.Curso='$idCurso'
        ORDER BY CFEC.espacioCurricular";

     $rs = mysqli_query($vConexion, $SQL);
        
     $i=0;
    while ($data = mysqli_fetch_array($rs)) {
            $Listado[$i]['CALIFICACION'] = $data['calificacion'];
            $Listado[$i]['ESPACIOCURRICULAR'] = $data['espacioCurricular'];
            
            $i++;
    }
    return $Listado;
}

	?>