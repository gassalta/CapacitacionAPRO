<?php
	function buscarContenido($vConexion,$id){
		$Contenido=array();
		$SQL = "SELECT C.id, C.espacioCurricular, E.NombreEspacCurric AS espacCurric, C.denominacion
               FROM contenidos C, espacioscurriculares E
                WHERE C.id='$id' AND C.espacioCurricular=E.Id AND C.estado=0";
                 $rs = mysqli_query($vConexion, $SQL);
                if($data = mysqli_fetch_array($rs)) {
                	$Contenido['ID'] = $data['id'];
                    $Contenido['IDESPCUR'] = $data['espacioCurricular'];
            		$Contenido['ESPACCURRIC'] = $data['espacCurric'];
            		$Contenido['DENOMINACION'] = $data['denominacion'];
                }
        return $Contenido;
	}

	function eliminarElContenido($vConexion,$id){
		$SQL = "UPDATE dbcalificacionesproa.contenidos SET estado=1 WHERE contenidos.id='$id'";
		if (mysqli_query($vConexion, $SQL)) {
			return "El contenido se eliminó correctamente";
		} else {
			return "Error al intentar eliminar contenido";
		}
	}
    function contenidoExiste($vConexion,$Denominacion,$EspCurr){
        $Contenido=array();
        $mensaje = "";
        $SQL = "SELECT id
               FROM contenidos
                WHERE denominacion='$Denominacion' AND espacioCurricular='$EspCurr'";
                 $rs = mysqli_query($vConexion, $SQL);
                if($data = mysqli_fetch_array($rs)) {
                    $Contenido['ID'] = $data['id'];
                    $mensaje = "El contenido ingresado ya existe en la base de datos, y su nro de identificacion es: ".$Contenido['ID'];
                }
        
        return $mensaje;
    }
    
    function buscarAprendizaje($vConexion,$id){
        $Contenido=array();
        $SQL = "SELECT *
               FROM aprendizajes
                WHERE id='$id' AND estado=0";
                 $rs = mysqli_query($vConexion, $SQL);
                if($data = mysqli_fetch_array($rs)) {
                    $Contenido['ID'] = $data['id'];
                    $Contenido['CONTENIDO'] = $data['contenido'];
                    $Contenido['DENOMINACION'] = $data['denominacion'];
                }
        return $Contenido;
    }
?>