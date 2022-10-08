<?php
	function buscarTutor($vConexion,$id){
		$Tutor=array();
		$SQL = "SELECT T.id, T.apellido, T.nombre, T.dni, T.telefono, T.mail, T.ocupacion, T.telTrabajo, T.nacionalidad, N.nacion AS nacion
               FROM tutores T, nacionalidades N
                WHERE T.id='$id' AND T.nacionalidad = N.id";
                 $rs = mysqli_query($vConexion, $SQL);
                if($data = mysqli_fetch_array($rs)) {
                	$Tutor['ID'] = $data['id'];
            		$Tutor['APELLIDO'] = $data['apellido'];
            		$Tutor['NOMBRE'] = $data['nombre'];
            		$Tutor['DNI'] = $data['dni'];
            		$Tutor['TELEFONO'] = $data['telefono'];
                    $Tutor['MAIL'] = $data['mail'];
                    $Tutor['OCUPACION'] = $data['ocupacion'];
                    $Tutor['TELTRABAJO'] = $data['telTrabajo'];
                    $Tutor['NACIONALIDAD'] = $data['nacionalidad'];
                    $Tutor['NACION'] = $data['nacion'];
                }
        return $Tutor;
	}

    function TutorExiste($vConexion,$DNI){
        $Tutor=array();
        $mensaje = "";
        $SQL = "SELECT id
               FROM tutores
                WHERE dni='$DNI'";
                 $rs = mysqli_query($vConexion, $SQL);
                if($data = mysqli_fetch_array($rs)) {
                    $Tutor['ID'] = $data['id'];
                    $mensaje = "El DNI ingresado ya existe en la base de datos, y su nro de identificacion es: ".$Tutor['ID'];
                }
        
        return $mensaje;
    }
    function buscarTutorXDNI($vConexion,$DNI){
        $Tutor=array();
        $SQL = "SELECT T.id, T.apellido, T.nombre, T.dni, T.telefono, T.mail, T.ocupacion, T.telTrabajo, T.nacionalidad, N.nacion AS nacion
               FROM tutores T, nacionalidades N
                WHERE E.dni='$DNI' AND E.nacionalidad = N.id";
                 $rs = mysqli_query($vConexion, $SQL);
                if($data = mysqli_fetch_array($rs)) {
                    $Tutor['ID'] = $data['id'];
                    $Tutor['APELLIDO'] = $data['apellido'];
                    $Tutor['NOMBRE'] = $data['nombre'];
                    $Tutor['DNI'] = $data['dni'];
                    $Tutor['TELEFONO'] = $data['telefono'];
                    $Tutor['MAIL'] = $data['mail'];
                    $Tutor['OCUPACION'] = $data['ocupacion'];
                    $Tutor['TELTRABAJO'] = $data['telTrabajo'];
                    $Tutor['NACIONALIDAD'] = $data['nacionalidad'];
                    $Tutor['NACION'] = $data['nacion'];
                }
        return $Tutor;
    }
?>