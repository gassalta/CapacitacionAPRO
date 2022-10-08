<?php
	function buscarCurso($vConexion,$id){
		$Curso=array();
		$SQL = "SELECT id, anio, division
               FROM cursos
                WHERE id='$id'";
                 $rs = mysqli_query($vConexion, $SQL);
                if($data = mysqli_fetch_array($rs)) {
                	$Curso['ID'] = $data['id'];
            		$Curso['ANIO'] = $data['anio'];
            		$Curso['DIVISION'] = $data['division'];            		
                }
        return $Curso;
	}

    function eliminarElCurso($vConexion,$id){
        $SQL = "DELETE FROM dbcalificacionesproa.cursos WHERE Id='$id'";
        if (mysqli_query($vConexion, $SQL)) {
            return "El Curso se eliminó correctamente";
        } else {
            return "Error al intentar eliminar Curso";
        }
    }
?>