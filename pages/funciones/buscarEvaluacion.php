<?php
	function buscarEvaluacion($vConexion,$id){
        $Eval=array();
		$SQL = "SELECT E.id, E.espacioCurricular AS idEspaCurri, EC.NombreEspacCurric AS espacCurric, E.instancia AS idInstancia, I.denominacion AS instancia, E.fecha
               FROM evaluaciones E, espacioscurriculares EC, instancias I
                WHERE E.Id='$id' AND E.espacioCurricular=EC.Id AND E.instancia=I.id";
                 $rs = mysqli_query($vConexion, $SQL);
                if($data = mysqli_fetch_array($rs)) {
                	$Eval['ID'] = $data['id'];
                    $Eval['IDESPACURRI'] = $data['idEspaCurri'];
            		$Eval['ESPACCURRIC'] = $data['espacCurric'];
                    $Eval['IDINSTANCIA'] = $data['idInstancia'];
            		$Eval['INSTANCIA'] = $data['instancia'];
            		$Eval['FECHA'] = $data['fecha'];
                }
        return $Eval;
	}

	function eliminarLaEvaluacion($vConexion,$id){
		$SQL = "DELETE FROM dbcalificacionesproa.evaluaciones WHERE id='$id'";
		if (mysqli_query($vConexion, $SQL)) {
			return "La evaluación se eliminó correctamente";
		} else {
			return "Error al intentar eliminar evaluación";
		}
	}
    function evaluacionExiste($vConexion,$fecha,$espCurr){
        $Eval=array();
        $mensaje = "";
        $SQL = "SELECT id
               FROM evaluaciones
                WHERE fecha='$fecha' AND espacioCurricular='$espCurr'";
                 $rs = mysqli_query($vConexion, $SQL);
                if($data = mysqli_fetch_array($rs)) {
                    $Eval['ID'] = $data['id'];
                    $mensaje = "La evaluación que se pretende guardar ya existe en la base de datos, y su nro de identificacion es: ".$Eval['ID'];
                }
        
        return $mensaje;
    }

    function buscarEvalXFecha($vConexion,$fecha,$espCurr){
        $Eval=array();
        $SQL = "SELECT id, espacioCurricular, instancia, fecha
               FROM evaluaciones
                WHERE fecha='$fecha' AND espacioCurricular='$espCurr'";
                 $rs = mysqli_query($vConexion, $SQL);
                if($data = mysqli_fetch_array($rs)) {
                    $Eval['ID'] = $data['id'];
                    $Eval['IDESPACURRI'] = $data['espacioCurricular'];
                    $Eval['INSTANCIA'] = $data['instancia'];
                    $Eval['FECHA'] = $data['fecha'];
                }
        
        return $Eval;
    }
    
    function evaluacionCalificada($vConexion,$id) {
        //Verifica si la evaluacion fue calificada, para ver si se puede modificar o eliminar (Si se calificó no se puede)
        $Eval = array();
        $SQL = "SELECT id, estudiante, evaluacion, calificacion
        FROM evaluacionesxestudiante
        WHERE evaluacion='$id'";
        $rs = mysqli_query($vConexion, $SQL);
        
     $i=0;
    while ($data = mysqli_fetch_array($rs)) {
            $Eval[$i]['ID'] = $data['id'];
            $Eval[$i]['ESTUDIANTE'] = $data['estudiante'];
            $Eval[$i]['EVALUACION'] = $data['evaluacion'];
            $Eval[$i]['CALIFICACION'] = $data['calificacion'];
            $i++;
    }
    return $Eval;
    }

    function evaluacionXEstCalificada($vConexion,$id,$Estudiante) {
        //Verifica si la evaluacion fue calificada, para ver si se puede modificar o eliminar (Si se calificó no se puede)
        $Eval = array();
        $SQL = "SELECT id, estudiante, evaluacion, calificacion
        FROM evaluacionesxestudiante
        WHERE evaluacion='$id' AND estudiante='$Estudiante'";
        $rs = mysqli_query($vConexion, $SQL);
        
     $i=0;
    while ($data = mysqli_fetch_array($rs)) {
            $Eval[$i]['ID'] = $data['id'];
            $Eval[$i]['ESTUDIANTE'] = $data['estudiante'];
            $Eval[$i]['EVALUACION'] = $data['evaluacion'];
            $Eval[$i]['CALIFICACION'] = $data['calificacion'];
            $i++;
    }
    return $Eval;
    }
?>