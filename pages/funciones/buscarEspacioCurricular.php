<?php
	function buscarEspacCurric($vConexion,$id){
		$EspCurr=array();
		$SQL = "SELECT EP.Id, EP.NombreEspacCurric, EP.Curso, C.Anio AS Anio, C.Division AS Division, EP.Area
               FROM espacioscurriculares EP, cursos C
                WHERE EP.Id='$id' AND EP.Curso=C.Id";
                 $rs = mysqli_query($vConexion, $SQL);
                if($data = mysqli_fetch_array($rs)) {
                	$EspCurr['ID'] = $data['Id'];
            		$EspCurr['NOMBREESPACCURRIC'] = $data['NombreEspacCurric'];
                    $EspCurr['CURSO'] = $data['Curso'];
            		$EspCurr['ANIO'] = $data['Anio'];
            		$EspCurr['DIVISION'] = $data['Division'];
                    $EspCurr['AREA'] = $data['Area'];
                }
        return $EspCurr;
	}

	function buscarEspacCurricSimple($vConexion,$id){
		$EspCurr=array();
		$SQL = "SELECT Id, NombreEspacCurric, Curso, Area
               FROM espacioscurriculares
                WHERE Id='$id'";
                 $rs = mysqli_query($vConexion, $SQL);
                if($data = mysqli_fetch_array($rs)) {
                	$EspCurr['ID'] = $data['Id'];
            		$EspCurr['NOMBREESPACCURRIC'] = $data['NombreEspacCurric'];
                    $EspCurr['CURSO'] = $data['Curso'];
                    $EspCurr['AREA'] = $data['Area'];
                }
        return $EspCurr;
	}

	function eliminarElEspCurr($vConexion,$id){
		$SQL = "DELETE FROM dbcalificacionesproa.espacioscurriculares WHERE Id='$id'";
		if (mysqli_query($vConexion, $SQL)) {
			return "El Espacio Curricular se eliminó correctamente";
		} else {
			return "Error al intentar eliminar Espacio Curricular";
		}
	}
    
    function buscarEspacCurricXNombre($vConexion,$EC){
		$EspCurr=array();
		$SQL = "SELECT Id, NombreEspacCurric, Curso, Area
               FROM espacioscurriculares
                WHERE NombreEspacCurric='$EC'";
                 $rs = mysqli_query($vConexion, $SQL);
                if($data = mysqli_fetch_array($rs)) {
                	$EspCurr['ID'] = $data['Id'];
            		$EspCurr['NOMBREESPACCURRIC'] = $data['NombreEspacCurric'];
                    $EspCurr['CURSO'] = $data['Curso'];
                    $EspCurr['AREA'] = $data['Area'];
                }
        return $EspCurr;
	}
?>