<?php
	function buscarAsistencia($vConexion,$estudiante,$anio,$instancia){
		$Asistencia=array();
		$SQL = "SELECT id, estudiante, anio, instancia, cantInasistencias
               FROM asistencias
                WHERE estudiante='$estudiante' AND anio='$anio' AND instancia='$instancia'";
                 $rs = mysqli_query($vConexion, $SQL);
                if($data = mysqli_fetch_array($rs)) {
                	$Asistencia['ID'] = $data['id'];
            		$Asistencia['ESTUDIANTE'] = $data['estudiante'];
            		$Asistencia['ANIO'] = $data['anio'];
            		$Asistencia['INSTANCIA'] = $data['instancia'];
            		$Asistencia['CANTINASISTENCIAS'] = $data['cantInasistencias'];
            		
                }
        return $Asistencia;
	}
    function buscarAsistenciaXId($vConexion,$id){
        $Asistencia=array();
        $SQL = "SELECT id, estudiante, anio, instancia, cantInasistencias
               FROM asistencias
                WHERE id='$id'";
                 $rs = mysqli_query($vConexion, $SQL);
                if($data = mysqli_fetch_array($rs)) {
                    $Asistencia['ID'] = $data['id'];
                    $Asistencia['ESTUDIANTE'] = $data['estudiante'];
                    $Asistencia['ANIO'] = $data['anio'];
                    $Asistencia['INSTANCIA'] = $data['instancia'];
                    $Asistencia['CANTINASISTENCIAS'] = $data['cantInasistencias'];
                    
                }
        return $Asistencia;
    }

    function contarInasistencias ($vConexion,$idAsistencia){
        $Detalles = array();
        $ContInasist = 0;
                $SQLDetalles = "SELECT situacion FROM detallesasistencias WHERE asistencia='$idAsistencia'";
                $rs2 = mysqli_query($vConexion, $SQLDetalles);
                $j=0;
                while ($data = mysqli_fetch_array($rs2)) {
                    $Detalles[$j]['SITUACION'] = $data['situacion'];    
                    $j++;                
                }
            $CantDetalles = count($Detalles);
            $ContInasist = 0;
            $ContTarde = 0;
            $ContAntic = 0;
            #Si encuentro detalles...
            if ($CantDetalles != 0) {
                #...reviso uno por uno si la situación es "Ausente" (2)
                for ($i=0; $i < $CantDetalles; $i++) { 
                    if ($Detalles[$i]['SITUACION'] == 2) {
                        $ContInasist++;
                    }
                }
            }
            return $ContInasist;
    }

    function contarAsistencias ($vConexion,$idAsistencia){
        $Detalles = array();
        $ContAsist = 0;
                $SQLDetalles = "SELECT situacion FROM detallesasistencias WHERE asistencia='$idAsistencia'";
                $rs2 = mysqli_query($vConexion, $SQLDetalles);
                $j=0;
                while ($data = mysqli_fetch_array($rs2)) {
                    $Detalles[$j]['SITUACION'] = $data['situacion'];    
                    $j++;                
                }
            $CantDetalles = count($Detalles);
            $ContAsist = 0;
            #Si encuentro detalles...
            if ($CantDetalles != 0) {
                #...reviso uno por uno si la situación es "Presente" (1)
                for ($i=0; $i < $CantDetalles; $i++) { 
                    if ($Detalles[$i]['SITUACION'] == 1) {
                        $ContAsist++;
                    }
                }
            }
            return $ContAsist;
    }
    function contarTardes ($vConexion,$idAsistencia){
        $Detalles = array();
        $ContAsist = 0;
                $SQLDetalles = "SELECT situacion FROM detallesasistencias WHERE asistencia='$idAsistencia'";
                $rs2 = mysqli_query($vConexion, $SQLDetalles);
                $j=0;
                while ($data = mysqli_fetch_array($rs2)) {
                    $Detalles[$j]['SITUACION'] = $data['situacion'];    
                    $j++;                
                }
            $CantDetalles = count($Detalles);
            $ContAsist = 0;
            #Si encuentro detalles...
            if ($CantDetalles != 0) {
                #...reviso uno por uno si la situación es "Tarde" (3)
                for ($i=0; $i < $CantDetalles; $i++) { 
                    if ($Detalles[$i]['SITUACION'] == 3) {
                        $ContAsist++;
                    }
                }
            }
            return $ContAsist;
    }

    function contarRA ($vConexion,$idAsistencia){
        $Detalles = array();
        $ContAsist = 0;
                $SQLDetalles = "SELECT situacion FROM detallesasistencias WHERE asistencia='$idAsistencia'";
                $rs2 = mysqli_query($vConexion, $SQLDetalles);
                $j=0;
                while ($data = mysqli_fetch_array($rs2)) {
                    $Detalles[$j]['SITUACION'] = $data['situacion'];    
                    $j++;                
                }
            $CantDetalles = count($Detalles);
            $ContAsist = 0;
            #Si encuentro detalles...
            if ($CantDetalles != 0) {
                #...reviso uno por uno si la situación es "Retiro Anticipado" (4)
                for ($i=0; $i < $CantDetalles; $i++) { 
                    if ($Detalles[$i]['SITUACION'] == 4) {
                        $ContAsist++;
                    }
                }
            }
            return $ContAsist;
    }

    function buscarDetalleAsistencia ($vConexion,$idAsistencia,$fecha) {
        $DetalleAsistencia=array();
        $SQL = "SELECT id, asistencia, fecha, situacion, justificacion
               FROM detallesasistencias
                WHERE asistencia='$idAsistencia' AND fecha='$fecha'";
                 $rs = mysqli_query($vConexion, $SQL);
                if($data = mysqli_fetch_array($rs)) {
                    $DetalleAsistencia['ID'] = $data['id'];
                    $DetalleAsistencia['ASISTENCIA'] = $data['asistencia'];
                    $DetalleAsistencia['FECHA'] = $data['fecha'];
                    $DetalleAsistencia['SITUACION'] = $data['situacion'];
                    $DetalleAsistencia['JUSTIFICACION'] = $data['justificacion'];
                    
                }
        return $DetalleAsistencia;
    }

    function contarInasistenciasJustificadas($vConexion,$idAsistencia){
        $Detalles = array();
        $ContJustificadas = 0;
                $SQLDetalles = "SELECT situacion, justificacion FROM detallesasistencias WHERE asistencia='$idAsistencia'";
                $rs2 = mysqli_query($vConexion, $SQLDetalles);
                $j=0;
                while ($data = mysqli_fetch_array($rs2)) {
                    $Detalles[$j]['SITUACION'] = $data['situacion'];
                    $Detalles[$j]['JUSTIFICACION'] = $data['justificacion'];    
                    $j++;                
                }
            $CantDetalles = count($Detalles);
            $ContJustificadas = 0;
            #Si encuentro detalles...
            if ($CantDetalles != 0) {
                #...reviso uno por uno si la situación es "Ausente" (2) y que justificacion no esté vacio
                for ($i=0; $i < $CantDetalles; $i++) { 
                    if ($Detalles[$i]['SITUACION'] == 2 && !empty($Detalles[$i]['JUSTIFICACION'])) {
                        $ContJustificadas++;
                    }
                }
            }
            return $ContJustificadas;
    }
        
?>