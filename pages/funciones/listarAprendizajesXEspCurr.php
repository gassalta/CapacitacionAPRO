<?php 
function Listar_AprendizajesXEC($vConexion,$espCurric) {
    $Listado=array();

    	$SQL = "SELECT A.id, A.denominacion, C.denominacion AS contenido
        FROM aprendizajes A, contenidos C
        WHERE A.contenido=C.id AND espacioCurricular='$espCurric' AND A.estado=0
        ORDER BY contenido AND A.id";

     $rs = mysqli_query($vConexion, $SQL);
        
     $i=0;
    while ($data = mysqli_fetch_array($rs)) {
            $Listado[$i]['ID'] = $data['id'];
            $Listado[$i]['DENOMINACION'] = $data['denominacion'];
            $Listado[$i]['CONTENIDO'] = $data['contenido'];

            $i++;
    }
    return $Listado;
}

function Listar_AprendizajesXECPEval($vConexion,$espCurric,$Evaluacion) {
    $Listado=array();

        $SQL = "SELECT A.id, A.denominacion, C.denominacion AS contenido, AE.evaluacion as evaluacion
        FROM aprendizajes A, contenidos C, aprendizajesxevaluacion AE
        WHERE A.contenido=C.id AND espacioCurricular='$espCurric' AND A.estado=0 AND A.id=AE.aprendizaje AND evaluacion='$Evaluacion'
        ORDER BY contenido AND A.id";

     $rs = mysqli_query($vConexion, $SQL);
        
     $i=0;
    while ($data = mysqli_fetch_array($rs)) {
            $Listado[$i]['ID'] = $data['id'];
            $Listado[$i]['DENOMINACION'] = $data['denominacion'];
            $Listado[$i]['CONTENIDO'] = $data['contenido'];
            $Listado[$i]['EVALUACION'] = $data['evaluacion'];
            $i++;
    }
    $Cant = count($Listado);
    $OtroListado = array();
    $OtroListado = Listar_AprendizajesXECPSinEval($vConexion,$espCurric);
    $CantOtro = count($OtroListado);
    $Ultimo = $i;
    if ($CantOtro!= 0) {
        for ($i=0; $i < $CantOtro; $i++) { 
            $esta = 0;
            for ($j=0; $j < $Cant; $j++) { 
                if ($OtroListado[$i]['ID'] == $Listado[$j]['ID']) {
                    $esta = 1;
                }
            }
            if ($esta == 0) {
                $Listado[$Ultimo] = $OtroListado[$i];
                $Ultimo++;
                $Cant = count($Listado);
            }
        }
    } 
    

    return $Listado;
}

function Listar_AprendizajesXECPSinEval($vConexion,$espCurric) {
    $Listado=array();

        $SQL = "SELECT A.id, A.denominacion, C.denominacion AS contenido
        FROM aprendizajes A, contenidos C
        WHERE A.contenido=C.id AND espacioCurricular='$espCurric' AND A.estado=0
        ORDER BY contenido AND A.id";

     $rs = mysqli_query($vConexion, $SQL);
        
     $i=0;
    while ($data = mysqli_fetch_array($rs)) {
            $Listado[$i]['ID'] = $data['id'];
            $Listado[$i]['DENOMINACION'] = $data['denominacion'];
            $Listado[$i]['CONTENIDO'] = $data['contenido'];
            $Listado[$i]['EVALUACION'] = 0;
            $i++;
    }

    return $Listado;
}

function Listar_AprendizajesXECYaEvaluados($vConexion,$espCurric,$Evaluacion) {
    $fechaAct = date('Y');
    $Listado=array();

        $SQL = "SELECT A.id, A.denominacion, C.denominacion AS contenido, AE.evaluacion as evaluacion
        FROM aprendizajes A, contenidos C, aprendizajesxevaluacion AE, evaluaciones E
        WHERE A.contenido=C.id AND C.espacioCurricular='$espCurric' AND A.estado=0 AND A.id=AE.aprendizaje AND evaluacion!='$Evaluacion' AND YEAR(E.fecha)='$fechaAct' AND AE.evaluacion=E.id
        ORDER BY contenido AND A.id";

     $rs = mysqli_query($vConexion, $SQL);
        
     $i=0;
    while ($data = mysqli_fetch_array($rs)) {
            $Listado[$i]['ID'] = $data['id'];
            $Listado[$i]['DENOMINACION'] = $data['denominacion'];
            $Listado[$i]['CONTENIDO'] = $data['contenido'];
            $Listado[$i]['EVALUACION'] = $data['evaluacion'];
            $i++;
    }
    
    return $Listado;
}

