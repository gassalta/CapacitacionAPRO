<?php
	function buscarEstudiante($vConexion,$id){
		$Estudiante=array();
		$SQL = "SELECT E.id, E.nroLegajo, E.nroLibroMatriz, E.nroFolio, E.apellido, E.nombre, E.dni, E.telefono, E.mail, E.nacionalidad, N.nacion AS nacion, E.escDeProcedencia, E.curso, C.Anio AS anio, C.Division AS division, E.lugarNacim, E.fechaNacim, E.domicilio, E.barrio, B.nombre AS nombreBarrio, E.fechaPreinscripcion, E.Padre, T.apellido AS apePadre, T.nombre AS nomPadre, E.Madre, T.apellido AS apeMadre, T.nombre AS nomMadre, E.Tutor, T.apellido AS apeTutor, T.nombre AS nomTutor
               FROM estudiantes E, nacionalidades N, cursos C, barrios B, tutores T
                WHERE E.id='$id' AND E.nacionalidad = N.id AND E.curso = C.Id AND E.barrio = B.id AND E.Padre = T.id AND E.Madre = T.id AND E.Tutor = T.id";
                 $rs = mysqli_query($vConexion, $SQL);
                if($data = mysqli_fetch_array($rs)) {
                	$Estudiante['ID'] = $data['id'];
                    $Estudiante['NROLEGAJO'] = $data['nroLegajo'];
                    $Estudiante['NROLIBROMATRIZ'] = $data['nroLibroMatriz'];
                    $Estudiante['NROFOLIO'] = $data['nroFolio'];
            		$Estudiante['APELLIDO'] = $data['apellido'];
            		$Estudiante['NOMBRE'] = $data['nombre'];
            		$Estudiante['DNI'] = $data['dni'];
            		$Estudiante['TELEFONO'] = $data['telefono'];
                    $Estudiante['MAIL'] = $data['mail'];
                    $Estudiante['NACIONALIDAD'] = $data['nacionalidad'];
                    $Estudiante['NACION'] = $data['nacion'];
                    $Estudiante['ESCDEPROCEDENCIA'] = $data['escDeProcedencia'];
                    $Estudiante['CURSO'] = $data['curso'];
                    $Estudiante['ANIO'] = $data['anio'];
                    $Estudiante['DIVISION'] = $data['division'];
                    $Estudiante['LUGARNACIM'] = $data['lugarNacim'];
                    $Estudiante['FECHANACIM'] = $data['fechaNacim'];
                    $Estudiante['DOMICILIO'] = $data['domicilio'];
                    $Estudiante['BARRIO'] = $data['barrio'];
                    $Estudiante['NOMBREBARRIO'] = $data['nombreBarrio'];
                    $Estudiante['FECHAPREINSCRIPCION'] = $data['fechaPreinscripcion'];
                    $Estudiante['PADRE'] = $data['Padre'];
            		$Estudiante['APEPADRE'] = $data['apePadre'];
            		$Estudiante['NOMPADRE'] = $data['nomPadre'];
                    $Estudiante['MADRE'] = $data['Madre'];
            		$Estudiante['APEMADRE'] = $data['apeMadre'];
                    $Estudiante['NOMMADRE'] = $data['nomMadre'];
                    $Estudiante['TUTOR'] = $data['Tutor'];
            		$Estudiante['APETUTOR'] = $data['apeTutor'];
                    $Estudiante['NOMTUTOR'] = $data['nomTutor'];
                } else {
                    $SQL = "SELECT E.id, E.nroLegajo, E.nroLibroMatriz, E.nroFolio, E.apellido, E.nombre, E.dni, E.telefono, E.mail, E.nacionalidad, N.nacion AS nacion, E.escDeProcedencia, E.curso, E.lugarNacim, E.fechaNacim, E.domicilio, E.barrio, B.nombre AS nombreBarrio, E.fechaPreinscripcion, E.Padre, T.apellido AS apePadre, T.nombre AS nomPadre, E.Madre, T.apellido AS apeMadre, T.nombre AS nomMadre, E.Tutor, T.apellido AS apeTutor, T.nombre AS nomTutor
               FROM estudiantes E, nacionalidades N, barrios B, tutores T
                WHERE E.id='$id' AND E.nacionalidad = N.id AND E.barrio = B.id AND E.Padre = T.id AND E.Madre = T.id AND E.Tutor = T.id";
                 $rs = mysqli_query($vConexion, $SQL);
                 if($data = mysqli_fetch_array($rs)) {
                    $Estudiante['ID'] = $data['id'];
                    $Estudiante['NROLEGAJO'] = $data['nroLegajo'];
                    $Estudiante['NROLIBROMATRIZ'] = $data['nroLibroMatriz'];
                    $Estudiante['NROFOLIO'] = $data['nroFolio'];
                    $Estudiante['APELLIDO'] = $data['apellido'];
                    $Estudiante['NOMBRE'] = $data['nombre'];
                    $Estudiante['DNI'] = $data['dni'];
                    $Estudiante['TELEFONO'] = $data['telefono'];
                    $Estudiante['MAIL'] = $data['mail'];
                    $Estudiante['NACIONALIDAD'] = $data['nacionalidad'];
                    $Estudiante['NACION'] = $data['nacion'];
                    $Estudiante['ESCDEPROCEDENCIA'] = $data['escDeProcedencia'];
                    $Estudiante['CURSO'] = $data['curso'];
                    $Estudiante['ANIO'] = "";
                    $Estudiante['DIVISION'] = "";
                    $Estudiante['LUGARNACIM'] = $data['lugarNacim'];
                    $Estudiante['FECHANACIM'] = $data['fechaNacim'];
                    $Estudiante['DOMICILIO'] = $data['domicilio'];
                    $Estudiante['BARRIO'] = $data['barrio'];
                    $Estudiante['NOMBREBARRIO'] = $data['nombreBarrio'];
                    $Estudiante['FECHAPREINSCRIPCION'] = $data['fechaPreinscripcion'];
                    $Estudiante['PADRE'] = $data['Padre'];
                    $Estudiante['APEPADRE'] = $data['apePadre'];
                    $Estudiante['NOMPADRE'] = $data['nomPadre'];
                    $Estudiante['MADRE'] = $data['Madre'];
                    $Estudiante['APEMADRE'] = $data['apeMadre'];
                    $Estudiante['NOMMADRE'] = $data['nomMadre'];
                    $Estudiante['TUTOR'] = $data['Tutor'];
                    $Estudiante['APETUTOR'] = $data['apeTutor'];
                    $Estudiante['NOMTUTOR'] = $data['nomTutor'];
                } else {
                    $SQL = "SELECT E.id, E.nroLegajo, E.nroLibroMatriz, E.nroFolio, E.apellido, E.nombre, E.dni, E.telefono, E.mail, E.nacionalidad, N.nacion AS nacion, E.escDeProcedencia, E.curso, C.Anio AS anio, C.Division AS division, E.lugarNacim, E.fechaNacim, E.domicilio, E.barrio, B.nombre AS nombreBarrio, E.fechaPreinscripcion, E.Padre, E.Madre, E.Tutor
               FROM estudiantes E, nacionalidades N, cursos C, barrios B
                WHERE E.id='$id' AND E.nacionalidad = N.id AND E.curso = C.Id AND E.barrio = B.id";
                 $rs = mysqli_query($vConexion, $SQL);
                if($data = mysqli_fetch_array($rs)) {
                    $Estudiante['ID'] = $data['id'];
                    $Estudiante['NROLEGAJO'] = $data['nroLegajo'];
                    $Estudiante['NROLIBROMATRIZ'] = $data['nroLibroMatriz'];
                    $Estudiante['NROFOLIO'] = $data['nroFolio'];
                    $Estudiante['APELLIDO'] = $data['apellido'];
                    $Estudiante['NOMBRE'] = $data['nombre'];
                    $Estudiante['DNI'] = $data['dni'];
                    $Estudiante['TELEFONO'] = $data['telefono'];
                    $Estudiante['MAIL'] = $data['mail'];
                    $Estudiante['NACIONALIDAD'] = $data['nacionalidad'];
                    $Estudiante['NACION'] = $data['nacion'];
                    $Estudiante['ESCDEPROCEDENCIA'] = $data['escDeProcedencia'];
                    $Estudiante['CURSO'] = $data['curso'];
                    $Estudiante['ANIO'] = $data['anio'];
                    $Estudiante['DIVISION'] = $data['division'];
                    $Estudiante['LUGARNACIM'] = $data['lugarNacim'];
                    $Estudiante['FECHANACIM'] = $data['fechaNacim'];
                    $Estudiante['DOMICILIO'] = $data['domicilio'];
                    $Estudiante['BARRIO'] = $data['barrio'];
                    $Estudiante['NOMBREBARRIO'] = $data['nombreBarrio'];
                    $Estudiante['FECHAPREINSCRIPCION'] = $data['fechaPreinscripcion'];
                    $Estudiante['PADRE'] = $data['Padre'];
                    $Estudiante['APEPADRE'] = "";
                    $Estudiante['NOMPADRE'] = "";
                    $Estudiante['MADRE'] = $data['Madre'];
                    $Estudiante['APEMADRE'] = "";
                    $Estudiante['NOMMADRE'] = "";
                    $Estudiante['TUTOR'] = $data['Tutor'];
                    $Estudiante['APETUTOR'] = "";
                    $Estudiante['NOMTUTOR'] = "";
                } else {
                    $SQL = "SELECT E.id, E.nroLegajo, E.nroLibroMatriz, E.nroFolio, E.apellido, E.nombre, E.dni, E.telefono, E.mail, E.nacionalidad, N.nacion AS nacion, E.escDeProcedencia, E.curso, E.lugarNacim, E.fechaNacim, E.domicilio, E.barrio, B.nombre AS nombreBarrio, E.fechaPreinscripcion, E.Padre, E.Madre, E.Tutor
               FROM estudiantes E, nacionalidades N, barrios B
                WHERE E.id='$id' AND E.nacionalidad = N.id AND E.barrio = B.id";
                 $rs = mysqli_query($vConexion, $SQL);
                if($data = mysqli_fetch_array($rs)) {
                    $Estudiante['ID'] = $data['id'];
                    $Estudiante['NROLEGAJO'] = $data['nroLegajo'];
                    $Estudiante['NROLIBROMATRIZ'] = $data['nroLibroMatriz'];
                    $Estudiante['NROFOLIO'] = $data['nroFolio'];
                    $Estudiante['APELLIDO'] = $data['apellido'];
                    $Estudiante['NOMBRE'] = $data['nombre'];
                    $Estudiante['DNI'] = $data['dni'];
                    $Estudiante['TELEFONO'] = $data['telefono'];
                    $Estudiante['MAIL'] = $data['mail'];
                    $Estudiante['NACIONALIDAD'] = $data['nacionalidad'];
                    $Estudiante['NACION'] = $data['nacion'];
                    $Estudiante['ESCDEPROCEDENCIA'] = $data['escDeProcedencia'];
                    $Estudiante['CURSO'] = $data['curso'];
                    $Estudiante['ANIO'] = "";
                    $Estudiante['DIVISION'] = "";
                    $Estudiante['LUGARNACIM'] = $data['lugarNacim'];
                    $Estudiante['FECHANACIM'] = $data['fechaNacim'];
                    $Estudiante['DOMICILIO'] = $data['domicilio'];
                    $Estudiante['BARRIO'] = $data['barrio'];
                    $Estudiante['NOMBREBARRIO'] = $data['nombreBarrio'];
                    $Estudiante['FECHAPREINSCRIPCION'] = $data['fechaPreinscripcion'];
                    $Estudiante['PADRE'] = $data['Padre'];
                    $Estudiante['APEPADRE'] = "";
                    $Estudiante['NOMPADRE'] = "";
                    $Estudiante['MADRE'] = $data['Madre'];
                    $Estudiante['APEMADRE'] = "";
                    $Estudiante['NOMMADRE'] = "";
                    $Estudiante['TUTOR'] = $data['Tutor'];
                    $Estudiante['APETUTOR'] = "";
                    $Estudiante['NOMTUTOR'] = "";
                }
                }
                }
                }
        return $Estudiante;
	}

    function estudianteExiste($vConexion,$DNI){
        $Estudiante=array();
        $mensaje = "";
        $SQL = "SELECT id
               FROM estudiantes
                WHERE dni='$DNI'";
                 $rs = mysqli_query($vConexion, $SQL);
                if($data = mysqli_fetch_array($rs)) {
                    $Estudiante['ID'] = $data['id'];
                    $mensaje = "El DNI ingresado ya existe en la base de datos, y su nro de identificacion es: ".$Estudiante['ID'];
                }
        
        return $mensaje;
    }
    function estudianteExisteOtro($vConexion,$DNI, $Id){
        $Estudiante=array();
        $mensaje = "";
        $SQL = "SELECT id
               FROM estudiantes
                WHERE dni='$DNI' AND id!='$Id'";
                 $rs = mysqli_query($vConexion, $SQL);
                if($data = mysqli_fetch_array($rs)) {
                    $Estudiante['ID'] = $data['id'];
                    $mensaje = "El DNI ingresado corresponde al estudiante Nro: ".$Estudiante['ID'];
                }
        
        return $mensaje;
    }
    function buscarEstudianteXDNI($vConexion,$DNI){
        $Estudiante=array();
        $SQL = "SELECT E.id, E.nroLegajo, E.nroLibroMatriz, E.nroFolio, E.apellido, E.nombre, E.dni, E.telefono, E.mail, N.nacion AS nacionalidad, E.escDeProcedencia, C.Anio AS anio, C.Division AS division, E.lugarNacim, E.fechaNacim, E.domicilio, B.nombre AS barrio, E.fechaPreinscripcion, T.apellido AS apePadre, T.nombre AS nomPadre, T.apellido AS apeMadre, T.nombre AS nomMadre, T.apellido AS apeTutor, T.nombre AS nomTutor
               FROM estudiantes E, nacionalidades N, cursos C, barrios B, tutores T
                WHERE E.dni='$DNI' AND E.nacionalidad = N.id AND E.curso = C.Id AND E.barrio = B.id AND E.Padre = T.id AND E.Madre = T.id AND E.Tutor = T.id";
                 $rs = mysqli_query($vConexion, $SQL);
                if($data = mysqli_fetch_array($rs)) {
                    $Estudiante['ID'] = $data['id'];
                    $Estudiante['NROLEGAJO'] = $data['nroLegajo'];
                    $Estudiante['NROLIBROMATRIZ'] = $data['nroLibroMatriz'];
                    $Estudiante['NROFOLIO'] = $data['nroFolio'];
                    $Estudiante['APELLIDO'] = $data['apellido'];
                    $Estudiante['NOMBRE'] = $data['nombre'];
                    $Estudiante['DNI'] = $data['dni'];
                    $Estudiante['TELEFONO'] = $data['telefono'];
                    $Estudiante['MAIL'] = $data['mail'];
                    $Estudiante['NACIONALIDAD'] = $data['nacionalidad'];
                    $Estudiante['ESCDEPROCEDENCIA'] = $data['escDeProcedencia'];
                    $Estudiante['ANIO'] = $data['anio'];
                    $Estudiante['DIVISION'] = $data['division'];
                    $Estudiante['LUGARNACIM'] = $data['lugarNacim'];
                    $Estudiante['FECHANACIM'] = $data['fechaNacim'];
                    $Estudiante['DOMICILIO'] = $data['domicilio'];
                    $Estudiante['BARRIO'] = $data['barrio'];
                    $Estudiante['FECHAPREINSCRIPCION'] = $data['fechaPreinscripcion'];
                    $Estudiante['APEPADRE'] = $data['apePadre'];
                    $Estudiante['NOMPADRE'] = $data['nomPadre'];
                    $Estudiante['APEMADRE'] = $data['apeMadre'];
                    $Estudiante['NOMMADRE'] = $data['nomMadre'];
                    $Estudiante['APETUTOR'] = $data['apeTutor'];
                    $Estudiante['NOMTUTOR'] = $data['nomTutor'];
                }
        return $Estudiante;
    }
    function buscarEstudianteSimple($vConexion,$id){
        $Estudiante=array();
        $SQL = "SELECT E.id, E.nroLegajo, E.nroLibroMatriz, E.nroFolio, E.apellido, E.nombre, E.dni
               FROM estudiantes E
                WHERE E.id='$id'";
                 $rs = mysqli_query($vConexion, $SQL);
                if($data = mysqli_fetch_array($rs)) {
                    $Estudiante['ID'] = $data['id'];
                    $Estudiante['NROLEGAJO'] = $data['nroLegajo'];
                    $Estudiante['NROLIBROMATRIZ'] = $data['nroLibroMatriz'];
                    $Estudiante['NROFOLIO'] = $data['nroFolio'];
                    $Estudiante['APELLIDO'] = $data['apellido'];
                    $Estudiante['NOMBRE'] = $data['nombre'];
                    $Estudiante['DNI'] = $data['dni'];
                } 
        return $Estudiante;
    }
?>