function Listar_AprendizajesXEval($vConexion,$espCurric,$Evaluacion) {
    $Listado=array();

        $SQL = "SELECT A.id, A.denominacion, C.denominacion AS contenido, AE.evaluacion as evaluacion
        FROM aprendizajes A, contenidos C, aprendizajesxevaluacion AE
        WHERE A.contenido=C.id AND C.espacioCurricular='$espCurric' AND A.estado=0 AND A.id=AE.aprendizaje AND evaluacion='$Evaluacion'
        ORDER BY contenido AND A.id";

     $rs = mysqli_query($vConexion, $SQL);
        
     $i=0;
    while ($data = mysqli_fetch_array($rs)) {
            $Listado[$i]['ID'] = $data['id'];
            $Listado[$i]['DENOMINACION'] = $data['denominacion'];
            $Listado[$i]['CONTENIDO'] = $data['contenido'];
            $Listado[$i]['EVALUACION'] = $data['evaluacion'];
            $i++;
    }
    
    return $Listado;
}

function ListarAprendizajesLogradosXEvalXEstudiante($vConexion,$Evaluacion,$Estudiante){
    $Listado=array();
    $SQL = "SELECT A.id, A.denominacion, C.denominacion AS contenido, EE.evaluacion as evaluacion, DA.estado AS logopend
        FROM aprendizajes A, contenidos C, evaluacionesxestudiante EE, detallesaprendizajes DA
        WHERE A.contenido=C.id AND A.estado=0 AND A.id=DA.aprendizaje AND evaluacion='$Evaluacion' AND EE.estudiante='$Estudiante' AND DA.evaluacionesxestudiante=EE.id AND DA.estado=1
        ORDER BY contenido, A.id";

     $rs = mysqli_query($vConexion, $SQL);
        
     $i=0;
    while ($data = mysqli_fetch_array($rs)) {
            $Listado[$i]['ID'] = $data['id'];
            $Listado[$i]['DENOMINACION'] = $data['denominacion'];
            $Listado[$i]['CONTENIDO'] = $data['contenido'];
            $Listado[$i]['EVALUACION'] = $data['evaluacion'];
            $i++;
    }
    
    return $Listado;
}

function ListarAprendizajesXEvalXEstudiante($vConexion,$Evaluacion,$Estudiante){
    $Listado=array();
    $SQL = "SELECT A.id, A.denominacion, C.denominacion AS contenido, EE.evaluacion as evaluacion, DA.estado AS logopend
        FROM aprendizajes A, contenidos C, evaluacionesxestudiante EE, detallesaprendizajes DA
        WHERE A.contenido=C.id AND A.estado=0 AND A.id=DA.aprendizaje AND evaluacion='$Evaluacion' AND EE.estudiante='$Estudiante' AND DA.evaluacionesxestudiante=EE.id
        ORDER BY contenido, A.id";

     $rs = mysqli_query($vConexion, $SQL);
        
     $i=0;
    while ($data = mysqli_fetch_array($rs)) {
            $Listado[$i]['ID'] = $data['id'];
            $Listado[$i]['DENOMINACION'] = $data['denominacion'];
            $Listado[$i]['CONTENIDO'] = $data['contenido'];
            $Listado[$i]['EVALUACION'] = $data['evaluacion'];
            $Listado[$i]['LOGOPEND'] = $data['logopend'];
            $i++;
    }
    
    return $Listado;
}

function ListarAprendizajesXEstudXECXEstado($vConexion, $Estudiante, $EspacioCurricular, $Instancia, $Estado){
    $fechaAct = date('Y');
    $Listado=array();
    $SQL = "SELECT A.id, A.denominacion
        FROM aprendizajes A, evaluacionesxestudiante EE, detallesaprendizajes DA, evaluaciones E
        WHERE A.estado=0 AND A.id=DA.aprendizaje AND E.espacioCurricular='$EspacioCurricular' AND E.id=EE.evaluacion AND E.instancia='$Instancia' AND EE.estudiante='$Estudiante' AND DA.evaluacionesxestudiante=EE.id AND DA.estado='$Estado' AND YEAR(E.fecha)='$fechaAct'
        ORDER BY contenido, A.id";

     $rs = mysqli_query($vConexion, $SQL);
        
     $i=0;
    while ($data = mysqli_fetch_array($rs)) {
            $Listado[$i]['ID'] = $data['id'];
            $Listado[$i]['DENOMINACION'] = $data['denominacion'];
            $i++;
    }
    
    return $Listado;
}

function ListarAprendizajesXEstudXECXEstadoSInst($vConexion, $Estudiante, $EspacioCurricular, $Estado){
    $fechaAct = date('Y');
    $Listado=array();
    $SQL = "SELECT IdAprend, Aprendizajes
        FROM evaluacion_aprendizaje_calificacion
        WHERE idEst='$Estudiante' AND IdEspCurr=$EspacioCurricular AND Id_Estado='$Estado'
        ORDER BY IdAprend";

     $rs = mysqli_query($vConexion, $SQL);
        
     $i=0;
    while ($data = mysqli_fetch_array($rs)) {
            $Listado[$i]['ID'] = $data['IdAprend'];
            $Listado[$i]['DENOMINACION'] = $data['Aprendizajes'];
            $i++;
    }
    
    return $Listado;
}
?>